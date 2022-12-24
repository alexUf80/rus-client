<?php
error_reporting(0);
ini_set('display_errors', 'Off');

chdir('..');
require 'autoload.php';

class CheckPhone extends Core
{
    private $response = array();

    public function __construct()
    {
    	parent::__construct();
        
    }
    
    
    public function run()
    {
        $phone = $this->request->get('phone');

        $clear_phone = $this->sms->clear_phone($phone);

        $user_id = $this->users->get_phone_user($clear_phone);

        $user = $this->users->get_user($user_id);

        if (strlen($clear_phone) != 11)
        {
            $this->response['incorrect'] = $clear_phone;
        }
        else
        {
            if ($user->blocked == 1) {
                $this->response['user_removed'] = 1;
                $this->output();
            }

            if ($exist_id = $this->users->get_phone_user($clear_phone))
            {
                $this->response['user_exists'] = 1;

                $user = $this->users->get_user((int)$exist_id);
                if (empty($this->is_developer))
                    $this->response['have_pass'] = (int)!empty($user->password);
            }
            else
            {
                $this->response['not_found'] = 1;
            }

        }

        $this->output();
    }
    
    private function output()
    {
       	header("Content-type: application/json; charset=UTF-8");
        header("Cache-Control: must-revalidate");
        header("Pragma: no-cache");
        header("Expires: -1");	
        
        echo json_encode($this->response);
        exit;

    }
}

$check_phone = new CheckPhone();
$check_phone->run(); 