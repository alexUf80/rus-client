<?php

class DocumentationController extends Controller
{
    public function fetch()
    {
        if ($_SERVER['REQUEST_URI'] == '/documentation') {
            $this->design->assign('meta_title', 'Документы');
            $this->design->assign('page_type', 'documentation');
        } else {
            $this->design->assign('meta_title', 'Архив');
            $this->design->assign('page_type', 'archive');
        };

        return $this->design->fetch('documentation.tpl');
    }
}
