<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

error_reporting(-1);
ini_set('display_errors', 'On');

class TestController extends Controller
{
    public function fetch()
    {
        
        $send_response = $this->sms->send(71234567890, '1111');
        var_dump($send_response);
        
        exit;
    }
   
}