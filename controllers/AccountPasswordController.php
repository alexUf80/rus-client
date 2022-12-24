<?php

class AccountPasswordController extends Controller
{
    public function fetch()
    {
        if(empty($this->user))
		{
			header('Location: '.$this->config->root_url.'/lk/login');
			exit();
		}        
        
        if ($this->request->method('post'))
        {
            if (!empty($_SESSION['looker_mode']))
                return false;

            $password = $this->request->post('password');
            
            if (empty($password))
            {
                $this->design->assign('error', 'Укажите пароль');
            }
            else
            {
                $this->users->update_user($this->user->id, array('password' => $password));
                
                if (empty($this->user->password))
                {
                    header('Location:/account');
                    exit;
                }
                else
                {
                    $this->design->assign('success', 'Пароль изменен');
                }
                
            }
        }
        else
        {
            
        }
        
        $this->design->assign('empty_password', empty($this->user->password));
        
        return $this->design->fetch('account/password.tpl');
    }
}