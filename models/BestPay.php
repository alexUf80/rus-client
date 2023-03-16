<?php

class BestPay extends Core
{

    private $url = '';
    private $currency_code = 643;

    private $fee = 0.05;

    private $sectors = array();

    private $passwords = array();

    public function __construct()
    {
        parent::__construct();

        $this->sectors =
            [
                'PAY_CREDIT' => $this->config->p2pSector,
                'RECURRENT' => $this->config->ecomSector,
                'ADD_CARD' => $this->config->tokenSector,
                'PAYMENT' => $this->config->paySector,
            ];

        $this->passwords =
            [
                $this->config->p2pSector => $this->config->p2pSectorPassword,
                $this->config->ecomSector => $this->config->ecomSectorPassword,
                $this->config->tokenSector => $this->config->tokenPassword,
                $this->config->paySector => $this->config->payPassword,
            ];

        $this->url = $this->config->b2phref;
    }

    public function get_sectors()
    {
        return $this->sectors;
    }

    public function get_sector($type)
    {
        return isset($this->sectors[$type]) ? $this->sectors[$type] : null;
    }

    /**
     * Best2pay::get_payment_link()
     *
     * Метод возвращает ссылку для оплаты любой картой
     *
     * @param int $amount - Сумма платежа в копейках
     * @param string $contract_id - Номер договора
     * @return string
     */
    public function get_payment_link($amount, $contract_id, $prolongation = 0, $card_id = 0, $sms = '')
    {

        $fee = round(max(1, floatval($amount * $this->fee)));

        if (!($contract = $this->contracts->get_contract($contract_id)))
            return false;

        if (!($user = $this->users->get_user((int)$contract->user_id)))
            return false;

        /*

        if ($prolongation == 1) {
            $multipaymentData =
                [
                    'amount' => $this->settings->prolongation_amount,
                    'fee' => 0,
                    'currency' => $this->currency_code,
                    'reference' => $contract->id,
                    'description' => 'Страхование от несчастного случая'
                ];
        }

        */

        $sector = $this->sectors['PAYMENT'];
        $password = $this->passwords[$sector];

        $description = 'Оплата по договору ' . $contract->number;

        if ($contract->status == 11)
            $url = $this->config->front_url . '/best2pay_callback/paymentRestruct?payment_id=' . $contract->payment_id;
        else
            $url = $this->config->front_url . '/best2pay_callback/payment';

        // регистрируем оплату
        $data = array(
            'sector' => $sector,
            'amount' => $amount,
            'currency' => $this->currency_code,
            'reference' => $contract->id,
            'description' => $description,
            'mode' => 1,
            'fee' => $fee,
            'url' => $url,
            'phone' => $user->phone_mobile,
            'fio' => $user->lastname . ' ' . $user->firstname . ' ' . $user->patronymic,
            'contract' => $contract->number,
//            'get_token' => 1,
        );

        if (isset($multipaymentData))
            $data['multipaymentData'] = json_encode($multipaymentData);

        $data['signature'] = $this->get_signature(array(
            $data['sector'],
            $data['amount'],
//            $data['fee'],
            $data['currency'],
            $password
        ));

        $b2p_order_id = $this->send('Register', $data);
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($b2p_order_id);echo '</pre><hr />';


        $transaction_id = $this->transactions->add_transaction(array(
            'user_id' => $contract->user_id,
            'amount' => $amount,
            'sector' => $sector,
            'register_id' => $b2p_order_id,
            'reference' => $contract->id,
            'description' => $description,
            'created' => date('Y-m-d H:i:s'),
            'prolongation' => $prolongation,
            'commision_summ' => $fee / 100,
            'sms' => $sms,
            'body' => serialize($data),
        ));
        // получаем длинную ссылку на оплату
        $data = array(
            'sector' => $sector,
            'id' => $b2p_order_id,

        );
        if (!empty($card_id)) {
            $card = $this->cards->get_card((int)$card_id);
            $data['token'] = $card->token;
//            $data['pan_token'] = $card->pan;
            $data['action'] = 'pay';
        }
        //echo __FILE__ . ' ' . __LINE__ . '<br /><pre>';var_dump($data, $card);echo '</pre><hr />';
        $data['signature'] = $this->get_signature(array($sector, $b2p_order_id, $password));

        $link = $this->url . 'webapi/Purchase?' . http_build_query($data);

        return $link;
    }

