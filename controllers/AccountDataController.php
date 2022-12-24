<?php

class AccountDataController extends Controller
{
    public function fetch()
    {
        if (empty($this->user))
        {
            header('Location: /lk/login');
            exit;
        }
    	
        return $this->design->fetch('account/data.tpl');
    }
    
}