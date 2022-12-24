<?php

class PageQRController extends Controller
{
	function fetch()
	{
		$url = 'для-qr-кода';

        if ($url == '404')
        {
    		$this->design->assign('meta_title', 'Страница не найдена');
            return $this->design->fetch('404.tpl');
        }
        
        if ($url == '403')
        {
    		$this->design->assign('meta_title', 'Не достаточно прав для просмотра');
            return $this->design->fetch('403.tpl');
            exit;
        }
        
		$page = $this->pages->get_page($url);
		
		// Отображать скрытые страницы только админу
		if(empty($page))
			return false;
		
		$this->design->assign('page', $page);
		$this->design->assign('meta_title', $page->meta_title);
		$this->design->assign('meta_keywords', $page->meta_keywords);
		$this->design->assign('meta_description', $page->meta_description);
		
		return $this->design->fetch('page.tpl');
	}
}