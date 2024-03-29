<?php
class StageCardController extends Controller
{
    public function fetch()
    {
        if (empty($this->user))
        {
            header('Location: /lk/login');
            exit;
        }

        if (!empty($this->user->stage_card))
        {
            header('Location: /account');
            exit;
        }

        if (empty($this->user->stage_files))
        {
            header('Location: /stage/files');
            exit;
        }

        if ($this->request->get('step') == 'prev')
        {
            $this->users->update_user($this->user->id, array('stage_files'=>0));
            header('Location: /stage/files');
            exit;
        }

        if ($cards = $this->cards->get_cards(array('user_id'=>$this->user->id)))
        {
            $card = reset($cards);

            // устанавливаем карту как основную
            if (count($cards) > 1)
            {
                $have_base_card = 0;
                foreach ($cards as $c)
                    if (!empty($c->base_card))
                        $have_base_card = 1;
                if (empty($have_base_card))
                    $this->cards->update_card($card->id, array('base_card' => 1));
            }
            else
            {
                $this->cards->update_card($card->id, array('base_card' => 1));
            }

            if(($this->user->lead_partner_id != 0) && ($this->user->partners_processed == 0)){
                
                $order = array(
                    'card_id' => $card->id,
                    'user_id' => $this->user->id,
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'amount' => $this->user->first_loan_amount,
                    'period' => $this->user->first_loan_period,
                    'first_loan' => 1,
                    'date' => date('Y-m-d H:i:s'),
                    'local_time' => $this->user->last_local_time,
                    'juicescore_session_id' => $this->user->juicescore_session_id,
                    // 'accept_sms' => $this->user->sms,
                    'client_status' => 'nk',
                    'autoretry' => 1
                );

                if(isset($_COOKIE['promo_code']))
                {
                    $promocode = $this->PromoCodes->get_code_by_code($_COOKIE['promo_code']);

                    if(!empty($promocode))
                        $order['promocode_id'] = $promocode->id;
                }

                if (isset($_COOKIE['utm_source'])) {
                    $order['utm_source'] = $_COOKIE['utm_source'];
                }
                $order['webmaster_id'] = $_COOKIE["wm_id"];
                $order['click_hash'] = $_COOKIE["clickid"];


                $order_new = $this->orders->get_orders(array('user_id' => $this->user->id))[0];
                $this->orders->update_order($order_new->order_id, $order);
                $order_id = $order_new->order_id;
                
                $uid = 'a0'.$order_new->order_id.'-'.date('Y').'-'.date('md').'-'.date('Hi').'-01771ca07de7';
                $this->users->update_user($this->user->id, array(
                    'stage_card' => 1,
                    'UID' => $uid,
                ));

                $contract = $this->contracts->get_contracts(array('user_id' => $this->user->id))[0];
                $update = array(
                    'card_id' => $card->id
                );
                $this->contracts->update_contract($contract->id, $update);

                $msg = 'Активируй займ ' . ($order_new->amount * 1) . ' в личном кабинете, код ' . $contract->accept_code . ' https://rus-zaym.ru/lk';
                $this->sms->send($order_new->phone_mobile, $msg);
                
            }
            else{

                $order = array(
                    'card_id' => $card->id,
                    'user_id' => $this->user->id,
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'amount' => $this->user->first_loan_amount,
                    'period' => $this->user->first_loan_period,
                    'first_loan' => 1,
                    'date' => date('Y-m-d H:i:s'),
                    'local_time' => $this->user->last_local_time,
                    'juicescore_session_id' => $this->user->juicescore_session_id,
                    'accept_sms' => $this->user->sms,
                    'client_status' => 'nk',
                    'autoretry' => 1,
                );

                if(isset($_COOKIE['promo_code']))
                {
                    $promocode = $this->PromoCodes->get_code_by_code($_COOKIE['promo_code']);

                    if(!empty($promocode))
                        $order['promocode_id'] = $promocode->id;
                }

                if (isset($_COOKIE['utm_source'])) {
                    $order['utm_source'] = $_COOKIE['utm_source'];
                }
                $order['webmaster_id'] = $_COOKIE["wm_id"];
                $order['click_hash'] = $_COOKIE["clickid"];


                $order_id = $this->orders->add_order($order);
    //            70093bcc-3a3f-11eb-9983-00155d2d0507
                $uid = 'a0'.$order_id.'-'.date('Y').'-'.date('md').'-'.date('Hi').'-01771ca07de7';
                $this->users->update_user($this->user->id, array(
                    'stage_card' => 1,
                    'UID' => $uid,
                ));

                // добавляем задание для проведения активных скорингов
                $scoring_types = $this->scorings->get_types();
                foreach ($scoring_types as $scoring_type)
                {
                    if ($scoring_type->active && empty($scoring_type->is_paid))
                    {
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

            }

            /** ******** создаем доки ********* **/
        $passport = str_replace([' ','-'], '', $this->user->passport_serial);
        $passport_serial = substr($passport, 0, 4);
        $passport_number = substr($passport, 4, 6);
        $contract = $this->contracts->get_contract($this->user->id);

        $contract = $contract[0];
        
        // $contract->user_phone_mobile = $user->phone_mobile;
        // $contract->user_email = $user->email;
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

                'regindex' => $this->user->Regindex,
                'regregion' => $this->user->Regregion,
                'regcity' => $this->user->Regcity,
                'regstreet' => $this->user->Regstreet,
                'reghousing' => $this->user->Reghousing,
                'regbuilding' => $this->user->Regbuilding,
                'regroom' => $this->user->Regroom,
                'faktindex' => $this->user->Faktindex,
                'faktregion' => $this->user->Faktregion,
                'faktcity' => $this->user->Faktcity,
                'faktstreet' => $this->user->Faktstreet,
                'fakthousing' => $this->user->Fakthousing,
                'faktbuilding' => $this->user->Faktbuilding,
                'faktroom' => $this->user->Faktroom,

                'profession' => $this->user->profession,
                'workplace' => $this->user->workplace,
                'workphone' => $this->user->workphone,
                'chief_name' => $this->user->chief_name,
                'chief_position' => $this->user->chief_position,
                'chief_phone' => $this->user->chief_phone,
                'income' => $this->user->income,
                'expenses' => $this->user->expenses,

                'first_loan_amount' => $this->user->first_loan_amount,
                'first_loan_period' => $this->user->first_loan_period,

                'number' => $order_id,
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
                'order_id' => $order_id,
                'type' => 'SOGLASIE_OPD',
                'params' => json_encode($params),
            ));

            // Согласие на НБКИ
            $this->documents->create_document(array(
                'user_id' => $this->user->id,
                'order_id' => $order_id,
                'type' => 'SOGLASIE_NBKI',
                'params' => json_encode($params),
            ));

            // Заявление на получение займа
            $this->documents->create_document(array(
                'user_id' => $this->user->id,
                'order_id' => $order_id,
                'type' => 'ANKETA_PEP',
                'params' => json_encode($params),
            ));

            //Причина отказа если не убрал галочку
            if (isset($this->user->service_reason) && $this->user->service_reason == 1) {
                $this->documents->create_document(array(
                    'user_id' => $this->user->id,
                    'order_id' => $order_id,
                    'type' => 'REASON_FOR_REFUSAL',
                    'params' => json_encode($params),
                ));
            }

            if(!empty($order['utm_source']) && $order['utm_source'] == 'leadstech')
                $this->PostBackCron->add(['order_id' => $order_id, 'status' => 0, 'goal_id' => 3]);
            
            if (!empty($order['utm_source']))
            {
                $this->leadgens->add_postback([
                    'order_id' => $order_id,
                    'created' => date('Y-m-d H:i:s'),
                    'lead_name' => $order['utm_source'],
                    'webmaster' => $order['webmaster_id'],
                    'click_hash' => $order['click_hash'],
                    'offer_id' => 0,
                    'type' => 'pending',
                ]);
            }
            
            header('Location: /account');
            exit;
        }

        return $this->design->fetch('stage/card.tpl');
    }

    private function create_documents()
    {

    }

}