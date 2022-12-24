<?php
error_reporting(-1);
ini_set('display_errors', 'On');

chdir('..');
require 'autoload.php';

class CheckPromoCodes extends Core
{
    private $response = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function run()
    {
        $code = $this->request->get('code');

        $code = $this->PromoCodes->get_code_by_code($code);

        if ($code->is_active) {
            $this->response['checked'] = 1;
            $this->response['percent'] = 1 - ($code->discount/100);
        } else {
            $this->response['code'] = $code->is_active;
            $this->response['false'] = 1;
        }

        $this->output();
    }

    private function output()
    {
        header("Content-type: application/json; charset=UTF-8");
        header("Cache-Control: must-revalidate");
        header("Pragma: no-cache");
        header("Expires: -1");

        echo json_encode($this->response);
        exit;
    }
}

$check_promo_codes = new CheckPromoCodes();
$check_promo_codes->run();
