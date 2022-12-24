<?php
error_reporting(-1);
ini_set('display_errors', 'On');

session_start();

chdir('..');
require 'autoload.php';

class UploadUserImages extends Core
{
    private $response = array();
    
    public function __construct()
    {
    	parent::__construct();
        
        $this->run();
    }
    
    private function run()
    {
    	if (!($uid = $this->request->get('uid')))
        {
            $this->response['error'] = 'empty uid';
            $this->output();
        }
        
        if (!($user_id = $this->users->get_uid_user($uid)))
        {
            $this->response['error'] = 'uid not found';
            $this->output();            
        }
        
        $client_images = $this->soap1c->get_client_images($uid);
        $this->response['items'] = $this->import1c->import_images($user_id, $client_images);
    
        $this->response['success'] = 1;
        
        $this->output();
    }
    
    private function output()
    {
    	header('Content-type: application/json');
        echo json_encode($this->response);
        exit;
    }
    
}
new UploadUserImages();