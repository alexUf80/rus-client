<?php
error_reporting(-1);
ini_set('display_errors', 'On');
chdir('..');

require 'autoload.php';

class RunScoringsApp extends Core
{
    private $response = array();
    
    public function run()
    {
    	$action = $this->request->get('action', 'string');
        
        switch ($action):
            
            case 'create':
            
                $type = $this->request->get('type', 'string');
                $order_id = $this->request->get('order_id', 'integer');
                
                $scoring_types = $this->scorings->get_types();
                
                if ($order = $this->orders->get_order($order_id))
                {
                    if ($type == 'all')
                    {
                        $audit = new StdClass();
                        $audit->status = 'new';
                        $audit->order_id = $order->order_id;
                        $audit->user_id = $order->user_id;
                        
                        $audit->types = array();
                        foreach ($scoring_types as $scoring_type)
                            if ($scoring_type->active)
                                $audit->types[] = $scoring_type->name;
                        
                        $this->response['audit_id'] = $this->scorings->add_audit($audit);
                        $this->response['success'] = 1;
                        
                    }
                }
                else
                {
                    $this->response['error'] = 'undefined_order';
                }
                
            break;
            
        endswitch;
    
        echo json_encode($this->response);
    }
    
}

$app = new RunScoringsApp();
$app->run();