    /**
     * Best2pay::add_card()
     *
     * Метод возврашает ссылку для привязки карты
     *
     * @param integer $user_id
     * @param integer $sector
     * @return string $link
     */
    public function add_card($user_id, $sector = 2516)
    {
        $sector = $this->sectors['ADD_CARD'];
//        $password = $this->settings->apikeys['best2pay'][$sector];
        $password = $this->passwords[$sector];

        $amount = 100;
        $description = 'Привязка карты'; // описание операции

        if (!($user = $this->users->get_user((int)$user_id)))
            return false;

        $user_address = $user->Regstreet_shorttype . ' ' . $user->Regstreet . ', д.' . $user->Reghousing;
        if (!empty($user->Regbuilding))
            $user_address .= ', стр.' . $user->Regbuilding;
        if (!empty($user->Regroom))
            $user_address .= ', кв.' . $user->Regroom;

        $user_city = $user->Regregion_shorttype . ' ' . $user->Regregion . ' ' . $user->Regcity_shorttype . ' ' . $user->Regcity;

        // регистрируем оплату
        $data = array(
            'sector' => $sector,
            'amount' => $amount,
            'currency' => $this->currency_code,
            'reference' => $user_id,
            'client_ref' => $user_id,
            'description' => $description,
//            'address' => $user_address,
//            'city' => $user_city,
//            'phone' => $user->phone_mobile,
//            'email' => $user->email,
            'first_name' => $user->firstname,
            'last_name' => $user->lastname,
            'patronymic' => $user->patronymic,
            'url' => $this->config->front_url . '/best2pay_callback/add_card',
            'recurring_period' => 0,
            'error_number' => 3,
            'continuing_recurring' => true,
//            'mode' => 1
        );
        $data['signature'] = $this->get_signature(array($data['sector'], $data['amount'], $data['currency'], $password));

        $b2p_order = $this->send('Register', $data);
        echo __FILE__ . ' ' . __LINE__ . '<br /><pre>';
        var_dump($b2p_order, $sector);
        echo '</pre><hr />';
        $xml = simplexml_load_string($b2p_order);
        $b2p_order_id = (string)$xml->id;

        $transaction_id = $this->transactions->add_transaction(array(
            'user_id' => $user_id,
            'amount' => $amount,
            'sector' => $sector,
            'register_id' => $b2p_order_id,
            'reference' => $user_id,
            'description' => $description,
            'created' => date('Y-m-d H:i:s'),
        ));

        // получаем ссылку на оплату 10руб для привязки карты
        $data = array(
            'sector' => $sector,
            'id' => $b2p_order_id,
            'get_token' => 1,
        );
        $data['signature'] = $this->get_signature(array($sector, $b2p_order_id, $password));

        $link = $this->url . 'webapi/Purchase?' . http_build_query($data);
//echo __FILE__.' '.__LINE__.'<br /><pre>';echo(htmlspecialchars($b2p_order));echo '</pre><hr />';

        return $link;

    }

    /**
     * Best2pay::pay_contract()
     * Переводит сумму займа на карту клиенту
     * @param integer $contract_id
     * @return string - статус перевода COMPLETE при успехе или пустую строку
     */
    public function pay_contract($contract_id)
    {
        $sector = $this->sectors['PAY_CREDIT'];
//        $password = $this->settings->apikeys['best2pay'][$sector];
        $password = $this->passwords[$sector];

        if (!($contract = $this->contracts->get_contract($contract_id)))
            return false;

        if (!($user = $this->users->get_user((int)$contract->user_id)))
            return false;

        if (!($card = $this->cards->get_card((int)$contract->card_id)))
            return false;

        $fio = "$user->lastname $user->firstname $user->patronymic";

        $data = array(
            'sector' => $sector,
            'amount' => $contract->amount * 100,
            'currency' => $this->currency_code,
            'pan' => $card->pan,
            'reference' => $contract->number,
            'token' => $card->token,
            'fio' => $fio,
            'last_name' => $user->lastname,
            'first_name' => $user->firstname,
            'patronymic' => $user->patronymic,
            'email' => $user->email,
            'phone' => $user->phone_mobile
        );

        $data['signature'] = $this->get_signature(array(
            $data['sector'],
            $data['amount'],
            $data['currency'],
            $data['pan'],
            $data['token'],
            $password
        ));

        $p2pcredit = array(
            'contract_id' => $contract->id,
            'user_id' => $user->id,
            'date' => date('Y-m-d H:i:s'),
            'body' => $data
        );
        $p2pcredit_id = $this->add_p2pcredit($p2pcredit);

        $response = $this->send('P2PCredit', $data, 'gateweb');

        $xml = simplexml_load_string($response);
        $status = (string)$xml->order_state;

        $this->update_p2pcredit($p2pcredit_id, array('response' => $response, 'status' => $status));

//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump(htmlspecialchars($response));echo '</pre><hr />';

        return $status;
    }


