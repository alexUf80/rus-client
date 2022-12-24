<?php

session_start();
chdir('..');

require 'autoload.php';

$code = new Core();

class LocalTimeUpdate extends Core
{
    public function __construct()
    {
    	$this->run();
    }
    
    private function run()
    {
    	if (!($local_time = $this->request->get('local')))
            return false;
        
        if (empty($_SESSION['user_id']))
            return false;
        
        if (!($user = $this->users->get_user((int)$_SESSION['user_id'])))
            return false;
        
        $this->users->update_user($user->id, array(
            'last_local_time' => $local_time,
            'last_activity' => time(),
            'last_ip' => $_SERVER['REMOTE_ADDR'],
        ));
    }
    
}

new LocalTimeUpdate();