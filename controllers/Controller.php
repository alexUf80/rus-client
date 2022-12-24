<?php

error_reporting(0);
ini_set('display_errors', 'Off');
ini_set('html_errors', 'Off');
class Controller extends Core
{
	public $user;
	public $page;
	
	private static $models;
	
	public function __construct()
	{
		parent::__construct();
		
		if(self::$models)
		{
			$this->user         = &self::$models->user;
			$this->page         = &self::$models->page;	
		}
		else
		{
			self::$models = $this;

			if (!empty($_COOKIE['user_id']) && !empty($_COOKIE['redirect_token']))
            {
                if ($user = $this->users->get_user((int)$_COOKIE['user_id']))
                {
                    if ($user->redirect_token == $_COOKIE['redirect_token'])
                    {
                        $this->users->update_user($user->id, array('redirect_token' => ''));
                        
                        $_SESSION['user_id'] = $user->id;
                        if (!empty($_COOKIE['looker']))
                            $_SESSION['looker_mode'] = 1;
                        
                        setcookie('redirect_token', NULL, time()-1, '/', '.nalichnoeplus.ru');
                        setcookie('user_id', NULL, time()-1, '/', '.nalichnoeplus.ru');
                        setcookie('looker', NULL, time()-1, '/', '.nalichnoeplus.ru');
                    }
                }
            }
            if(isset($_SESSION['user_id']))
			{
				if (!($user = $this->users->get_user(intval($_SESSION['user_id']))))
                {
                    unset($_SESSION['user_id']);
                }
                else
                {
                    $user->contract = $this->contracts->get_last_contract($user->id);
                    
                    $this->user = $user;                                    
                }

			}

			// Текущая страница (если есть)
			$subdir = substr(dirname(dirname(__FILE__)), strlen($_SERVER['DOCUMENT_ROOT']));
			$page_url = trim(substr($_SERVER['REQUEST_URI'], strlen($subdir)),"/");
			if(strpos($page_url, '?') !== false)
				$page_url = substr($page_url, 0, strpos($page_url, '?'));
			$this->page = $this->pages->get_page((string)$page_url);
			$this->design->assign('page', $this->page);		
			
            $this->design->assign('is_developer', $this->is_developer);
            $this->design->assign('is_looker', $this->is_looker);
            
			// Передаем в дизайн то, что может понадобиться в нем
			$this->design->assign('user',	$this->user);
			
			$this->design->assign('config',		$this->config);
			$this->design->assign('settings',	$this->settings);

		}
	}
		
	function fetch()
	{
		return false;
	}
	
}
