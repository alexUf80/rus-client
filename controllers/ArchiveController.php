<?php

class ArchiveController extends Controller
{
    public function fetch()
    {
        return $this->design->fetch('archive.tpl');
    }
}
