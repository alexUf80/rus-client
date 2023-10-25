<?php
class AccountController extends Controller
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
        if (empty($this->user)) {
            header('Location: /lk/login');
            exit;
        }

        if(($this->user->lead_partner_id > 0) && empty($this->user->stage_contact)){
            header('Location: /stage/contact_persons');
            exit;
        }

        if (empty($this->user->stage_personal)) {
            header('Location: /stage/personal');
            exit;
        }

        if (empty($this->user->stage_passport)) {
            header('Location: /stage/passport');
            exit;
        }

        if (empty($this->user->stage_address)) {
            header('Location: /stage/address');
            exit;
        }

        if (empty($this->user->stage_work)) {
            header('Location: /stage/work');
            exit;
        }

        if (empty($this->user->stage_files)) {
            header('Location: /stage/files');
            exit;
        }

        if (empty($this->user->stage_card)) {
            header('Location: /stage/card');
            exit;
        }

        if (!$this->is_developer && empty($this->user->password)) {
            header('Location: /account/password');
            exit;
        }

        // подача повторной заявки или кредитного доктора
        if ($this->request->method('post')) {
            if (!empty($_SESSION['looker_mode']))
                return false;

            // КРЕДИТНЫЙ ДОКТОР
            if ($this->request->post('loan_doctor_step', 'integer' !== null)) {

                if (!empty($user_order) && in_array($user_order->status, array(0, 1, 2, 4, 5))) {
                    $this->design->assign('error', 'У Вас уже есть активная заявка');
                } else {
                    $loan_doctor_step = $this->request->post('loan_doctor_step', 'integer');
                    $sms = $this->request->post('sms', 'integer');



                    $period = $this->request->post('period', 'integer');
                    $card_id = $this->request->post('card_id', 'integer');

                    $service_insurance = $this->request->post('service_insurance', 'integer');
                    $service_reason = $this->request->post('service_reason', 'integer');
                    $service_sms = $this->request->post('service_sms', 'integer');

                    $juicescore_session_id = $this->request->post('juicescore_session_id');
                    $local_time = $this->request->post('local_time');

                    $client_status = 'kd';
                    

                   // // Реккурентное списание суммы за кредитный доктор
                    $xml = $this->BestPay->recurring_by_token($card_id, $this->loan_doctor_payment[$loan_doctor_step]*100, 'Кредитный доктор');
                    $status = (string)$xml->state;
                    
                    $err = [];
                    // $status = 'APPROVED';

                    if ($status == 'APPROVED') {
                        $transaction = $this->transactions->get_register_id_transaction($xml->order_id);

                        $order = array(
                            'amount' => $this->loan_doctor_steps[$loan_doctor_step],
                            'period' => $period,
                            'card_id' => $card_id,
                            'date' => date('Y-m-d H:i:s'),
                            'user_id' => $this->user->id,
                            'status' => 2,
                            'ip' => $_SERVER['REMOTE_ADDR'],
                            'first_loan' => 0,
                            'juicescore_session_id' => $juicescore_session_id,
                            'local_time' => $local_time,
                            'client_status' => $client_status,
                            'accept_sms' => $sms,
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
                            'accept_code' => $sms,
                            'accept_ip' => $_SERVER['REMOTE_ADDR'],
                            'sent_status' => 0,
                        );
                
                        $user = $this->users->get_user($order->user_id);
                        if($user->lead_partner_id == 0){
                            $new_contract['card_id'] = $order->card_id;
                        }
                        
                        $contract_id = $this->contracts->add_contract($new_contract);
                        $contract = $this->contracts->get_contract($contract_id);
    
                        $this->orders->update_order($order_id, array('contract_id' => $contract_id));                    

                        $contract = $this->contracts->get_contract($contract_id);

                        $operation_id = $this->operations->add_operation(array(
                            'contract_id' => $contract->id,
                            'user_id' => $contract->user_id,
                            'order_id' => $contract->order_id,
                            'type' => 'DOCTOR',
                            'amount' => $this->loan_doctor_payment[$loan_doctor_step],
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
                                
                            $this->user = $this->users->get_user($contract->user_id);
    
                            $passport = str_replace([' ','-'], '', $this->user->passport_serial);
                            $passport_serial = substr($passport, 0, 4);
                            $passport_number = substr($passport, 4, 6);
    
                            $params = array(
                                'lastname' => $this->user->lastname,
                                'firstname' => $this->user->firstname,
                                'patronymic' => $this->user->patronymic,
                                'gender' => $this->user->gender,
                                'phone' => $this->user->phone_mobile,
                                'birth' => $this->user->birth,
                                'birth_place' => $this->user->birth_place,
                                'inn' => $this->user->inn,
                                'snils' => $this->user->snils,
                                'email' => $this->user->email,
                                'created' => $this->user->created,
                
                                'passport_serial' => $passport_serial,
                                'passport_number' => $passport_number,
                                'passport_date' => $this->user->passport_date,
                                'passport_code' => $this->user->subdivision_code,
                                'passport_issued' => $this->user->passport_issued,
                
                                // 'regindex' => $this->user->Regindex,
                                // 'regregion' => $this->user->Regregion,
                                // 'regcity' => $this->user->Regcity,
                                // 'regstreet' => $this->user->Regstreet,
                                // 'reghousing' => $this->user->Reghousing,
                                // 'regbuilding' => $this->user->Regbuilding,
                                // 'regroom' => $this->user->Regroom,
                                // 'faktindex' => $this->user->Faktindex,
                                // 'faktregion' => $this->user->Faktregion,
                                // 'faktcity' => $this->user->Faktcity,
                                // 'faktstreet' => $this->user->Faktstreet,
                                // 'fakthousing' => $this->user->Fakthousing,
                                // 'faktbuilding' => $this->user->Faktbuilding,
                                // 'faktroom' => $this->user->Faktroom,
                
                                'profession' => $this->user->profession,
                                'workplace' => $this->user->workplace,
                                'workphone' => $this->user->workphone,
                                // 'chief_name' => $this->user->chief_name,
                                // 'chief_position' => $this->user->chief_position,
                                // 'chief_phone' => $this->user->chief_phone,
                                'income' => $this->user->income,
                                'expenses' => $this->user->expenses,
                
                                'first_loan_amount' => $this->user->first_loan_amount,
                                'first_loan_period' => $this->user->first_loan_period,
                
                                'number' => $contract->order_id,
                                'create_date' => date('Y-m-d'),
                                'asp' => $this->user->sms,
                                'accept_code' => $contract->accept_code,
                            );
                            if (!empty($this->user->contact_person_name))
                            {
                                $params['contactperson_phone'] = $this->user->contact_person_phone;
                
                                $contact_person_name = explode(' ', $this->user->contact_person_name);
                                $params['contactperson_name'] = $this->user->contact_person_name;
                                $params['contactperson_lastname'] = isset($contact_person_name[0]) ? $contact_person_name[0] : '';
                                $params['contactperson_firstname'] = isset($contact_person_name[1]) ? $contact_person_name[1] : '';
                                $params['contactperson_patronymic'] = isset($contact_person_name[2]) ? $contact_person_name[2] : '';
                            }
                            if (!empty($this->user->contact_person2_name))
                            {
                                $params['contactperson2_phone'] = $this->user->contact_person_phone;
                
                                $contact_person2_name = explode(' ', $this->user->contact_person2_name);
                                $params['contactperson2_name'] = $this->user->contact_person2_name;
                                $params['contactperson2_lastname'] = isset($contact_person2_name[0]) ? $contact_person2_name[0] : '';
                                $params['contactperson2_firstname'] = isset($contact_person2_name[1]) ? $contact_person2_name[1] : '';
                                $params['contactperson2_patronymic'] = isset($contact_person2_name[2]) ? $contact_person2_name[2] : '';
                            }
    
                            // Согласие на ОПД
                            $this->documents->create_document(array(
                                'user_id' => $this->user->id,
                                'order_id' => $contract->order_id,
                                'contract_id' => $contract->id,
                                'type' => 'SOGLASIE_OPD',
                                'params' => json_encode($params),
                            ));
                            
                            // Заявление на получение займа
                            $this->documents->create_document(array(
                                'user_id' => $this->user->id,
                                'order_id' => $contract->order_id,
                                'contract_id' => $contract->id,
                                'type' => 'ANKETA_PEP_KD',
                                'params' => json_encode($params),
                            ));

                            $this->create_document('IND_USLOVIYA_NL', $contract);
                            $this->create_document('PRIL_1', $contract);

                            $this->create_document('DOP_DOCTOR', $contract);

                            $this->users->update_user($this->user->id, array(
                                'loan_doctor' => $loan_doctor_step
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

                        }
                        else{
                            $this->contracts->update_contract($contract->id, array('status' => 6));
                            $this->orders->update_order($contract->order_id, array('status' => 6));
                            
                            $err[] = 'Ошибка! Не удалось выдать займ по Кредитному доктору';
                        }

                    }
                    else{
                        $err[] = 'Ошибка! Не выдан займ по Кредитному доктору';
                    }

                }
                $this->design->assign('err', $err);
            }
            // ПОВТОРНАЯ ЗАЯВКА
            else{
                $user_orders = $this->orders->get_orders(array('user_id' => $this->user->id));
                $user_order = reset($user_orders);
                if (!empty($user_order) && in_array($user_order->status, array(0, 1, 2, 4, 5))) {
                    $this->design->assign('error', 'У Вас уже есть активная заявка');
                } else {

                    $last_contract = $this->contracts->get_last_contract($this->user->id);

                    if(!empty($last_contract)){
                        $issuance_date_from = date('Y-m-d', strtotime($last_contract->close_date.'-1 year'));
                        $count_closed_contracts = $this->contracts->count_contracts([
                            'user_id' => $this->user->id,
                            'status' => 7,
                            'issuance_date_from' => $issuance_date_from
                        ]);

                        if($count_closed_contracts >= 9){
                            $this->design->assign('error', 'Ограничение на количество контрактов (не более 9 за один календарный год)');
                            exit;
                        }

                    }

                    $amount = $this->request->post('amount', 'integer');
                    $period = $this->request->post('period', 'integer');
                    $card_id = $this->request->post('card_id', 'integer');

                    $service_insurance = $this->request->post('service_insurance', 'integer');
                    $service_reason = $this->request->post('service_reason', 'integer');
                    $service_sms = $this->request->post('service_sms', 'integer');

                    $juicescore_session_id = $this->request->post('juicescore_session_id');
                    $local_time = $this->request->post('local_time');

                    setcookie('loan_amount', null);
                    setcookie('loan_period', null);

                    $client_status = $this->users->check_client_status($this->user);

                    $this->users->update_user($this->user->id, array(
                        'service_insurance' => $service_insurance,
                        'service_reason' => $service_reason,
                        'service_sms' => $service_sms,
                        'client_status' => $client_status
                    ));

                    $order = array(
                        'amount' => $amount,
                        'period' => $period,
                        'card_id' => $card_id,
                        'date' => date('Y-m-d H:i:s'),
                        'user_id' => $this->user->id,
                        'status' => 0,
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'first_loan' => 0,
                        'juicescore_session_id' => $juicescore_session_id,
                        'local_time' => $local_time,
                        'client_status' => $client_status,
                    );

                    if(isset($_COOKIE['promo_code']))
                    {
                        $promocode = $this->PromoCodes->get_code_by_code($_COOKIE['promo_code']);

                        if(!empty($promocode))
                            $order['promocode_id'] = $promocode->id;
                    }

                    $order['utm_source'] = $_COOKIE['utm_source'];
                    $order['webmaster_id'] = $_COOKIE["wm_id"];
                    $order['click_hash'] = $_COOKIE["clickid"];


                    // проверяем возможность автоповтора
                    $order['autoretry'] = 1;

                    if(isset($_COOKIE['promo_code']))
                    {
                        $promocode = $this->PromoCodes->get_code_by_code($_COOKIE['promo_code']);

                        if(!empty($promocode))
                            $order['promocode_id'] = $promocode->id;
                    }

                    $order_id = $this->orders->add_order($order);

                    // добавляем задание для проведения активных скорингов
                    $scoring_types = $this->scorings->get_types();
                    foreach ($scoring_types as $scoring_type) {
                        if ($scoring_type->active && empty($scoring_type->is_paid)) {
                            $add_scoring = array(
                                'user_id' => $this->user->id,
                                'order_id' => $order_id,
                                'type' => $scoring_type->name,
                                'status' => 'new',
                                'created' => date('Y-m-d H:i:s')
                            );
                            $this->scorings->add_scoring($add_scoring);
                        }
                    }

                    if(!empty($order['utm_source']) && $order['utm_source'] == 'leadstech')
                        $this->PostBackCron->add(['order_id' => $order_id, 'status' => 0, 'goal_id' => 3]);


                    header('Location: /account');
                    exit;
                }
            }

        }
        $documents = $this->documents->get_documents(array('user_id' => $this->user->id, 'client_visible'=>1));
        $this->design->assign('documents', $documents);

        if ($active_contract = $this->contracts->find_active_contracts($this->user->id)) {
            $order = $this->orders->get_order((int)$active_contract->order_id);

        } else {
            $orders = $this->orders->get_orders(array('user_id' => $this->user->id, 'sort' => 'date_desc'));

            foreach ($orders as $o) {
                if($o->client_status == 'kd'){
                    continue;
                }
                $order = $o;
                break;
            }
            
            
        }

        if (!empty($order)) {

            $order = $this->orders->get_order($order->order_id);

            if($order->loantype_id != 0)
            {
                $loantype = $this->Loantypes->get_loantype($order->loantype_id);
                $stdPercent = $loantype->percent/100;
            }else
                $stdPercent = 0.008;


            $order->return_amount = ($order->amount * $stdPercent * $order->period) + $order->amount;
            $return_period = date_create();
            date_add($return_period, date_interval_create_from_date_string($order->period . ' days'));
            $order->return_period = date_format($return_period, 'Y-m-d H:i:s');
        }

        // мараторий
        if (!empty($order) && ($order->status == 3 || $order->status == 8)) {
            $reason = $this->reasons->get_reason($order->reason_id);
            if (!empty($reason) && $reason->maratory > 0) {
                if ((strtotime($order->reject_date) + $reason->maratory * 86400) > time()) {
                    $reject_block = date('Y-m-d H:i:s', strtotime($order->reject_date) + $reason->maratory * 86400 + 64800);
                    $this->design->assign('reject_block', $reject_block);
                }
            }
        }

        $show_prolongation = false;
        if (!empty($order))
            $order->prolongation_date = null;


        if ($order->contract_id) {
            $order->contract = $this->contracts->get_contract($order->contract_id);

            $date1 = new DateTime(date('Y-m-d', strtotime($order->contract->inssuance_date)));
            $date2 = new DateTime(date('Y-m-d'));
            $date3 = new DateTime(date('Y-m-d', strtotime($order->contract->return_date)));

            $currenDate = date('Y-m-d', time());

            $pro_date = $order->contract->return_date;


            if (date_diff($date2, $date3)->days <= 3) {
                $show_prolongation = true;
                $diff_days = date_diff($date1, $date3)->days;
                if ($diff_days > 150) {
                    $show_prolongation = false;
                }
            } else if ($date2 > $date3) {
                $show_prolongation = true;
                $diff_days = date_diff($date2, $date1)->days;
                $pro_date = $currenDate;
                if ($diff_days > 150) {
                    $show_prolongation = false;
                }
            }

            if ($show_prolongation) {
                $date_interval = 30;
                if ($diff_days > 120) {
                    $date_interval = 150 - $diff_days;
                }

                if ($date_interval < 0) {
                    $order->prolongation_date = null;
                } else {
                    $date_interval = new DateInterval("P{$date_interval}D");
                    $order->prolongation_date = (new DateTime($pro_date))->add($date_interval)->format('Y-m-d');
                }
            }

            if (!$order->prolongation_date) {
                $show_prolongation = false;
            }

            $diff = $date2->diff($date1);
            $order->contract->delay = $diff->days;

            $operations = $this->operations->get_operations(['contract_id' => $order->contract_id]);
            $percents_sum = 0;
            $percents_count = 0;
            $percents = 0;
            foreach ($operations as $operation) {
                if ($operation->type == 'PERCENTS') {
                    $percents_sum += $operation->amount;
                    $percents_count += 1;
                    $percents = $operation->amount;
                }
            }

            $max_loan_value = 1.3;
            $diff_to_new_max  = intval((strtotime(date('Y-m-d', strtotime($order->contract->inssuance_date))) - strtotime(date('Y-m-d', strtotime('2023-07-01')))) / 86400);
            if ($diff_to_new_max < 0) {
                $max_loan_value = 1.5;
            }

            $prolongation_amount = 0;
            if (empty($order->contract->stop_profit)) {
                if (empty($order->contract->hide_prolongation)) {
                    if ($order->contract->type == 'base' && ($order->contract->status == 2 || $order->contract->status == 4)) // выдан
                    {
                        if ($order->contract->prolongation < 5 || ($order->contract->prolongation >= 5 && $order->contract->sold)) {
                            if ($order->contract->loan_percents_summ > 0) {
                                if ($percents_sum < $order->contract->amount * $max_loan_value) {
                                    // $prolongation_amount = $order->contract->loan_percents_summ + $this->settings->prolongation_amount;
                                    $prolongation_amount = $order->contract->loan_percents_summ + $order->contract->loan_peni_summ + $this->settings->prolongation_amount;
                                }
                            }
                        }
                    }
                }
            }

            if (date_diff($date2, $date3)->days <= 3 || $date2 > $date3)
                $this->design->assign('prolongation_amount', $prolongation_amount);



            /*
            $inssuance_date = new DateTime();
            $finish_date = new DateTime();
            $finish_date->add(DateInterval::createFromDateString($order->contract->period.' days'));

            $inssuance_date = date_create($order->contract->inssuance_date);
            date_add($inssuance_date, date_interval_create_from_date_string($order->contract->period.' days'));
            $diff_period = date_diff($inssuance_date, );
            $order->contract->return_amount = ($order->contract->loan_body_summ * $order->contract->base_percent * $diff_period) + $order->contract->loan_body_summ + $order->contract->loan_percents_summ;
            */

//            $return_period = date_create($order->contract->inssuance_date);
//            date_add($return_period, date_interval_create_from_date_string($order->contract->period.' days'));
//            $order->contract->return_date = date_format($return_period, 'Y-m-d H:i:s');
        }

        if (!empty($order))
            $this->design->assign('order', $order);

        $need_fields = $this->users->check_fields($this->user);
        $this->design->assign('need_fields', $need_fields);

        $statuses = $this->orders->get_statuses();
        $this->design->assign('statuses', $statuses);

        $min_summ = $this->settings->loan_min_summ;
        $max_summ = $this->settings->loan_max_summ;
        $min_period = $this->settings->loan_min_period;
        $max_period = $this->settings->loan_max_period;
        $current_summ = empty($_COOKIE['loan_summ']) ? $this->settings->loan_default_summ : $_COOKIE['loan_summ'];
        $current_period = empty($_COOKIE['loan_period']) ? $this->settings->loan_default_period : $_COOKIE['loan_period'];
        $loan_percent = $this->settings->loan_default_percent;


        $this->design->assign('min_summ', $min_summ);
        $this->design->assign('max_summ', $max_summ);
        $this->design->assign('min_period', $min_period);
        $this->design->assign('max_period', $max_period);
        $this->design->assign('current_summ', $current_summ);
        $this->design->assign('current_period', $current_period);
        $this->design->assign('loan_percent', $loan_percent);
        $this->design->assign('show_prolongation', $show_prolongation);

        if ($closedContract = $this->session->getFlash('closedContract')) {
            $this->design->assign('closedContract', $closedContract);
        }

        // TODO: Сделать проверку на показ формы для нового кредита
        $this->design->assign('form_repeat_order', 1);

        $cards = $this->cards->get_cards(array('user_id' => $this->user->id));
        $this->design->assign('cards', $cards);
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($order);echo '</pre><hr />';

        if (isset($this->user->contract) && $this->user->contract->status != 3 && $this->user->contract->sold) {
            if (empty($this->user->contract->premier))
                return $this->design->fetch('account/cession.tpl');
            else
                return $this->design->fetch('account/premier.tpl');
        } else{
            $warning_card = '';

            foreach ($cards as $card){
                list($month, $year) = explode('/', $card->expdate);
                $card_exp = date('Y-m-t', strtotime($year.'-'.$month));
                $now_date = date('Y-m-d');

                if ($card->expdate != '') {
                    if($now_date > $card_exp){
                        $last_four_digits = substr($card->pan, -4);
                        $warning_card .= "Пожалуйста, замените карту *$last_four_digits. Она не активна, мы не сможем зачислить займ<br>";
                    }
                }

            }

            $kd = OperationsORM::query()
                ->where('order_id', '=', $order->order_id)
                ->where('type', '=', 'DOCTOR')
                ->first();
            $this->design->assign('kd', $kd);

            $this->design->assign('warning_card', $warning_card);
            $this->design->assign('loan_doctor_steps', $this->loan_doctor_steps);
            $this->design->assign('loan_doctor_payment', $this->loan_doctor_payment);
            $this->design->assign('loan_doctor_steps_count', count($this->loan_doctor_steps));

            $user_date = new DateTime(date('Y-m-d', strtotime($order->birth)));
            $now_date = new DateTime(date('Y-m-d'));
            $user_age = date_diff($user_date, $now_date);
            $user_age_year = $user_age->y;

            $this->design->assign('user_age_year', $user_age_year);
            $this->design->assign('kd_on', $this->settings->kd_on);
            $this->design->assign('safe_mode', $this->settings->safe_mode);

            return $this->design->fetch('account/home.tpl');
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
