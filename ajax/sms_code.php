<?php
error_reporting(-1);
ini_set('display_errors', 'On');

session_start();

chdir('..');
require 'autoload.php';

class SmsCode extends Core
{
    // задержка между отправкой смс
    private $delay = 30;

    private $response = array();

    public function run()
    {
        $phone = $this->request->get('phone', 'string');

        $action = $this->request->get('action', 'string');
        switch ($action):

            case 'send':

                $this->send_action($phone);

                break;

            case 'send_accept_code':

                $this->send_accept_code_action($phone);

                break;

            case 'check':

                $code = $this->request->get('code', 'string');

                $this->check_action($phone, $code);

                break;

            case 'check_accept_sms':

                $this->check_accept_sms_action();

                break;

        endswitch;

        $this->output();
    }

    private function check_accept_sms_action()
    {
        $accept_code = $this->request->get('code');
        $contract_id = $this->request->get('contract_id');

        if ($contract = $this->contracts->get_contract($contract_id)) {
            if ($contract->accept_code == $accept_code) {
                $this->response['success'] = 1;
            } else {
                $this->response['error'] = 'Код не совпадает';
            }
        } else {
            $this->response['error'] = 'Договор не найден!';
        }
    }


    private function send_accept_code_action($phone)
    {
        if (!empty($_SESSION['sms_time']) && ($_SESSION['sms_time'] + $this->delay) > time()) {
            $this->response['error'] = 'sms_time';
            $this->response['time_left'] = $_SESSION['sms_time'] + $this->delay - time();
        } else {
            $contract_id = $this->request->get('contract_id', 'integer');
            if ($contract = $this->contracts->get_contract($contract_id)) {
                $msg = 'Активируй займ ' . ($contract->amount * 1) . ' в личном кабинете, код' . $contract->accept_code;
                if (!empty($this->is_developer)) {
                    $this->response['mode'] = 'developer';
                    $this->response['developer_code'] = $contract->accept_code;

                    $this->response['message'] = $msg;
                } else {
                    $send_response = $this->sms->send($phone, $msg);
                    $send_response = $send_response['response'];

                    $message =
                        [
                            'code' => $contract->accept_code,
                            'phone' => $phone,
                            'response' => "$send_response"
                        ];

                    $this->sms->add_message($message);

                    $this->response['response'] = $send_response;

                    $this->response['mode'] = 'standart';
                }

                $_SESSION['sms_time'] = time();

                $this->response['success'] = true;
                if (empty($_SESSION['sms_time']))
                    $this->response['time_left'] = 0;
                else
                    $this->response['time_left'] = ($_SESSION['sms_time'] + $this->delay) - time();

            } else {
                $this->response['error'] = 'Договор не найден!';
            }
        }
    }

    private function send_action($phone)
    {
        if (!empty($_SESSION['sms_time']) && ($_SESSION['sms_time'] + $this->delay) > time()) {
            $this->response['error'] = 'sms_time';
            $this->response['time_left'] = $_SESSION['sms_time'] + $this->delay - time();
        } else {
            if ($phone == '7000 000-0011')
                $rand_code = '0000';
            else
                $rand_code = mt_rand(1000, 9999);

            $sms_message = array(
                'code' => $rand_code,
                'phone' => $phone,
                'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
                'created' => date('Y-m-d H:i:s'),
            );
            $this->response['phone'] = $phone;
            if (!empty($this->is_developer)) {
                $this->response['mode'] = 'developer';
                $this->response['developer_code'] = $rand_code;

                $sms_message['response'] = 'DEVELOPER MODE';
            } else {
                $via_call = $this->request->get('via_call', 'string');

                if ($via_call) {
                    $send_response = $this->sms->send_code_via_call($phone);

                    file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'call.log', date("Y-m-d H:i:s") . ' - ' . $send_response . "\n", FILE_APPEND | LOCK_EX);
                    preg_match('/.+ (CODE - (?<code>\d+))/ui', $send_response, $match);
                    if (isset($match['code'])) {
                        $code = substr($match['code'], -4);
                        $sms_message['code'] = $code;
                    }

                    $this->delay = 60;

                    $this->response['response'] = $send_response;
                    $sms_message['response'] = $send_response;
                } else {
                    $send_response = $this->sms->send($phone, "$rand_code - код подтверждения https://barents-finans.ru/lk/");
                    $this->response['response'] = $send_response;
                    $sms_message['response'] = $send_response;

                    $this->response['mode'] = 'standart';
                }
            }

            $this->sms->add_message($sms_message);

            $_SESSION['sms_time'] = time();

            $this->response['success'] = true;
            if (empty($_SESSION['sms_time']))
                $this->response['time_left'] = 0;
            else
                $this->response['time_left'] = ($_SESSION['sms_time'] + $this->delay) - time();
        }
    }

    private function check_action($phone, $code)
    {
        if ($db_code = $this->sms->get_code($phone)) {
            $this->response['success'] = intval($db_code == $code);

        } else {
            $this->response['success'] = 0;
        }
    }

    private function output()
    {
        header("Content-type: application/json; charset=UTF-8");
        header("Cache-Control: must-revalidate");
        header("Pragma: no-cache");
        header("Expires: -1");

        echo json_encode($this->response);
    }
}

$sms_code = new SmsCode();
$sms_code->run();