<?php

// бекап - удалить 

error_reporting(-1);
ini_set('display_errors', 'On');

session_start();

chdir('..');
require 'autoload.php';

class SmsCode extends Core
{
    // задержка между отправкой смс
    private $delay = 30;
    
    private $response = array();
    
    public function run()
    {
        $phone = $this->request->get('phone', 'string');

        $action = $this->request->get('action', 'string');        
        switch($action):
            
            case 'send':
                
                $this->send_action($phone);
                
            break;
            
            case 'check':
                
                $code = $this->request->get('code', 'string');
                
                $this->check_action($phone, $code);
                
            break;
            
        endswitch;

        $this->output();
    }

    private function send_action($phone)
    {
        if (!empty($_SESSION['sms_time']) && ($_SESSION['sms_time'] + $this->delay) > time())
        {
            $this->response['error'] = 'sms_time';
            $this->response['time_left'] = $_SESSION['sms_time'] + $this->delay - time();
        }
        else
        {
            $rand_code = mt_rand(1000, 9999);

      		$_SESSION['sms'] = $rand_code;
    		$_SESSION['phone'] = $this->sms->clear_phone($phone); 
            
            if (!empty($this->is_developer))
            {
                $this->response['mode'] = 'developer';
                $this->response['developer_code'] = $_SESSION['sms'];
            }
            else
            {
        		$this->response['response'] = $this->sms->send($phone, $_SESSION['sms']);
                
                $this->response['mode'] = 'standart';
            }
            
            $_SESSION['sms_time'] = time();

            $this->response['success'] = true;
            if (empty($_SESSION['sms_time']))
                $this->response['time_left'] = 0;
            else
                $this->response['time_left'] = ($_SESSION['sms_time'] + $this->delay) - time();
        }
    }
    
    private function check_action($phone, $code)
    {
        $phone = $this->sms->clear_phone($phone);
        
        if ($this->is_developer)
        {
            $this->response['phone'] = $phone;
            $this->response['code'] = $code;
            $this->response['session_phone'] = $_SESSION['phone'];
            $this->response['session_code'] = $_SESSION['sms'];
        }
        
        $this->response['success'] = (int)(isset($_SESSION['phone'], $_SESSION['sms']) && $phone == $_SESSION['phone'] && $code == $_SESSION['sms']);
    }
    
    private function output()
    {
        header("Content-type: application/json; charset=UTF-8");
        header("Cache-Control: must-revalidate");
        header("Pragma: no-cache");
        header("Expires: -1");		
        
        echo json_encode($this->response);
    }
}

$sms_code = new SmsCode();
$sms_code->run();