    public function recurrent_pay($card_id, $amount, $description, $contract_id = null, $prolongation = 0)
    {
        $sector = $this->sectors['RECURRENT'];
//        $password = $this->settings->apikeys['best2pay'][$sector];
        $password = $this->passwords[$sector];

        $fee = max($this->min_fee, floatval($amount * $this->fee));

        if (!($card = $this->cards->get_card($card_id)))
            return false;

        if (!($user = $this->users->get_user((int)$card->user_id)))
            return false;

        $data = array(
            'sector' => $sector,
            'id' => $card->register_id,
            'amount' => $amount,
            'currency' => $this->currency_code,
            'fee' => $fee
        );
        $data['signature'] = $this->get_signature(array(
            $data['sector'],
            $data['id'],
            $data['amount'],
            $data['fee'],
            $data['currency'],
            $password
        ));

        $transaction_id = $this->transactions->add_transaction(array(
            'user_id' => $user->id,
            'amount' => $amount,
            'sector' => $sector,
            'register_id' => $card->register_id,
            'reference' => $user->id,
            'description' => $description,
            'created' => date('Y-m-d H:i:s'),
            'prolongation' => $prolongation,
        ));

        $recurring = $this->send('Recurring', $data);
        $xml = simplexml_load_string($recurring);

        $status = (string)$xml->state;

        $this->transactions->update_transaction($transaction_id, array('callback_response' => $recurring));

        if ($status == 'APPROVED') {

            $contract = $this->contracts->get_contract($contract_id);

            $payment_amount = $amount / 100;

            $this->operations->add_operation(array(
                'contract_id' => $contract->id,
                'user_id' => $contract->user_id,
                'order_id' => $contract->order_id,
                'type' => 'RECURRENT',
                'amount' => $payment_amount,
                'created' => date('Y-m-d H:i:s'),
            ));

            if (!empty($prolongation)) {
                if (!empty($contract->prolongation)) {
                    //TODO: делаем страховку
                    $payment_amount = $payment_amount - $this->settings->prolongation_amount;
                }

                // списываем долг
                if ($contract->loan_percents_summ > $payment_amount) {
                    $new_loan_percents_summ = $contract->loan_percents_summ - $payment_amount;
                    $new_loan_body_summ = $contract->loan_body_summ;
                } else {
                    $new_loan_percents_summ = 0;
                    $new_loan_body_summ = ($contract->loan_body_summ + $contract->loan_percents_summ) - $payment_amount;
                }

                $new_return_date = date('Y-m-d H:i:s', time() + 86400 * $this->settings->prolongation_period);

                $this->contracts->update_contract($contract->id, array(
                    'loan_percents_summ' => $new_loan_percents_summ,
                    'loan_body_summ' => $new_loan_body_summ,
                    'return_date' => $new_return_date,
                    'prolongation' => $contract->prolongation + 1,
                ));

            } else {

                // списываем долг
                if ($contract->loan_percents_summ > $payment_amount) {
                    $new_loan_percents_summ = $contract->loan_percents_summ - $payment_amount;
                    $new_loan_body_summ = $contract->loan_body_summ;
                } else {
                    $new_loan_percents_summ = 0;
                    $new_loan_body_summ = ($contract->loan_body_summ + $contract->loan_percents_summ) - $payment_amount;
                }

                $this->contracts->update_contract($contract->id, array(
                    'loan_percents_summ' => $new_loan_percents_summ,
                    'loan_body_summ' => $new_loan_body_summ
                ));
            }

            // закрываем кредит
            if ($new_loan_body_summ <= 0) {
                $this->contracts->update_contract($contract->id, array(
                    'status' => 3,
                ));

                $this->orders->update_order($contract->order_id, array(
                    'status' => 7
                ));
            }


            return true;
//echo __FILE__.' '.__LINE__.'<br /><pre>';echo(htmlspecialchars($recurring));echo $contract_id.'</pre><hr />';exit;

        } else {
            return false;
        }

    }


