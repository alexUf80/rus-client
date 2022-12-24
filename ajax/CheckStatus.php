<?php

error_reporting(0);
ini_set('display_errors', 'Off');

chdir('..');
require 'autoload.php';

class CheckStatus extends Core
{
    public function __construct()
    {
        parent::__construct();
    }

    public function run()
    {
        $orderId = $this->request->post('orderId');

        $order = $this->orders->get_order($orderId);

        echo $order->status;
    }
}
$checkStatus = new CheckStatus();
$checkStatus->run();