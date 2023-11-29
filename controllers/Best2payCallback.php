<?php

class Best2PayCallback extends Controller
{
    private $loan_doctor_steps = array(
        1 => 1000,
        2 => 2000,
        3 => 3000,
        4 => 4000,
        5 => 5000,
        6 => 6000,
        7 => 7000,
        8 => 8000,
        9 => 9000,
    );

    private $loan_doctor_payment = array(
        1 => 2000,
        2 => 3000,
        3 => 4000,
        4 => 5000,
        5 => 6000,
        6 => 7000,
        7 => 8000,
        8 => 9000,
        9 => 10000,
    );

    public function fetch()
    {
        switch ($this->request->get('action', 'string')):

            case 'add_card':
                $this->add_card_action();
                break;

            case 'payment':
                $this->payment_action();
                break;

            case 'recurrent':
                $this->recurrent();
                break;

            case 'paymentRestruct':
                $this->paymentRestruct();
                break;

            case 'paymentKD':
                $this->paymentKD_action();
                break;

            default:
                $meta_title = 'Ошибка';
                $this->design->assign('error', 'Ошибка');

        endswitch;

        return $this->design->fetch('best2pay_callback.tpl');
    }

    public function payment_action()
    {
        $register_id = $this->request->get('id', 'integer');
        $operation = $this->request->get('operation', 'integer');
        $error = $this->request->get('error', 'integer');
        $code = $this->request->get('code', 'integer');

        if (!empty($register_id)) {
            if ($transaction = $this->transactions->get_register_id_transaction($register_id)) {
                if ($transaction_operation = $this->operations->get_transaction_operation($transaction->id)) {
                    $this->design->assign('error', 'Оплата уже принята.');
                } else {

                    if (empty($operation)) {
                        $register_info = $this->BestPay->get_register_info($transaction->sector, $register_id);
                        $xml = simplexml_load_string($register_info);

                        foreach ($xml->operations as $xml_operation)
                            if ($xml_operation->operation->state == 'APPROVED')
                                $operation = (string)$xml_operation->operation->id;
                    }


                    if (!empty($operation)) {
                        $operation_info = $this->BestPay->get_operation_info($transaction->sector, $register_id, $operation);
                        $xml = simplexml_load_string($operation_info);
                        $reason_code = (string)$xml->reason_code;
                        $payment_amount = strval($xml->amount) / 100;
                        $operation_date = date('Y-m-d H:i:s', strtotime(str_replace('.', '-', (string)$xml->date)));

                        if ($reason_code == 1) {


                            if (!($contract = $this->contracts->get_contract($transaction->reference)))
                                $contract = $this->contracts->get_number_contract($transaction->reference);

                            $rest_amount = $payment_amount;
                            $rest_amount_kd = $payment_amount;


                            $contract_order = $this->orders->get_order((int)$contract->order_id);

                            $user = $this->users->get_user($contract_order->user_id);

                            $regaddress = $this->Addresses->get_address($user->regaddress_id);
                            $regaddress_full = $regaddress->adressfull;

                            $passport_series = substr(str_replace(array(' ', '-'), '', $contract_order->passport_serial), 0, 4);
                            $passport_number = substr(str_replace(array(' ', '-'), '', $contract_order->passport_serial), 4, 6);
                            $subdivision_code = $contract_order->subdivision_code;
                            $passport_issued = $contract_order->passport_issued;
                            $passport_date = $contract_order->passport_date;


                            $document_params = array(
                                'lastname' => $contract_order->lastname,
                                'firstname' => $contract_order->firstname,
                                'patronymic' => $contract_order->patronymic,
                                'birth' => $contract_order->birth,
                                'phone' => $contract_order->phone_mobile,
                                'regaddress_full' => $regaddress_full,
                                'passport_series' => $passport_series,
                                'passport_number' => $passport_number,
                                'passport_serial' => $contract_order->passport_serial,
                                'subdivision_code' => $subdivision_code,
                                'passport_issued' => $passport_issued,
                                'passport_date' => $passport_date,
                                'asp' => $transaction->sms,
                                'created' => date('Y-m-d H:i:s'),
                                'base_percent' => $contract->base_percent,
                                'amount' => $contract->amount,
                                'number' => $contract->number,
                                'order_created' => $contract_order->date,

                            );

                            if (!empty($transaction->prolongation) && $payment_amount >= $contract->loan_percents_summ) {

                                $new_return_date = date('Y-m-d H:i:s', time() + 86400 * $this->settings->prolongation_period);

                                $document_params['return_date'] = $new_return_date;
                                $document_params['return_date_day'] = date('d', strtotime($new_return_date));
                                $document_params['return_date_month'] = date('m', strtotime($new_return_date));
                                $document_params['return_date_year'] = date('Y', strtotime($new_return_date));
                                $document_params['period'] = $this->settings->prolongation_period;

                                $docs = 1;

                                if (!empty($contract->service_insurance)) 
                                {
                                    if ($payment_amount >= $contract->loan_percents_summ + $this->settings->prolongation_amount + $contract->loan_peni_summ) {
                                        $operation_id = $this->operations->add_operation(array(
                                            'contract_id' => $contract->id,
                                            'user_id' => $contract->user_id,
                                            'order_id' => $contract->order_id,
                                            'transaction_id' => $transaction->id,
                                            'type' => 'INSURANCE_BC',
                                            'amount' => $this->settings->prolongation_amount,
                                            'created' => date('Y-m-d H:i:s'),
                                            'sent_status' => 0,
                                        ));

                                        $insurance_id = $this->insurances->add_insurance(array(
                                            'number' => '',
                                            'amount' => $this->settings->prolongation_amount,
                                            'user_id' => $contract->user_id,
                                            'order_id' => $contract->order_id,
                                            'create_date' => date('Y-m-d H:i:s'),
                                            'start_date' => date('Y-m-d 00:00:00', time() + (1 * 86400)),
                                            'end_date' => date('Y-m-d 23:59:59', time() + (31 * 86400)),
                                            'operation_id' => $operation_id,
                                            'protection' => 0,
                                        ));
                                        $this->transactions->update_transaction($transaction->id, array('insurance_id' => $insurance_id));

                                        $rest_amount = $rest_amount - $this->settings->prolongation_amount;

                                        $payment_amount -= $this->settings->prolongation_amount;

                                        $docs = 2;
                                    }
                                }

                                // продлеваем контракт
                                $this->contracts->update_contract($contract->id, array(
                                    'return_date' => $new_return_date,
                                    'prolongation' => $contract->prolongation + 1,
                                    'status' => 2
                                ));

                                //Создаем пролонгацию и записываем в нее айди страховки
                                $this->prolongations->add_prolongation(array(
                                    'contract_id' => $contract->id,
                                    'user_id' => $contract->user_id,
                                    'insurance_id' => empty($insurance_id) ? '' : $insurance_id,
                                    'created' => date('Y-m-d H:i:s'),
                                    'accept_code' => $transaction->sms,
                                    'transaction_id' => $transaction->id,
                                ));

                            }

                        } else {
                            $this->transactions->update_transaction($transaction->id, array('prolongation' => 0));
                        }


                        // списываем проценты
                        $contract_loan_percents_summ = (float)$contract->loan_percents_summ;
                        if ($contract->loan_percents_summ > 0) {
                            if ($rest_amount >= $contract->loan_percents_summ) {
                                $contract_loan_percents_summ = 0;
                                $rest_amount = $rest_amount - $contract->loan_percents_summ;
                                $transaction_loan_percents_summ = $contract->loan_percents_summ;
                            } else {
                                $contract_loan_percents_summ = $contract->loan_percents_summ - $rest_amount;
                                $transaction_loan_percents_summ = $rest_amount;
                                $rest_amount = 0;
                            }
                        }

                        // списываем основной долг
                        $contract_loan_body_summ = (float)$contract->loan_body_summ;
                        if ($contract->loan_body_summ > 0) {
                            if ($rest_amount >= $contract->loan_body_summ) {
                                $contract_loan_body_summ = 0;
                                $rest_amount = $rest_amount - $contract->loan_body_summ;
                                $transaction_loan_body_summ = $contract->loan_body_summ;
                            } else {
                                $contract_loan_body_summ = $contract->loan_body_summ - $rest_amount;
                                $transaction_loan_body_summ = $rest_amount;
                                $rest_amount = 0;
                            }
                        }

                        $kd = OperationsORM::query()
                            ->where('order_id', '=', $contract->order_id)
                            ->where('type', '=', 'DOCTOR')
                            ->first();

                        if (!is_null($kd->amount) && ($contract->return_date > date('Y-m-d H:i:s'))
                            && $rest_amount_kd >= $contract->loan_body_summ){
                            $contract_loan_body_summ = 0;
                            $contract_loan_percents_summ = 0;
                            $contract_loan_peni_summ = 0;

                            $transaction_loan_body_summ = $contract->loan_body_summ;
                            $transaction_loan_percents_summ = 0;
                            $transaction_loan_peni_summ = 0;
                        }

                        if (!empty($contract->collection_status)) {

                            $date1 = new DateTime(date('Y-m-d', strtotime($contract->return_date)));
                            $date2 = new DateTime(date('Y-m-d'));

                            $diff = $date2->diff($date1);
                            $contract->expired_days = $diff->days;

                            $collection_order = array(
                                'transaction_id' => $transaction->id,
                                'manager_id' => $contract->collection_manager_id,
                                'contract_id' => $contract->id,
                                'created' => date('Y-m-d H:i:s'),
                                'body_summ' => empty($transaction_loan_body_summ) ? 0 : $transaction_loan_body_summ,
                                'percents_summ' => empty($transaction_loan_percents_summ) ? 0 : $transaction_loan_percents_summ,
                                'charge_summ' => empty($transaction_loan_charge_summ) ? 0 : $transaction_loan_charge_summ,
                                'peni_summ' => empty($transaction_loan_peni_summ) ? 0 : $transaction_loan_peni_summ,
                                'commision_summ' => $transaction->commision_summ,
                                'closed' => 0,
                                'prolongation' => 0,
                                'collection_status' => $contract->collection_status,
                                'expired_days' => $contract->expired_days,
                            );
                        }

                        if (empty($transaction->prolongation) && $rest_amount != 0 && $contract_loan_body_summ == 0 && $contract_loan_percents_summ == 0)
                        {
                            // списываем пени
                            $contract_loan_peni_summ = (float)$contract->loan_peni_summ;

                            if ($contract->loan_peni_summ > 0) {
                                if ($rest_amount >= $contract->loan_peni_summ) {
                                    $contract_loan_peni_summ = 0;
                                    $rest_amount = $rest_amount - $contract->loan_peni_summ;
                                    $transaction_loan_peni_summ = $contract->loan_peni_summ;
                                } else {
                                    $contract_loan_peni_summ = $contract->loan_peni_summ - $rest_amount;
                                    $transaction_loan_peni_summ = $rest_amount;
                                    $rest_amount = 0;
                                }
                            }

                            $collection_order['peni_summ'] = empty($transaction_loan_peni_summ) ? 0 : $transaction_loan_peni_summ;
                            
                        }
                        
                        $this->contracts->update_contract($contract->id, array(
                            'loan_percents_summ' => $contract_loan_percents_summ,
                            'loan_peni_summ' => isset($contract_loan_peni_summ) ? $contract_loan_peni_summ : $contract->loan_peni_summ,
                            'loan_body_summ' => $contract_loan_body_summ,
                        ));

                        $this->transactions->update_transaction($transaction->id, array(
                            'loan_percents_summ' => empty($transaction_loan_percents_summ) ? 0 : $transaction_loan_percents_summ,
                            'loan_peni_summ' => empty($transaction_loan_peni_summ) ? 0 : $transaction_loan_peni_summ,
                            'loan_body_summ' => empty($transaction_loan_body_summ) ? 0 : $transaction_loan_body_summ,
                        ));

                        if (!empty($transaction->prolongation) && $payment_amount >= $contract->loan_percents_summ) {
                            if (!empty($collection_order))
                                    $collection_order['prolongation'] = 1;

                            $this->contracts->update_contract($contract->id, ['collection_status' => 0, 'collection_manager_id' => 0]);

                            $return_amount = round($contract_loan_body_summ + $contract_loan_body_summ * $contract->base_percent * $this->settings->prolongation_period / 100, 2);
                            $return_amount_percents = round($contract_loan_body_summ * $contract->base_percent * $this->settings->prolongation_period / 100, 2);

                            $document_params['return_amount'] = $return_amount;
                            $document_params['return_amount_percents'] = $return_amount_percents;

                            $document_params['amount'] = $contract_loan_body_summ;

                            // дополнительное соглашение
                            $this->documents->create_document(array(
                                'user_id' => $contract->user_id,
                                'order_id' => $contract->order_id,
                                'contract_id' => $contract->id,
                                'type' => 'DOP_SOGLASHENIE',
                                'params' => json_encode($document_params)
                            ));

                            // заявление на пролонгацию
                            $this->documents->create_document(array(
                                'user_id' => $contract->user_id,
                                'order_id' => $contract->order_id,
                                'contract_id' => $contract->id,
                                'type' => 'ZAYAVLENIE_PROLONGATION',
                                'params' => json_encode($document_params)
                            ));

                            if ($docs == 2) {
                                $document_params['insurance'] = $this->insurances->get_insurance($insurance_id);
                                $this->documents->create_document(array(
                                    'user_id' => $contract->user_id,
                                    'order_id' => $contract->order_id,
                                    'contract_id' => $contract->id,
                                    'type' => 'POLIS_PROLONGATION',
                                    'params' => json_encode($document_params)
                                ));
                            }
                        }

                        // закрываем кредит
                        $contract_loan_percents_summ = round($contract_loan_percents_summ, 2);
                        $contract_loan_body_summ = round($contract_loan_body_summ, 2);

                        $contract_loan_peni_summ = isset($contract_loan_peni_summ) ? $contract_loan_peni_summ : $contract->loan_peni_summ;
                        $contract_loan_peni_summ = round($contract_loan_peni_summ, 2);
                        
                        if ($contract_loan_body_summ <= 0 && $contract_loan_percents_summ <= 0 && $contract_loan_peni_summ == 0) {
                            $this->contracts->update_contract($contract->id, array(
                                'status' => 3,
                                'collection_status' => 0,
                                'close_date' => date('Y-m-d H:i:s'),
                            ));

                            $this->orders->update_order($contract->order_id, array(
                                'status' => 7
                            ));
                            if (!empty($collection_order))
                                    $collection_order['closed'] = 1;
                        }

                        if (!empty($collection_order))
                                $this->collections->add_collection($collection_order);

                        $this->operations->add_operation(array(
                            'contract_id' => $contract->id,
                            'user_id' => $contract->user_id,
                            'order_id' => $contract->order_id,
                            'type' => 'PAY',
                            'amount' => $payment_amount,
                            'created' => $operation_date,
                            'transaction_id' => $transaction->id,
                            'loan_body_summ' => $contract_loan_body_summ,
                            'loan_percents_summ' => $contract_loan_percents_summ,
                            'loan_charge_summ' => 0,
                            'loan_peni_summ' => isset($contract_loan_peni_summ) ? $contract_loan_peni_summ : $contract->loan_peni_summ
                        ));
                        $this->design->assign('success', 'Оплата прошла успешно.');
                    } else {
                        $reason_code_description = $this->BestPay->get_reason_code_description($code);
                        $this->design->assign('reason_code_description', $reason_code_description);

                        $this->design->assign('error', 'При оплате произошла ошибка.');
                    }
                    $this->transactions->update_transaction($transaction->id, array(
                        'operation' => $operation,
                        'callback_response' => $register_info,
                        'reason_code' => $reason_code
                    ));


                    // Снимаем страховку
                    if (!empty($contract->service_insurance)) 
                    {
                        // $insurance_cost = $this->insurances->get_insurance_cost($contract->amount);
                        $insurance_cost = 0;
                        
                        $order = $this->orders->get_order($contract->order_id);

                        if ($order->client_status == 'kd')
                        {
                            $insurance_cost = $this->insurances->get_insurance_cost($contract->amount);
                        }
                        else{
                            $insurance_cost = 1000;
                        }

                        // if ($order->client_status == 'kd' && $insurance_cost > 0)
                        if ($insurance_cost > 0)
                        {
                            $insurance_amount = $insurance_cost * 100;

                            $description = 'Страховой полис';

                            $xml = $this->BestPay->recurring_by_token($contract->card_id, $insurance_amount, $description);
                            $status = (string)$xml->state;

                            if ($status == 'APPROVED') {
                                
                                $transaction = $this->transactions->get_register_id_transaction($xml->order_id);
                                
                                $contract = $this->contracts->get_contract($contract->id);

                                $max_service_value = $this->operations->max_service_number();

                                $operation_id = $this->operations->add_operation(array(
                                    'contract_id' => $contract->id,
                                    'user_id' => $contract->user_id,
                                    'order_id' => $contract->order_id,
                                    'type' => 'INSURANCE',
                                    'amount' => $insurance_cost,
                                    'created' => date('Y-m-d H:i:s'),
                                    'transaction_id' => $transaction->id,
                                    'service_number' => $max_service_value,
                                ));

                                $dt = new DateTime();
                                $dt->add(new DateInterval('P1M'));
                                $end_date = $dt->format('Y-m-d 23:59:59');

                                try{
                                    $contract->insurance = new InsurancesORM();
                                    $contract->insurance->amount = $insurance_cost;
                                    $contract->insurance->user_id = $contract->user_id;
                                    $contract->insurance->order_id = $contract->order_id;
                                    $contract->insurance->start_date = date('Y-m-d 00:00:00', time() + (1 * 86400));
                                    $contract->insurance->end_date = $end_date;
                                    $contract->insurance->operation_id = $operation_id;
                                    $contract->insurance->save();

                                    $contract->insurance->number = InsurancesORM::create_number($contract->insurance->id);

                                    InsurancesORM::where('id', $contract->insurance->id)->update(['number' => $contract->insurance->number]);
                                }catch (Exception $e)
                                {

                                }

                                    $this->contracts->update_contract($contract->id, array(
                                    'insurance_id' => $contract->insurance_id,
                                    // 'loan_body_summ' => $contract->amount + $insurance_cost
                                    'loan_body_summ' => $contract->amount
                                ));

                                //создаем документы для страховки
                                $this->create_document('POLIS', $contract);

                                // //Отправляем чек по страховке
                                // $return = $this->Cloudkassir->send_insurance($operation_id);

                                // if (!empty($return))
                                // {
                                //     $resp = json_decode($return);

                                //     $this->receipts->add_receipt(array(
                                //         'user_id' => $contract->user_id,
                                //         'name' => 'Страхование от несчастных случаев',
                                //         'order_id' => $contract->order_id,
                                //         'contract_id' => $contract->id,
                                //         'insurance_id' => $contract->insurance_id,
                                //         'receipt_url' => (string)$resp->Model->ReceiptLocalUrl,
                                //         'response' => serialize($return),
                                //         'created' => date('Y-m-d H:i:s'),
                                //     ));
                                // }
                            }
                        }
                    }

                }
            }
        } else {
            $this->design->assign('error', 'Ошибка: Транзакция не найдена');
        }


    }