    public function recurrent($card_id, $amount, $description)
    {
        $sector = $this->sectors['RECURRENT'];
//        $password = $this->settings->apikeys['best2pay'][$sector];
        $password = $this->passwords[$sector];

//        $fee = max($this->min_fee, floatval($amount * $this->fee));

        if (!($card = $this->cards->get_card($card_id)))
            return false;

        if (!($user = $this->users->get_user((int)$card->user_id)))
            return false;

        $data = array(
            'sector' => $sector,
            'id' => $card->register_id,
            'amount' => $amount,
            'currency' => $this->currency_code,
//            'fee' => $fee
        );
        $data['signature'] = $this->get_signature(array(
            $data['sector'],
            $data['id'],
            $data['amount'],
//            $data['fee'],
            $data['currency'],
            $password
        ));

        $recurring = $this->send('Recurring', $data);
        $xml = simplexml_load_string($recurring);
        $status = (string)$xml->state;

        $transaction_id = $this->transactions->add_transaction(array(
            'user_id' => $user->id,
            'amount' => $amount,
            'sector' => $sector,
            'register_id' => $card->register_id,
            'reference' => $user->id,
            'description' => $description,
            'created' => date('Y-m-d H:i:s'),
            'callback_response' => $recurring
        ));

        return $xml;


    }

    public function purchase_by_token($card_id, $amount, $description, $contract_id)
    {
        $sector = $this->sectors['RECURRENT'];
        $password = $this->passwords[$sector];

        if (!($card = $this->cards->get_card($card_id)))
            return false;
        if (!($user = $this->users->get_user((int)$card->user_id)))
            return false;

        $fee = round(max(1, floatval($amount * $this->fee)));


        // регистрируем оплату
        $data = array(
            'sector' => $sector,
            'amount' => $amount,
            'currency' => $this->currency_code,
            'reference' => $user->id,
            'description' => $description,
            'phone' => $user->phone_mobile,
            'email' => $user->email,
            'first_name' => $user->firstname,
            'last_name' => $user->lastname,
            'patronymic' => $user->patronymic,
        );
        $data['signature'] = $this->get_signature(array($data['sector'], $data['amount'], $data['currency'], $password));

        $b2p_order = $this->send('Register', $data);
        $xml = simplexml_load_string($b2p_order);
        $b2p_order_id = (string)$xml->id;
        $data = array(
            'sector' => $sector,
            'id' => $b2p_order_id,
            'token' => $card->token,
            'fee' => $fee
        );
        $data['signature'] = $this->get_signature(array(
            $data['sector'],
            $data['id'],
            $data['token'],
//            $data['fee'],
            $password
        ));

        $recurring = $this->send('PurchaseByToken', $data);
        $xml = simplexml_load_string($recurring);
        $status = (string)$xml->state;
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($recurring );echo '</pre><hr />';

        $transaction_id = $this->transactions->add_transaction(array(
            'user_id' => $user->id,
            'amount' => $amount,
            'sector' => $sector,
            'body' => json_encode($data),
            'register_id' => $b2p_order_id,
            'reference' => $user->id,
            'description' => $description,
            'created' => date('Y-m-d H:i:s'),
            'callback_response' => $recurring
        ));

        if ($status == 'APPROVED') {

            $contract = $this->contracts->get_contract($contract_id);

            $payment_amount = $amount / 100;

            $this->operations->add_operation(array(
                'contract_id' => $contract->id,
                'user_id' => $contract->user_id,
                'order_id' => $contract->order_id,
                'type' => 'RECURRENT',
                'amount' => $payment_amount,
                'created' => date('Y-m-d H:i:s'),
            ));

            // списываем долг
            if ($contract->loan_percents_summ > $payment_amount) {
                $new_loan_percents_summ = $contract->loan_percents_summ - $payment_amount;
                $new_loan_body_summ = $contract->loan_body_summ;
            } else {
                $new_loan_percents_summ = 0;
                $new_loan_body_summ = ($contract->loan_body_summ + $contract->loan_percents_summ) - $payment_amount;
            }

            $this->contracts->update_contract($contract->id, array(
                'loan_percents_summ' => $new_loan_percents_summ,
                'loan_body_summ' => $new_loan_body_summ
            ));

            // закрываем кредит
            if ($new_loan_body_summ <= 0) {
                $this->contracts->update_contract($contract->id, array(
                    'status' => 3,
                ));

                $this->orders->update_order($contract->order_id, array(
                    'status' => 7
                ));
            }


            return true;

        } else {
            return false;
        }
    }

