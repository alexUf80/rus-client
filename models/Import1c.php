<?php

class Import1c extends Core
{
    public function import_images($user_id, $images)
    {
        $items = array();
    	if (!empty($images))
        {
            foreach ($images as $image)
            {
                $new_item = array(
                    'user_id' => $user_id,
                    'name' => $image,
                    'type' => 'file',
                    'status' => 4
                );
                $new_item['id'] = $this->users->add_file($new_item);
            
                $items[] = (object)$new_item;
            }
        }
        
        return $items;
    }
    
    /**
     * Import1c::import_balance()
     * 
     * @param mixed $user_id
     * @param mixed $balance
     * @return void
     */
    public function import_balance($user_id, $balance_1c)
    {
    	if (!empty($balance_1c->НомерЗайма))
        {
            if ($balance_1c->НомерЗайма == 'Погашен')
            {
                if ($contracts = $this->contracts->get_contracts(array('user_id'=>$user_id)))
                {
                    foreach ($contracts as $c)
                    {
                        if ($c->type == 'onec')
                        {
                            $this->contracts->update_contract($c->id, array(
                                'status' => 3, // закрыт
                                'loan_body_summ' => 0,
                                'loan_percents_summ' => 0,
                                'loan_charge_summ' => 0,
                                'loan_peni_summ' => 0,
                                'close_date' => date('Y-m-d H:i:s'),                    
                            ));
                            $this->orders->update_order($c->order_id, array(
                                'status' => 7
                            ));
                        }   
                    }
                }
                
                if ($user_contract = $this->contracts->get_number_contract($balance_1c->НомерЗайма))
                {
                    $this->contracts->update_contract($user_contract->id, array(
                        'status' => 3, // закрыт
                        'loan_body_summ' => 0,
                        'loan_percents_summ' => 0,
                        'loan_charge_summ' => 0,
                        'loan_peni_summ' => 0,                    
                    ));
                }
            }
            elseif ($balance_1c->НомерЗайма == 'Договор передан в суд')
            {
                $number = $balance_1c->НомерПроданногоДоговора;
                if ($user_contract = $this->contracts->get_number_contract($number))
                {
                    if ($user_contract->type == 'onec')
                    {
                        $this->contracts->update_contract($user_contract->id, array(
                            'loan_body_summ' => $balance_1c->ОстатокОД,
                            'loan_percents_summ' => $balance_1c->ОстатокПроцентов,
                            'loan_charge_summ' => 0,
                            'loan_peni_summ' => $balance_1c->ОстатокПени,
                            'status' => 8, // Суд
                            'sud' => 1
                        ));                                        
                    }
                }
                else
                {
                    $order = array(
                        'user_id' => $user_id,
                        'status' => 5,
                        'type' => 'onec',
                        'manager_id' => 21,// 1С пользователь
                    );
                    $order_id = $this->orders->add_order($order);
                    
                    $contract = array(
                        'order_id' => $order_id,
                        'user_id' => $user_id,
                        'type' => 'onec',
                        'number' => $number,
                        'amount' => 0,
                        'period' => 0,
                        'base_percent' => $balance_1c->ПроцентнаяСтавка,
                        'charge_percent' => 0,
                        'peni_percent' => 0,
                        'service_reason' => 0,
                        'service_insurance' => 0,
                        'accept_code' => 0,
                        'accept_ip' => '',
                        'card_id' => 0,
                        'status' => 8, 
                        'sud' => 1,
                        'loan_body_summ' => $balance_1c->ОстатокОД,
                        'loan_percents_summ' => $balance_1c->ОстатокПроцентов,
                        'loan_charge_summ' => 0,
                        'loan_peni_summ' => $balance_1c->ОстатокПени,
                        'insurance_id' => 0,
                        'prolongation' => 0,
                        'create_date' => date('Y-m-d H:i:s', strtotime($balance_1c->ДатаЗайма)),
                        'sent_status' => 3, // отправлять не нужно
                    );
                    $contract_id = $this->contracts->add_contract($contract);
                    
                    $this->orders->update_order($order_id, array('contract_id' => $contract_id));
                    
                }
            }
            elseif ($balance_1c->НомерЗайма == 'Договор продан')
            {
                $number = $balance_1c->НомерПроданногоДоговора;
                if ($user_contract = $this->contracts->get_number_contract($number))
                {
                    if ($user_contract->type == 'onec')
                    {
                        $this->contracts->update_contract($user_contract->id, array(
                            'loan_body_summ' => $balance_1c->ОстатокОД,
                            'loan_percents_summ' => $balance_1c->ОстатокПроцентов,
                            'loan_charge_summ' => 0,
                            'loan_peni_summ' => $balance_1c->ОстатокПени,
                            'status' => 4, // Просрочка
                            'sold' => 1
                        ));                                        
                    }
                }
                else
                {
                    $order = array(
                        'user_id' => $user_id,
                        'status' => 5,
                        'type' => 'onec',
                        'manager_id' => 21,// 1С пользователь
                    );
                    $order_id = $this->orders->add_order($order);
                    
                    $contract = array(
                        'order_id' => $order_id,
                        'user_id' => $user_id,
                        'type' => 'onec',
                        'number' => $number,
                        'amount' => 0,
                        'period' => 0,
                        'base_percent' => $balance_1c->ПроцентнаяСтавка,
                        'charge_percent' => 0,
                        'peni_percent' => 0,
                        'service_reason' => 0,
                        'service_insurance' => 0,
                        'accept_code' => 0,
                        'accept_ip' => '',
                        'card_id' => 0,
                        'status' => 4, 
                        'sold' => 1,
                        'loan_body_summ' => $balance_1c->ОстатокОД,
                        'loan_percents_summ' => $balance_1c->ОстатокПроцентов,
                        'loan_charge_summ' => 0,
                        'loan_peni_summ' => $balance_1c->ОстатокПени,
                        'insurance_id' => 0,
                        'prolongation' => 0,
                        'create_date' => date('Y-m-d H:i:s', strtotime($balance_1c->ДатаЗайма)),
                        'sent_status' => 3, // отправлять не нужно
                    );
                    $contract_id = $this->contracts->add_contract($contract);
                    
                    $this->orders->update_order($order_id, array('contract_id' => $contract_id));
                }
            }
            else
            {
                if ($user_contract = $this->contracts->get_number_contract($balance_1c->НомерЗайма))
                {
                    if ($user_contract->type == 'onec')
                    {                                        
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($balance_1c, $user_contract);echo '</pre><hr />';exit;
                        $this->contracts->update_contract($user_contract->id, array(
                            'loan_body_summ' => $balance_1c->ОстатокОД,
                            'loan_percents_summ' => $balance_1c->ОстатокПроцентов,
                            'loan_charge_summ' => 0,
                            'loan_peni_summ' => $balance_1c->ОстатокПени,
                        ));                    
                    }                    
                }
                else
                {
                    $order = array(
                        'user_id' => $user_id,
                        'status' => 5,
                        'type' => 'onec',
                        'manager_id' => 21,// 1С пользователь
                    );
                    $order_id = $this->orders->add_order($order);
                    
                    $contract = array(
                        'order_id' => $order_id,
                        'user_id' => $user_id,
                        'type' => 'onec',
                        'number' => $balance_1c->НомерЗайма,
                        'amount' => 0,
                        'period' => 0,
                        'base_percent' => $balance_1c->ПроцентнаяСтавка,
                        'charge_percent' => 0,
                        'peni_percent' => 0,
                        'service_reason' => 0,
                        'service_insurance' => 0,
                        'accept_code' => 0,
                        'accept_ip' => '',
                        'card_id' => 0,
                        'status' => 2, // выдан
                        'loan_body_summ' => $balance_1c->ОстатокОД,
                        'loan_percents_summ' => $balance_1c->ОстатокПроцентов,
                        'loan_charge_summ' => 0,
                        'loan_peni_summ' => $balance_1c->ОстатокПени,
                        'insurance_id' => 0,
                        'prolongation' => 0,
                        'create_date' => date('Y-m-d H:i:s', strtotime($balance_1c->ДатаЗайма)),
                        'sent_status' => 3, // отправлять не нужно
                    );
                    $contract_id = $this->contracts->add_contract($contract);
                    
                    $this->orders->update_order($order_id, array('contract_id' => $contract_id));
                }
            }
        }
    }
    
