<?php

class DocumentationController extends Controller
{
    public function fetch()
    {
        return $this->design->fetch('documentation.tpl');
    }
}
