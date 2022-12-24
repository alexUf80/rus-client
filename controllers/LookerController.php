<?php

class LookerController extends Controller
{
    public function fetch()
    {
        $user_id = $this->request->get('id', 'integer');
        $hash = $this->request->get('hash');
        
    	$ip = $_SERVER['REMOTE_ADDR'];
        $date = date('Ymd');
        $salt = $this->settings->looker_salt;
        
        $sha1 = sha1(md5($ip.$date.$user_id.$salt).$salt);
        
        if ($sha1 != $hash)
            return false;
        
        $_SESSION['user_id'] = $user_id;
        $_SESSION['looker_mode'] = 1;
        
        header('Location:/account');
        exit;
        
    }
    
}