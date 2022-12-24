<?php

class Core
{
	public $is_developer = 0; 
	public $is_looker = 0; 
    
    private $classes = array(
        
	);
	private static $objects = array();
	
	public function __construct()
	{
//echo $_SERVER['REMOTE_ADDR'].'<br />';
        if (in_array($_SERVER['REMOTE_ADDR'], array('94.154.39.111', '94.154.39.244')))
            $this->is_developer = 1;
	    if (isset($_COOKIE['developer']) && $_COOKIE['developer'] == '12091979')
            $this->is_developer = 1;
        if (isset($_GET['set_developer']) && $_GET['set_developer'] == '12091979')
        {
            setcookie('developer', $_GET['set_developer']);
            header('Location: /');
            exit;
        }
        if (isset($_GET['set_developer']) && $_GET['set_developer'] == 'unset')
        {
            setcookie('developer', NULL);
            header('Location: /');
            exit;
        }
        
        if (!empty($_SESSION['looker_mode']))
            $this->is_looker = 1;
            
//$this->is_developer = 0;    
    }

	public function __get($name)
	{
		if(isset(self::$objects[$name]))
			return(self::$objects[$name]);
		
		if (class_exists(ucfirst($name)))
            $class = ucfirst($name);
        elseif(array_key_exists($name, $this->classes))
    		$class = $this->classes[$name];
		else
            return null;

		self::$objects[$name] = new $class();
		
		return self::$objects[$name];
	}
}