    public function get_operation_info($sector, $register_id, $operation_id)
    {
//        $password = $this->settings->apikeys['best2pay'][$sector];
        $password = $this->passwords[$sector];

        $data = array(
            'sector' => $sector,
            'id' => $register_id,
            'operation' => $operation_id,
            'get_token' => 1
        );
        $data['signature'] = $this->get_signature(array($sector, $register_id, $operation_id, $password));

        $info = $this->send('Operation', $data);

        return $info;
    }

    public function get_register_info($sector, $register_id, $get_token = 1)
    {
//        $password = $this->settings->apikeys['best2pay'][$sector];
        $password = $this->passwords[$sector];

        $data = array(
            'sector' => $sector,
            'id' => $register_id,
            'mode' => 0,
            'get_token' => $get_token
        );
        $data['signature'] = $this->get_signature(array($sector, $register_id, $password));

        $info = $this->send('Order', $data);

        return $info;
    }


    private function send($method, $data, $type = 'webapi')
    {
        $string_data = http_build_query($data);
        $context = stream_context_create(array(
            'http' => array(
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: " . strlen($string_data) . "\r\n",
                'method' => 'POST',
                'content' => $string_data
            )
        ));
        $b2p = file_get_contents($this->url . $type . '/' . $method, false, $context);
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($this->url.$type.'/'.$method, $data);echo '</pre><hr />';

        return $b2p;
    }

    private function get_signature($data)
    {
        $str = '';
        foreach ($data as $item)
            $str .= $item;

        $md5 = md5($str);
        $signature = base64_encode($md5);

        return $signature;
    }

