<?php
error_reporting(-1);
ini_set('display_errors', 'On');
class Location_scoring extends Core
{
    private $user_id;
    private $order_id;
    private $audit_id;
    private $type;
    private $exception_regions;
    
    
    
    public function run($audit_id, $user_id, $order_id)
    {
        $this->user_id = $user_id;
        $this->audit_id = $audit_id;
        $this->order_id = $order_id;
        
        $this->type = $this->scorings->get_type('location');
        $this->exception_regions = explode(',', $this->type->params['regions']);
        
        
        $user = $this->users->get_user((int)$user_id);
        
        return $this->scoring($user->Regregion);        
    }

    private function scoring($region_name)
    {
        $score = !in_array(mb_strtolower($region_name, 'utf8'), $this->exception_regions);
        
        $found = 0;
        foreach ($this->exception_regions as $region)
        {
            if (stripos($region_name, $region) !== false)
                $found = 1;
        }
echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($this->exception_regions, $region, $found);echo '</pre><hr />';
//        $score = !$found;
        
        $add_scoring = array(
            'user_id' => $this->user_id,
            'audit_id' => $this->audit_id,
            'type' => 'location',
            'body' => $region_name,
            'success' => (int)$score
        );
        if ($score)
        {
            $add_scoring['string_result'] = 'Допустимый регион: '.$region_name;
        }
        else
        {
            $add_scoring['string_result'] = 'Недопустимый регион: '.$region_name;
        }
        
        $this->scorings->add_scoring($add_scoring);
        
        return $score;
    }

}