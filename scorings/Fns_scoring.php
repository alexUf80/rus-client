<?php

class Fns_scoring extends Core
{
    private $user_id;
    private $order_id;
    private $audit_id;
    private $type;
    
    private $url = "https://service.nalog.ru/inn-proc.do";
    
    public function run($audit_id, $user_id, $order_id)
    {
        $this->user_id = $user_id;
        $this->audit_id = $audit_id;
        $this->order_id = $order_id;
        
        $this->type = $this->scorings->get_type('fns');
    	
        $user = $this->users->get_user((int)$user_id);

        return $this->scoring($user);
    }
    
    public function scoring($user)
    {
        $birthday = date('d.m.Y', strtotime($user->birth));
        $passportdate = date('d.m.Y', strtotime($user->passport_date));
        $fns = $this->get_inn($user->lastname, $user->firstname, $user->patronymic, $birthday, 21, $user->passport_serial, $passportdate);

        if (!empty($fns->code))
        {
            $scoring = array(
                'user_id' => $user->id,
                'audit_id' => $this->audit_id,
                'type' => 'fns',
                'body' => $fns->inn,
                'success' => 1,
                'scorista_id' => '',
                'string_result' => 'ИНН найден'
            );
            $this->scorings->add_scoring($scoring);
            
            
        }
        else
        {
            $scoring = array(
                'user_id' => $user->id,
                'audit_id' => $this->audit_id,
                'type' => 'fns',
                'body' => '',
                'success' => 0,
                'scorista_id' => '',
                'string_result' => 'ИНН не найден'
            );
            $this->scorings->add_scoring($scoring);
        }

    }
    public function get_inn($surname, $name, $patronymic, $birthdate, $doctype, $docnumber, $docdate)
    {

        $data = array(
            "fam" => $surname,
            "nam" => $name,
            "otch" => $patronymic,
            "bdate" => $birthdate,
            "bplace" => "",
            "doctype" => $doctype,
            "docno" => $docnumber,
            "docdt" => $docdate,
            "c" => "innMy",
            "captcha" => "",
            "captchaToken" => ""
        );
        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => array(
                    'Content-type: application/x-www-form-urlencoded',
                ),
                'content' => http_build_query($data)
            ),
        );
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($data);echo '</pre><hr />';
        $context = stream_context_create($options);
        $result = file_get_contents($this->url, false, $context);
    
        return json_decode($result);
    }
}