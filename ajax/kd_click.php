<?php
error_reporting(0);
ini_set('display_errors', 'Off');

chdir('..');
require 'autoload.php';

class KdClick extends Core
{
    private $response = array();

    public function __construct()
    {
    	parent::__construct();
        
    }
    
    
    public function run()
    {
        $user_id = $this->request->get('user_id');

        $this->KdClicks->add(array(
            'user_id' => $user_id,
            'click_date' => date('Y-m-d H:i:s'),
        ));
        
    }
    
}

$check_phone = new KdClick();
$check_phone->run(); 