    public function paymentKD_action()
    {
        $register_id = $this->request->get('id', 'integer');
        // $operation = $this->request->get('operation', 'integer');
        $error = $this->request->get('error', 'integer');
        $code = $this->request->get('code', 'integer');

        if (!empty($register_id)) {
            if ($transaction = $this->transactions->get_register_id_transaction($register_id)) {
                if ($transaction_operation = $this->operations->get_transaction_operation($transaction->id)) {
                    $this->design->assign('error', 'Оплата уже принята.');
                } else {

                    if (empty($operation)) {
                        $register_info = $this->BestPay->get_register_info($transaction->sector, $register_id);
                        $xml = simplexml_load_string($register_info);

                        foreach ($xml->operations as $xml_operation)
                            if ($xml_operation->operation->state == 'APPROVED')
                                $operation = (string)$xml_operation->operation->id;
                    }


                    if (!empty($operation)) {

                        $period = 14;
                        $client_status = 'kd';
                        $user = $this->users->get_user($transaction->user_id);
                        $user_cards = $this->cards->get_cards(array('user_id' => $user->id));
                        
                        $card_id = 0;
                        foreach ($user_cards as $user_card) {
                            $card_id = $user_card->id;
                            if ($user_card->base_card = 1)
                                break;
                        }

                        $order = array(
                            'amount' => $this->loan_doctor_steps[($user->loan_doctor + 1)],
                            'period' => $period,
                            'card_id' => $card_id,
                            'date' => date('Y-m-d H:i:s'),
                            'user_id' => $user->id,
                            'status' => 2,
                            'ip' => $_SERVER['REMOTE_ADDR'],
                            'first_loan' => 0,
                            // 'juicescore_session_id' => $juicescore_session_id,
                            // 'local_time' => $local_time,
                            'client_status' => $client_status,
                            // 'accept_sms' => $sms,
                            'accept_date' => date('Y-m-d H:i:s'),
                            'approve_date' => date('Y-m-d H:i:s'),
                        );
    
                        $order['utm_source'] = $_COOKIE['utm_source'];
                        $order['webmaster_id'] = $_COOKIE["wm_id"];
                        $order['click_hash'] = $_COOKIE["clickid"];
    
                        $order['autoretry'] = 1;
    
    
                        $order_id = $this->orders->add_order($order);
    
                        $order = $this->orders->get_order($order_id);
                        $new_contract = array(
    
                            'order_id' => $order_id,
                            'user_id' => $order->user_id,
                            'card_id' => $order->card_id,
                            'type' => 'base',
                            'amount' => $order->amount,
                            'period' => $order->period,
                            'create_date' => date('Y-m-d H:i:s'),
                            'accept_date' => date('Y-m-d H:i:s'),
                            'status' => 1,
                            'base_percent' => $this->settings->loan_default_percent,
                            'charge_percent' => $this->settings->loan_charge_percent,
                            'peni_percent' => $this->settings->loan_peni,
                            'service_sms' => $order->service_sms,
                            'service_reason' => $order->service_reason,
                            'service_insurance' => $order->service_insurance,
                            // 'accept_code' => $sms,
                            'accept_ip' => $_SERVER['REMOTE_ADDR'],
                            'sent_status' => 0,
                        );
                
                        // $user = $this->users->get_user($order->user_id);
                        if($user->lead_partner_id == 0){
                            $new_contract['card_id'] = $order->card_id;
                        }

                        $this->transactions->update_transaction($transaction->id, array(
                            'operation' => $operation,
                            'callback_response' => $register_info,
                            'reason_code' => $reason_code
                        ));
                        
                        $contract_id = $this->contracts->add_contract($new_contract);
                        $contract = $this->contracts->get_contract($contract_id);
    
                        $this->orders->update_order($order_id, array('contract_id' => $contract_id));                    

                        $operation_id = $this->operations->add_operation(array(
                            'contract_id' => $contract->id,
                            'user_id' => $contract->user_id,
                            'order_id' => $contract->order_id,
                            'type' => 'DOCTOR',
                            'amount' => $this->loan_doctor_payment[($user->loan_doctor + 1)],
                            'created' => date('Y-m-d H:i:s'),
                            'transaction_id' => $transaction->id,
                        ));

                        // // Выдача денег по кредитному доктору
                        $res = $this->BestPay->pay_contract_with_register($contract->id, $contract->service_insurance, $contract->service_sms);

                        // $res = 'APPROVED';
                        if ($res == 'APPROVED') {

                            $ob_date = new DateTime();
                            $ob_date->add(DateInterval::createFromDateString($contract->period . ' days'));
                            $return_date = $ob_date->format('Y-m-d H:i:s');

                            $this->contracts->update_contract($contract->id, array(
                                'status' => 2,
                                'inssuance_date' => date('Y-m-d H:i:s'),
                                'loan_body_summ' => $contract->amount,
                                'loan_percents_summ' => 0,
                                'return_date' => $return_date,
                            ));

                            $this->operations->add_operation(array(
                                'contract_id' => $contract->id,
                                'user_id' => $contract->user_id,
                                'order_id' => $contract->order_id,
                                'type' => 'P2P',
                                'amount' => $contract->amount,
                                'created' => date('Y-m-d H:i:s'),
                            ));

                            $this->orders->update_order($contract->order_id, array('status' => 5));

                            // Создаем документы для КД
                                
                            // $this->user = $this->users->get_user($contract->user_id);
    
                            $passport = str_replace([' ','-'], '', $user->passport_serial);
                            $passport_serial = substr($passport, 0, 4);
                            $passport_number = substr($passport, 4, 6);
    
                            $params = array(
                                'lastname' => $user->lastname,
                                'firstname' => $user->firstname,
                                'patronymic' => $user->patronymic,
                                'gender' => $user->gender,
                                'phone' => $user->phone_mobile,
                                'birth' => $user->birth,
                                'birth_place' => $user->birth_place,
                                'inn' => $user->inn,
                                'snils' => $user->snils,
                                'email' => $user->email,
                                'created' => $user->created,
                
                                'passport_serial' => $passport_serial,
                                'passport_number' => $passport_number,
                                'passport_date' => $user->passport_date,
                                'passport_code' => $user->subdivision_code,
                                'passport_issued' => $user->passport_issued,
                
                                // 'regindex' => $user->Regindex,
                                // 'regregion' => $user->Regregion,
                                // 'regcity' => $user->Regcity,
                                // 'regstreet' => $user->Regstreet,
                                // 'reghousing' => $user->Reghousing,
                                // 'regbuilding' => $user->Regbuilding,
                                // 'regroom' => $user->Regroom,
                                // 'faktindex' => $user->Faktindex,
                                // 'faktregion' => $user->Faktregion,
                                // 'faktcity' => $user->Faktcity,
                                // 'faktstreet' => $user->Faktstreet,
                                // 'fakthousing' => $user->Fakthousing,
                                // 'faktbuilding' => $user->Faktbuilding,
                                // 'faktroom' => $user->Faktroom,
                
                                'profession' => $user->profession,
                                'workplace' => $user->workplace,
                                'workphone' => $user->workphone,
                                // 'chief_name' => $>user->chief_name,
                                // 'chief_position' => $user->chief_position,
                                // 'chief_phone' => $>user->chief_phone,
                                'income' => $user->income,
                                'expenses' => $user->expenses,
                
                                'first_loan_amount' => $user->first_loan_amount,
                                'first_loan_period' => $user->first_loan_period,
                
                                'number' => $contract->order_id,
                                'create_date' => date('Y-m-d'),
                                'asp' => $user->sms,
                                'accept_code' => $contract->accept_code,
                            );
                            if (!empty($tuser->contact_person_name))
                            {
                                $params['contactperson_phone'] = $user->contact_person_phone;
                
                                $contact_person_name = explode(' ', $user->contact_person_name);
                                $params['contactperson_name'] = $user->contact_person_name;
                                $params['contactperson_lastname'] = isset($contact_person_name[0]) ? $contact_person_name[0] : '';
                                $params['contactperson_firstname'] = isset($contact_person_name[1]) ? $contact_person_name[1] : '';
                                $params['contactperson_patronymic'] = isset($contact_person_name[2]) ? $contact_person_name[2] : '';
                            }
                            if (!empty($user->contact_person2_name))
                            {
                                $params['contactperson2_phone'] = $user->contact_person_phone;
                
                                $contact_person2_name = explode(' ', $user->contact_person2_name);
                                $params['contactperson2_name'] = $user->contact_person2_name;
                                $params['contactperson2_lastname'] = isset($contact_person2_name[0]) ? $contact_person2_name[0] : '';
                                $params['contactperson2_firstname'] = isset($contact_person2_name[1]) ? $contact_person2_name[1] : '';
                                $params['contactperson2_patronymic'] = isset($contact_person2_name[2]) ? $contact_person2_name[2] : '';
                            }
    
                            // Согласие на ОПД
                            $this->documents->create_document(array(
                                'user_id' => $user->id,
                                'order_id' => $contract->order_id,
                                'contract_id' => $contract->id,
                                'type' => 'SOGLASIE_OPD',
                                'params' => json_encode($params),
                            ));
                            
                            // Заявление на получение займа
                            $this->documents->create_document(array(
                                'user_id' => $user->id,
                                'order_id' => $contract->order_id,
                                'contract_id' => $contract->id,
                                'type' => 'ANKETA_PEP_KD',
                                'params' => json_encode($params),
                            ));

                            $this->create_document('IND_USLOVIYA_NL', $contract);
                            $this->create_document('PRIL_1', $contract);

                            $this->create_document('DOP_DOCTOR', $contract);

                            $this->users->update_user($user->id, array(
                                'loan_doctor' => ($user->loan_doctor + 1)
                            ));
                            

                            // Снимаем страховку если есть
                            if (!empty($contract->service_insurance)) 
                            {
                                $insurance_cost = $this->insurances->get_insurance_cost($contract->amount);

                                if ($insurance_cost > 0)
                                {
                                    $insurance_amount = $insurance_cost * 100;
            
                                    $description = 'Страховой полис';
            
                                    $xml = $this->BestPay->recurring_by_token($contract->card_id, $insurance_amount, $description);
                                    $status = (string)$xml->state;
            
                                    if ($status == 'APPROVED') {
                                        $transaction = $this->transactions->get_register_id_transaction($xml->order_id);

                                        $operation_id = $this->operations->add_operation(array(
                                            'contract_id' => $contract->id,
                                            'user_id' => $contract->user_id,
                                            'order_id' => $contract->order_id,
                                            'type' => 'INSURANCE',
                                            'amount' => $insurance_cost,
                                            'created' => date('Y-m-d H:i:s'),
                                            'transaction_id' => $transaction->id,
                                            'service_number' => $max_service_value,
                                        ));
            
                                        $dt = new DateTime();
                                        $dt->add(new DateInterval('P1M'));
                                        $end_date = $dt->format('Y-m-d 23:59:59');

                                        try{
                                            $contract->insurance = new InsurancesORM();
                                            $contract->insurance->amount = $insurance_cost;
                                            $contract->insurance->user_id = $contract->user_id;
                                            $contract->insurance->order_id = $contract->order_id;
                                            $contract->insurance->start_date = date('Y-m-d 00:00:00', time() + (1 * 86400));
                                            $contract->insurance->end_date = $end_date;
                                            $contract->insurance->operation_id = $operation_id;
                                            $contract->insurance->save();

                                            $contract->insurance->number = InsurancesORM::create_number($contract->insurance->id);

                                            InsurancesORM::where('id', $contract->insurance->id)->update(['number' => $contract->insurance->number]);
                                        }catch (Exception $e)
                                        {

                                        }

                                            $this->contracts->update_contract($contract->id, array(
                                            'insurance_id' => $contract->insurance_id,
                                            // 'loan_body_summ' => $contract->amount + $insurance_cost
                                            // 'loan_body_summ' => $contract->amount
                                        ));

                                        //создаем документы для страховки
                                        $this->create_document('POLIS', $contract);
                                        $this->create_document('KID', $contract);
                                        
                                    }
                                }
                            }
                            $this->design->assign('success', 'Оплата за услугу Кредитный доктор и выдача займа прошла успешно');

                        }
                        else{
                            $this->contracts->update_contract($contract->id, array('status' => 6));
                            $this->orders->update_order($contract->order_id, array('status' => 6));
                            
                            $this->design->assign('error', 'При выдаче займа по услуге Кредитный доктор возникла ошибка');
                        }

                    } else {
                        $reason_code_description = $this->BestPay->get_reason_code_description($code);
                        $this->design->assign('reason_code_description', $reason_code_description);

                        $this->design->assign('error', 'При оформлении услуги Кредитный доктор возникла ошибка');
                    }
                    // $this->transactions->update_transaction($transaction->id, array(
                    //     'operation' => $operation,
                    //     'callback_response' => $register_info,
                    //     'reason_code' => $reason_code
                    // ));


                }
            }
        } else {
            $this->design->assign('error', 'Ошибка: Транзакция не найдена');
        }


    }