    public function get_reason_code_description($code, $full = false)
    {
        $descriptions = array(
            2 => 'Платёж отклонён. Возможные причины: недостаточно средств на счёте, были указаны неверные реквизиты карты, по Вашей карте запрещены расчёты через Интернет. Пожалуйста, попробуйте выполнить платёж повторно или обратитесь в Банк, выпустивший Вашу карту. ',
            3 => 'Платёж отклонён. Пожалуйста, обратитесь в Банк, выпустивший Вашу карту. ',
            4 => 'Платёж отклонён. Пожалуйста, обратитесь в Банк, выпустивший Вашу карту. ',
            5 => 'Операция недопустима для Эмитента. Платёж отклонён. Пожалуйста, обратитесь в Банк, выпустивший Вашу карту. ',
            6 => 'Платёж отклонён. Возможные причины: недостаточно средств на счёте, были указаны неверные реквизиты карты, по Вашей карте запрещены расчёты через Интернет. Пожалуйста, попробуйте выполнить платёж повторно или обратитесь в Банк, выпустивший Вашу карту. ',
            7 => 'Платёж отклонён. Пожалуйста, обратитесь в Контактный центр. ',
            8 => 'Платёж отклонён. Пожалуйста, обратитесь в Контактный центр. ',
            9 => 'Платёж отклонён. Пожалуйста, обратитесь в Контактный центр. ',
            10 => 'Платёж отклонён. Пожалуйста, обратитесь в Контактный центр. ',
            11 => 'Платёж отклонён. Пожалуйста, обратитесь в Контактный центр. ',
            12 => 'Платёж отклонён. Возможные причины: недостаточно средств на счёте, были указаны неверные реквизиты карты, по Вашей карте запрещены расчёты через Интернет. Пожалуйста, попробуйте выполнить платёж повторно или обратитесь в Банк, выпустивший Вашу карту. ',
            13 => 'Платёж отклонён. Пожалуйста, попробуйте выполнить платёж позднее или обратитесь в Контактный центр. ',
            14 => 'Платёж отклонён. Пожалуйста, обратитесь в платёжную систему, электронными деньгами которой Вы пытаетесь оплатить Заказ. ',
            15 => 'Платёж отклонён. Пожалуйста, обратитесь в Контактный центр. ',
            16 => 'Платёж отклонён. Пожалуйста, обратитесь в Контактный центр. ',
            0 => 'Платёж отклонён. Пожалуйста, попробуйте выполнить платёж позднее или обратитесь в Контактный центр. '
        );

        $full_descriptions = array(
            2 => 'Неверный срок действия Банковской карты. <br />Платёж отклонён. Возможные причины: недостаточно средств на счёте, были указаны неверные реквизиты карты, по Вашей карте запрещены расчёты через Интернет. Пожалуйста, попробуйте выполнить платёж повторно или обратитесь в Банк, выпустивший Вашу карту. ',
            3 => 'Неверный статус Банковской карты на стороне Эмитента. <br />Платёж отклонён. Пожалуйста, обратитесь в Банк, выпустивший Вашу карту. ',
            4 => 'Операция отклонена Эмитентом. <br />Платёж отклонён. Пожалуйста, обратитесь в Банк, выпустивший Вашу карту. ',
            5 => 'Операция недопустима для Эмитента. Платёж отклонён. Пожалуйста, обратитесь в Банк, выпустивший Вашу карту. ',
            6 => 'Недостаточно средств на счёте Банковской карты. <br />Платёж отклонён. Возможные причины: недостаточно средств на счёте, были указаны неверные реквизиты карты, по Вашей карте запрещены расчёты через Интернет. Пожалуйста, попробуйте выполнить платёж повторно или обратитесь в Банк, выпустивший Вашу карту. ',
            7 => 'Превышен установленный для ТСП лимит на сумму операций (дневной, недельный, месячный) или сумма операции выходит за пределы установленных границ. <br />Платёж отклонён. Пожалуйста, обратитесь в Контактный центр. ',
            8 => 'Операция отклонена по причине срабатывания системы предотвращения мошенничества. <br />Платёж отклонён. Пожалуйста, обратитесь в Контактный центр. ',
            9 => 'Заказ уже находится в процессе оплаты. Операция, возможно, задублировалась. <br />Платёж отклонён. Пожалуйста, обратитесь в Контактный центр. ',
            10 => 'Системная ошибка. <br />Платёж отклонён. Пожалуйста, обратитесь в Контактный центр. ',
            11 => 'Ошибка 3DS аутентификации. <br />Платёж отклонён. Пожалуйста, обратитесь в Контактный центр. ',
            12 => 'Указано неверное значение секретного кода карты. <br />Платёж отклонён. Возможные причины: недостаточно средств на счёте, были указаны неверные реквизиты карты, по Вашей карте запрещены расчёты через Интернет. Пожалуйста, попробуйте выполнить платёж повторно или обратитесь в Банк, выпустивший Вашу карту. ',
            13 => 'Операция отклонена по причине недоступности Эмитента и/или Банка- эквайрера. <br />Платёж отклонён. Пожалуйста, попробуйте выполнить платёж позднее или обратитесь в Контактный центр. ',
            14 => 'Операция отклонена оператором электронных денег. <br />Платёж отклонён. Пожалуйста, обратитесь в платёжную систему, электронными деньгами которой Вы пытаетесь оплатить Заказ. ',
            15 => 'BIN платёжной карты присутствует в черных списках. <br />Платёж отклонён. Пожалуйста, обратитесь в Контактный центр. ',
            16 => 'BIN 2 платёжной карты присутствует в черных списках. <br />Платёж отклонён. Пожалуйста, обратитесь в Контактный центр. ',
            0 => 'Операция отклонена по другим причинам. Требуется уточнение у ПЦ.<br />Платёж отклонён. Пожалуйста, попробуйте выполнить платёж позднее или обратитесь в Контактный центр. '
        );

        if (empty($full))
            return isset($descriptions[$code]) ? $descriptions[$code] : '';
        else
            return isset($full_descriptions[$code]) ? $full_descriptions[$code] : '';
    }


