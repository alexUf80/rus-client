<?php

error_reporting(-1);
ini_set('display_errors', 'On');

session_start();

chdir('..');
require 'autoload.php';

class ProlongationSum extends Core
{
    public function run()
    {
        switch ($this->request->get('action')):

            case 'check_prolongation_sum':
                $this->check_prolongation_sum();
                break;

        endswitch;
    }

    private function check_prolongation_sum()
    {
        echo json_encode(['sum' => $this->settings->prolongation_amount]);
    }
}
$sms_code = new ProlongationSum();
$sms_code->run();