    public function add_card_action()
    {
        $register_id = $this->request->get('id', 'integer');
        $operation = $this->request->get('operation', 'integer');
        $error = $this->request->get('error', 'integer');
        $code = $this->request->get('code', 'integer');

        if (!empty($register_id)) {
            if ($transaction = $this->transactions->get_register_id_transaction($register_id)) {
                if (!empty($operation)) {
                    $operation_info = $this->BestPay->get_operation_info($transaction->sector, $register_id, $operation);
                    $xml = simplexml_load_string($operation_info);
                    $reason_code = (string)$xml->reason_code;

                    if ($reason_code == 1) {

                        $card = array(
                            'user_id' => (string)$xml->reference,
                            'name' => (string)$xml->name,
                            'pan' => (string)$xml->pan,
                            'sector' => $transaction->sector,
                            'expdate' => (string)$xml->expdate,
                            'approval_code' => (string)$xml->approval_code,
                            'token' => (string)$xml->token,
                            'operation_date' => str_replace('.', '-', (string)$xml->date),
                            'created' => date('Y-m-d H:i:s'),
                            'operation' => $xml->order_id,
                            'register_id' => $transaction->register_id,
                            'transaction_id' => $transaction->id,
                            'bin_issuer' => (string)$xml->bin_issuer,
                        );

                        $cardId = $this->cards->add_card($card);

                        $countUserCards = $this->cards->count_cards(array('user_id' => $xml->reference));
                        if ($countUserCards > 1) {
                            $this->design->assign('cardId', $cardId);
                            $this->session->set('otherCardAdded', 1);
                        }
                        $this->design->assign('success', 'Карта успешно привязана.');

                    } else {
                        $reason_code_description = $this->BestPay->get_reason_code_description($code);
                        $this->design->assign('reason_code_description', $reason_code_description);
                        $this->design->assign('error', 'При привязке карты произошла ошибка.');
                    }
                    $this->transactions->update_transaction($transaction->id, array(
                        'operation' => $operation,
                        'callback_response' => $operation_info,
                        'reason_code' => $reason_code
                    ));


                } else {
                    $callback_response = $this->BestPay->get_register_info($transaction->sector, $register_id, $operation, 1);
                    $this->transactions->update_transaction($transaction->id, array(
                        'operation' => 0,
                        'callback_response' => $callback_response
                    ));
                    $this->design->assign('error', 'При привязке карты произошла ошибка. Код ошибки: ' . $error);

                }
            } else {
                $this->design->assign('error', 'Ошибка: Транзакция не найдена');
            }


        } else {
            $this->design->assign('error', 'Ошибка запроса');
        }
    }