    public function add_card_enroll($user_id)
    {
        $sector = $this->sectors['ADD_CARD'];
        $password = $this->passwords[$sector];

        $amount = 100; // сумма для списания
        $description = 'Привязка карты'; // описание операции
// 812763
        // регистрируем оплату
        $data = array(
            'sector' => $sector,
            'amount' => $amount,
            'currency' => $this->currency_code,
            'reference' => $user_id,
            'description' => $description,
            'url' => $this->config->root_url . '/best2pay_callback/add_card',
//            'mode' => 1
        );
        $data['signature'] = $this->get_signature(array($data['sector'], $data['amount'], $data['currency'], $password));

        $b2p_order = $this->send('Register', $data);

        $xml = simplexml_load_string($b2p_order);
        $b2p_order_id = (string)$xml->id;

        $transaction_id = $this->transactions->add_transaction(array(
            'user_id' => $user_id,
            'amount' => $amount,
            'sector' => $sector,
            'register_id' => $b2p_order_id,
            'reference' => $user_id,
            'description' => $description,
            'created' => date('Y-m-d H:i:s'),
        ));
//exit;
        // получаем ссылку на привязку карты
        $data = array(
            'sector' => $sector,
            'id' => $b2p_order_id
        );
        $data['signature'] = $this->get_signature(array($sector, $b2p_order_id, $password));

        $link = $this->url . 'webapi/CardEnroll?' . http_build_query($data);

        return $link;
    }


    public function get_p2pcredit($id)
    {
        $query = $this->db->placehold("
            SELECT * 
            FROM __p2pcredits
            WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
        if ($result = $this->db->result()) {
            $result->body = unserialize($result->body);
            $result->response = unserialize($result->response);
        }

        return $result;
    }

    public function get_p2pcredits($filter = array())
    {
        $id_filter = '';
        $keyword_filter = '';
        $limit = 1000;
        $page = 1;

        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND id IN (?@)", array_map('intval', (array)$filter['id']));

        if (isset($filter['keyword'])) {
            $keywords = explode(' ', $filter['keyword']);
            foreach ($keywords as $keyword)
                $keyword_filter .= $this->db->placehold('AND (name LIKE "%' . $this->db->escape(trim($keyword)) . '%" )');
        }

        if (isset($filter['limit']))
            $limit = max(1, intval($filter['limit']));

        if (isset($filter['page']))
            $page = max(1, intval($filter['page']));

        $sql_limit = $this->db->placehold(' LIMIT ?, ? ', ($page - 1) * $limit, $limit);

        $query = $this->db->placehold("
            SELECT * 
            FROM __p2pcredits
            WHERE 1
                $id_filter
 	           $keyword_filter
            ORDER BY id DESC 
            $sql_limit
        ");
        $this->db->query($query);
        if ($results = $this->db->results()) {
            foreach ($results as $result) {
                $result->body = unserialize($result->body);
                $result->response = unserialize($result->response);
            }
        }

        return $results;
    }

    public function count_p2pcredits($filter = array())
    {
        $id_filter = '';
        $keyword_filter = '';

        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND id IN (?@)", array_map('intval', (array)$filter['id']));

        if (isset($filter['keyword'])) {
            $keywords = explode(' ', $filter['keyword']);
            foreach ($keywords as $keyword)
                $keyword_filter .= $this->db->placehold('AND (name LIKE "%' . $this->db->escape(trim($keyword)) . '%" )');
        }

        $query = $this->db->placehold("
            SELECT COUNT(id) AS count
            FROM __p2pcredits
            WHERE 1
                $id_filter
                $keyword_filter
        ");
        $this->db->query($query);
        $count = $this->db->result('count');

        return $count;
    }

    public function add_p2pcredit($p2pcredit)
    {
        $p2pcredit = (array)$p2pcredit;

        if (isset($p2pcredit['body']))
            $p2pcredit['body'] = serialize($p2pcredit['body']);
        if (isset($p2pcredit['response']))
            $p2pcredit['response'] = serialize($p2pcredit['response']);

        $query = $this->db->placehold("
            INSERT INTO __p2pcredits SET ?%
        ", $p2pcredit);
        $this->db->query($query);
        $id = $this->db->insert_id();
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($query);echo '</pre><hr />';
        return $id;
    }

    public function update_p2pcredit($id, $p2pcredit)
    {
        $p2pcredit = (array)$p2pcredit;

        if (isset($p2pcredit['body']))
            $p2pcredit['body'] = serialize($p2pcredit['body']);
        if (isset($p2pcredit['response']))
            $p2pcredit['response'] = serialize($p2pcredit['response']);

        $query = $this->db->placehold("
            UPDATE __p2pcredits SET ?% WHERE id = ?
        ", $p2pcredit, (int)$id);
        $this->db->query($query);

        return $id;
    }

    public function delete_p2pcredit($id)
    {
        $query = $this->db->placehold("
            DELETE FROM __p2pcredits WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
    }

}