    /**
     * Import_1c::import_user()
     * Записывает в базу данные пользователя полученные из 1с
     * 
     * @param string $uid
     * @param object $details
     * @return integer $user_id
     */
    public function import_user($uid, $details, $sms)
    {
//        echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($details);echo '</pre><hr />';
        
        if ($user_id = $this->users->get_uid_user($uid))
            return $user_id;
        
        
        
        if ($details->Пол == 'Мужской')
            $gender = 'male';
        elseif ($details->Пол == 'Женский')
            $gender = 'female';
        else
            $gender = '';
        
        $birth = date('d.m.Y', strtotime(str_replace('.', '-', $details->ДатаРожденияПоПаспорту)));
        if (empty($birth))
            $birth = '';
        
        $passport_date = date('d.m.Y', strtotime(str_replace('.', '-', $details->ПаспортДатаВыдачи)));
        if (empty($passport_date))
            $passport_date = '';
        
        list($regregion, $regregion_shorttype) = $this->parse_shorttype($details->АдресРегистрацииРегион);
        list($regdistrict, $regdistrict_shorttype) = $this->parse_shorttype($details->АдресРегистрацииРайон);
        list($reglocality, $reglocality_shorttype) = $this->parse_shorttype($details->АдресРегистрацииНасПункт);
        list($regcity, $regcity_shorttype) = $this->parse_shorttype($details->АдресРегистрацииГород, ',');
        list($regstreet, $regstreet_shorttype) = $this->parse_shorttype($details->АдресРегистрацииУлица);

        list($faktregion, $faktregion_shorttype) = $this->parse_shorttype($details->АдресФактическогоПроживанияРегион);
        list($faktdistrict, $faktdistrict_shorttype) = $this->parse_shorttype($details->АдресФактическогоПроживанияРайон);
        list($faktlocality, $faktlocality_shorttype) = $this->parse_shorttype($details->АдресФактическогоПроживанияНасПункт);
        list($faktcity, $faktcity_shorttype) = $this->parse_shorttype($details->АдресФактическогоПроживанияГород, ',');
        list($faktstreet, $faktstreet_shorttype) = $this->parse_shorttype($details->АдресФактическогоПроживанияУлица);


        $user = array(
            'UID' => $uid,
            'loaded_from_1c' => 1,
            'email' => $details->Email,
            'sms' => empty($sms) ? '' : $sms,
            
            'stage_personal' => 1,
            'stage_passport' => 1,
            'stage_address' => 1,
            'stage_work' => 1,
            'stage_files' => 1,
            'stage_card' => 1,
            
            'created' => date('Y-m-d H:i:s'),
            'enabled' => 1,
            'last_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
            'reg_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
            
            'lastname' => $details->Фамилия,
            'firstname' => $details->Имя,
            'patronymic' => $details->Отчество,

            'gender' => $gender,
            'birth' => $birth,
            'birth_place' => $details->МестоРожденияПоПаспорту,
            'phone_mobile' => $this->sms->clear_phone($details->АдресФактическогоПроживанияМобильныйТелефон),

            'passport_serial' => $details->ПаспортСерия.'-'.$details->ПаспортНомер,
            'subdivision_code' => $details->ПаспортКодПодразделения,
            'passport_date' => $passport_date,
            'passport_issued' => $details->ПаспортКемВыдан,

            'Regindex' => $details->АдресРегистрацииИндекс,
            'Regregion' => empty($regregion) ? '' : $regregion,
            'Regregion_shorttype' => empty($regregion_shorttype) ? '' : $regregion_shorttype,
            'Regdistrict' => empty($regdistrict) ? '' : $regdistrict,
            'Regdistrict_shorttype' => empty($regdistrict_shorttype) ? '' : $regdistrict_shorttype,
            'Reglocality' => empty($reglocality) ? '' : $reglocality,
            'Reglocality_shorttype' => empty($reglocality_shorttype) ? '' : $reglocality_shorttype,
            'Regcity' => empty($regcity) ? '' : $regcity,
            'Regcity_shorttype' => empty($regcity_shorttype) ? '' : $regcity_shorttype,
            'Regstreet' => empty($regstreet) ? '' : $regstreet,
            'Regstreet_shorttype' => empty($regstreet_shorttype) ? '' : $regstreet_shorttype,
            'Reghousing' => $details->АдресРегистрацииДом,
            'Regbuilding' => '',
            'Regroom' => $details->АдресРегистрацииКвартира,

            'Faktindex' => $details->АдресФактическогоПроживанияИндекс,
            'Faktregion' => empty($faktregion) ? '' : $faktregion,
            'Faktregion_shorttype' => empty($faktregion_shorttype) ? '' : $faktregion_shorttype,
            'Faktdistrict' => empty($faktdistrict) ? '' : $faktdistrict,
            'Faktdistrict_shorttype' => empty($faktdistrict_shorttype) ? '' : $faktdistrict_shorttype,
            'Faktlocality' => empty($faktlocality) ? '' : $faktlocality,
            'Faktlocality_shorttype' => empty($faktlocality_shorttype) ? '' : $faktlocality_shorttype,
            'Faktcity' => empty($faktcity) ? '' : $faktcity,
            'Faktcity_shorttype' => empty($faktcity_shorttype) ? '' : $faktcity_shorttype,
            'Faktstreet' => empty($faktstreet) ? '' : $faktstreet,
            'Faktstreet_shorttype' => empty($faktstreet_shorttype) ? '' : $faktstreet_shorttype,
            'Fakthousing' => $details->АдресФактическогоПроживанияДом,
            'Faktbuilding' => '',
            'Faktroom' => $details->АдресФактическогоПроживанияКвартира,
            
            'profession' => $details->ОрганизацияДолжность,
            'workplace' => $details->ОрганизацияНазвание,
            'workaddress' => $details->ОрганизацияАдрес,
            'workcomment' => $details->ОрганизацияКомментарийКТелефону,
            'workphone' => $this->sms->clear_phone($details->ОрганизацияТелефон),
            'chief_name' => $details->ОрганизацияФИОРуководителя,
            'chief_position' => '',
            'chief_phone' => $this->sms->clear_phone($details->ОрганизацияТелефонРуководителя),

            'income' => $details->ОрганизацияЕжемесячныйДоход,
            'expenses' => $details->ОбщаяСуммаРасходов,
            'social' => $details->VK_id,
            
        );
        
        if (!empty($details->КонтактныеЛица[0]))
        {
            $user['contact_person_name'] = $details->КонтактныеЛица[0]->Фамилия.' '.$details->КонтактныеЛица[0]->Имя.' '.$details->КонтактныеЛица[0]->Отчество;
            $user['contact_person_phone'] = $this->sms->clear_phone($details->КонтактныеЛица[0]->ТелефонМобильный);
            $user['contact_person_relation'] = $details->КонтактныеЛица[0]->СтепеньРодства;
        }
        
        if (!empty($details->КонтактныеЛица[1]))
        {
            $user['contact_person2_name'] = $details->КонтактныеЛица[1]->Фамилия.' '.$details->КонтактныеЛица[1]->Имя.' '.$details->КонтактныеЛица[1]->Отчество;
            $user['contact_person2_phone'] = $this->sms->clear_phone($details->КонтактныеЛица[1]->ТелефонМобильный);
            $user['contact_person2_relation'] = $details->КонтактныеЛица[1]->СтепеньРодства;
        }
        
        if (!empty($details->КонтактныеЛица[2]))
        {
            $user['contact_person3_name'] = $details->КонтактныеЛица[2]->Фамилия.' '.$details->КонтактныеЛица[2]->Имя.' '.$details->КонтактныеЛица[2]->Отчество;
            $user['contact_person3_phone'] = $this->sms->clear_phone($details->КонтактныеЛица[2]->ТелефонМобильный);
            $user['contact_person3_relation'] = $details->КонтактныеЛица[2]->СтепеньРодства;
        }
        
        $user_id = $this->users->add_user($user);

        $this->user = $this->users->get_user((int)$user_id);
        
        /** ******** создаем доки ********* **/
        list($passport_number, $passport_serial) = explode('-', $this->user->passport_serial);
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
            
            'number' => '',//$order->order_id,
            'create_date' => date('Y-m-d H:i:s'),
            'asp' => $this->user->sms,
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
        // анкета заявление
        $this->documents->create_document(array(
            'user_id' => $this->user->id,
            'order_id' => 0,
            'type' => 'ANKETA_PEP',
            'params' => $params,                
        ));
        
        // согласие на взаимодействие
        $this->documents->create_document(array(
            'user_id' => $this->user->id,
            'order_id' => 0,
            'type' => 'SOGLASIE_VZAIMODEYSTVIE',
            'params' => $params,                
        ));
        
        // согласие для мегафона
        $this->documents->create_document(array(
            'user_id' => $this->user->id,
            'order_id' => 0,
            'type' => 'SOGLASIE_MEGAFON',
            'params' => $params,                
        ));
        
        // согласие для скористы
        $this->documents->create_document(array(
            'user_id' => $this->user->id,
            'order_id' => 0,
            'type' => 'SOGLASIE_SCORING',
            'params' => $params,                
        ));
        
        // согласие на списание
        $this->documents->create_document(array(
            'user_id' => $this->user->id,
            'order_id' => 0,
            'type' => 'SOGLASIE_SPISANIE',
            'params' => $params,                
        ));
        
        $params['service_reason_cost'] = $this->settings->reject_reason_cost;        
        $this->documents->create_document(array(
            'user_id' => $this->user->id,
            'order_id' => 0,
            'type' => 'PRICHINA_OTKAZA',
            'params' => $params,                
        ));


        
        return $user_id;
    }
    
    /**
     * Import_1c::parse_shorttype()
     * Парсит названия городов, регионов улиц и извлекает тип 
     * 
     * @param string $subject
     * @param string $delimiter
     * @return array
     */
    private function parse_shorttype($subject, $delimiter = ' ')
    {
        $response = array(
            0 => '', // main
            1 => '', // shorttype
        );
        
        if (!empty($subject))
        {
            $expl = explode($delimiter, $subject);
            if (count($expl) > 1)
            {
                $response[1] = mb_strtolower(array_pop($expl), 'utf-8');
                $response[0] = implode($delimiter, $expl);
            }
            else
            {
                $response[0] = $subject;
            }
        }
        
        return $response;
    }

}