    private function paymentRestruct()
    {
        $register_id = $this->request->get('id', 'integer');
        $operation = $this->request->get('operation', 'integer');
        $code = $this->request->get('code', 'integer');
        $paymentId = $this->request->get('payment_id');

        if (!empty($register_id)) {
            if ($transaction = $this->transactions->get_register_id_transaction($register_id)) {
                if ($transaction_operation = $this->operations->get_transaction_operation($transaction->id)) {
                    $this->design->assign('error', 'Оплата уже принята.');
                } else {

                    if (empty($operation)) {
                        $register_info = $this->BestPay->get_register_info($transaction->sector, $register_id);
                        $xml = simplexml_load_string($register_info);

                        foreach ($xml->operations as $xml_operation)
                            if ($xml_operation->operation->state == 'APPROVED')
                                $operation = (string)$xml_operation->operation->id;
                    }


                    if (!empty($operation)) {
                        $operation_info = $this->BestPay->get_operation_info($transaction->sector, $register_id, $operation);
                        $xml = simplexml_load_string($operation_info);
                        $reason_code = (string)$xml->reason_code;
                        $payment_amount = strval($xml->amount) / 100;
                        $operation_date = date('Y-m-d H:i:s', strtotime(str_replace('.', '-', (string)$xml->date)));

                        if ($reason_code == 1) {
                            if (!($contract = $this->contracts->get_contract($transaction->reference)))
                                $contract = $this->contracts->get_number_contract($transaction->reference);
                            $rest_amount = $payment_amount;

                        } else {
                            $this->transactions->update_transaction($transaction->id, array('prolongation' => 0));
                        }

                        $planOperation = $this->PaymentsToSchedules->get($paymentId);

                        $operationId = $this->operations->add_operation(array(
                            'contract_id' => $contract->id,
                            'user_id' => $contract->user_id,
                            'order_id' => $contract->order_id,
                            'type' => 'PAY',
                            'amount' => $payment_amount,
                            'created' => $operation_date,
                            'transaction_id' => $transaction->id,
                            'loan_body_summ' => $planOperation->plan_od,
                            'loan_percents_summ' => $planOperation->plan_prc,
                            'loan_peni_summ' => $planOperation->plan_peni,
                            'loan_charge_summ' => 0
                        ));

                        $faktOperation =
                            [
                                'operation_id' => $operationId,
                                'fakt_payment' => $payment_amount,
                                'fakt_od' => $planOperation->plan_od,
                                'fakt_prc' => $planOperation->plan_prc,
                                'fakt_peni' => $planOperation->plan_peni,
                                'fakt_date' => date('Y-m-d H:i:s'),
                                'status' => 2
                            ];

                        $this->PaymentsToSchedules->update($planOperation->id, $faktOperation);

                        $countRemaining = $this->PaymentsToSchedules->get_count_remaining($contract->id);

                        if ($countRemaining == 0) {
                            $this->contracts->update_contract($contract->id, array(
                                'status' => 3,
                                'collection_status' => 0,
                                'close_date' => date('Y-m-d H:i:s'),
                            ));

                            $this->orders->update_order($contract->order_id, array(
                                'status' => 7
                            ));
                        } else {
                            $nextPay = $this->PaymentsToSchedules->get_next($contract->id);

                            $this->contracts->update_contract($contract->id, array(
                                'loan_body_summ' => $nextPay->plan_od,
                                'loan_percents_summ' => $nextPay->plan_prc,
                                'loan_peni_summ' => $nextPay->plan_peni,
                                'next_pay' => date('Y-m-d', strtotime($nextPay->plan_date)),
                                'payment_id' => $nextPay->id
                            ));
                        }

                        $this->design->assign('success', 'Оплата прошла успешно.');
                    } else {
                        $reason_code_description = $this->BestPay->get_reason_code_description($code);
                        $this->design->assign('reason_code_description', $reason_code_description);

                        $this->design->assign('error', 'При оплате произошла ошибка.');
                    }
                    $this->transactions->update_transaction($transaction->id, array(
                        'operation' => $operation,
                        'callback_response' => $register_info,
                        'reason_code' => $reason_code
                    ));


                }
            }
        } else {
            $this->design->assign('error', 'Ошибка: Транзакция не найдена');
        }
    }

    public function create_document($document_type, $contract)
    {
        $ob_date = new DateTime();
        $ob_date->add(DateInterval::createFromDateString($contract->period . ' days'));
        $return_date = $ob_date->format('Y-m-d H:i:s');

        $return_amount = round($contract->amount + $contract->amount * $contract->base_percent * $contract->period / 100, 2);
        $return_amount_rouble = (int)$return_amount;
        $return_amount_kop = ($return_amount - $return_amount_rouble) * 100;

        $contract_order = $this->orders->get_order((int)$contract->order_id);

        $insurance_cost = $this->insurances->get_insurance_cost($contract_order);

        $params = array(
            'lastname' => $contract_order->lastname,
            'firstname' => $contract_order->firstname,
            'patronymic' => $contract_order->patronymic,
            'phone' => $contract_order->phone_mobile,
            'birth' => $contract_order->birth,
            'number' => $contract->number,
            'contract_date' => date('Y-m-d H:i:s'),
            'created' => date('Y-m-d H:i:s'),
            'return_date' => $return_date,
            'return_date_day' => date('d', strtotime($return_date)),
            'return_date_month' => date('m', strtotime($return_date)),
            'return_date_year' => date('Y', strtotime($return_date)),
            'return_amount' => $return_amount,
            'return_amount_rouble' => $return_amount_rouble,
            'return_amount_kop' => $return_amount_kop,
            'base_percent' => $contract->base_percent,
            'amount' => $contract->amount,
            'period' => $contract->period,
            'return_amount_percents' => round($contract->amount * $contract->base_percent * $contract->period / 100, 2),
            'passport_serial' => $contract_order->passport_serial,
            'passport_date' => $contract_order->passport_date,
            'subdivision_code' => $contract_order->subdivision_code,
            'passport_issued' => $contract_order->passport_issued,
            'passport_series' => substr(str_replace(array(' ', '-'), '', $contract_order->passport_serial), 0, 4),
            'passport_number' => substr(str_replace(array(' ', '-'), '', $contract_order->passport_serial), 4, 6),
            'asp' => $contract->accept_code,
            'insurance_summ' => $insurance_cost,
        );

        $params['user'] = $this->users->get_user($contract->user_id);
        $params['order'] = $this->orders->get_order($contract->order_id);
        $params['contract'] = $contract;

        $params['pan'] = $this->cards->get_card($contract->card_id)->pan;

        $this->documents->create_document(array(
            'user_id' => $contract->user_id,
            'order_id' => $contract->order_id,
            'contract_id' => $contract->id,
            'type' => $document_type,
            'params' => json_encode($params),
        ));

    }
}