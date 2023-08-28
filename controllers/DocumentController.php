<?php

error_reporting(-1);
ini_set('display_errors', 'off');
ini_set('max_execution_time', 120);

class DocumentController extends Controller
{
    public function fetch()
    {
        if ($this->request->get('action') == 'preview') {
            $this->action_preview();
            exit;
        }

        $id = $this->request->get('id');
        $id = str_replace('.pdf', '', $id);
        if (empty($id))
            return false;

        if (!($user_id = $this->request->get('user_id', 'integer')))
            return false;

        if (!($document = $this->documents->get_document($id)))
            return false;

        if ($user_id != $document->user_id)
            return false;

        if (!($user = $this->users->get_user($document->user_id)))
            return false;


        $order = $this->orders->get_order($document->order_id);
        $contract = $this->contracts->get_contract($order->contract_id);

        if (!empty($document->params)) {

            $document->params = json_decode($document->params, true);

            if (in_array($document->type, ['DOP_SOGLASHENIE']))
                $this->design->assign('inssuance_date', $contract->inssuance_date);

            if (in_array($document->type, ['DOP_RESTRUCT', 'GRAPH_RESTRUCT']))
                $document->params['schedules']['payment_schedules'] = json_decode($document->params['schedules']['payment_schedules'], true);

            $pay_body_summ = 0;
            $pay_percents_summ = 0;
            $pay_peni_summ = 0;
            if (in_array($document->type, ['PRIL_1'])){
                $operations = OperationsORM::query()
                ->where('order_id', '=', $order->order_id)
                ->whereIn('type', ['PAY', 'RECURRENT'])
                ->get();
                foreach ($operations as $operation) {
                    $transaction = $this->transactions->get_transaction($operation->transaction_id);
                    $operation->tr_loan_body_summ = $transaction->loan_body_summ;
                    $operation->tr_loan_percents_summ = $transaction->loan_percents_summ;
                    $operation->tr_loan_peni_summ = $transaction->loan_peni_summ;

                    $pay_body_summ += $transaction->loan_body_summ;
                    $pay_percents_summ += $transaction->loan_percents_summ;
                    $pay_peni_summ += $transaction->loan_peni_summ;
                }
                $this->design->assign('operations', $operations);
                $this->design->assign('pay_body_summ', $pay_body_summ);
                $this->design->assign('pay_percents_summ', $pay_percents_summ);
                $this->design->assign('pay_peni_summ', $pay_peni_summ);

                $peni_sum = 0;
                $percents_sum = 0;
                $operations = OperationsORM::query()
                ->where('order_id', '=', $order->order_id)
                ->whereIn('type', ['PENI', 'PERCENTS'])
                ->get();
                foreach ($operations as $operation) {
                    if ($operation->type == 'PENI') {
                        $peni_sum = $operation->amount;
                    }
                    else{
                        $percents_sum = $operation->amount;
                    }
                }
                $this->design->assign('peni_sum', $peni_sum);
                $this->design->assign('percents_sum', $percents_sum);
                
                $date1 = new DateTime(date('Y-m-d', strtotime($contract->inssuance_date)));
                $date2 = new DateTime(date('Y-m-d'));
                
                $diff = $date2->diff($date1);
                $inssuance_delay = $diff->days;
                
                $this->design->assign('inssuance_delay', $inssuance_delay);
                
            }

            foreach ($document->params as $param_name => $param_value) {
                if ($param_name == 'insurance')
                    $this->design->assign('insurances', (object)$param_value);
                else if ($param_name == 'contract') {
                    $insuranceCreated = $this->Insurances->get_insurance($param_value['insurance']['id']);
                    $insuranceCreated = $insuranceCreated->create_date;

                    $this->design->assign('insuranceCreated', $insuranceCreated);
                    $this->design->assign('insurances', (object)$param_value['insurance']);
                }
                else if ($param_name == 'return_amount_percents'){
                    // $this->design->assign('return_amount_percents', round($contract->amount * $contract->base_percent * $contract->period / 100, 2),);
                    // $this->design->assign('return_amount_percents', 0);
                } else
                    $this->design->assign($param_name, $param_value);

                    $this->design->assign('return_amount_percents', round($contract->amount * $contract->base_percent * $contract->period / 100, 2),);
            }

            $this->design->assign('docCreated', $document->created);

            foreach ($user as $key => $value)
                $this->design->assign($key, $value);

            $faktaddress = $this->Addresses->get_address($user->faktaddress_id);
            $faktaddress_full = $faktaddress->adressfull;

            $this->design->assign('faktaddress_full', $faktaddress_full);

            $regaddress = $this->Addresses->get_address($user->regaddress_id);
            $regaddress_full = $regaddress->adressfull;

            $this->design->assign('regaddress_full', $regaddress_full);

            $faktaddress = $this->Addresses->get_address($user->faktaddress_id);
            $faktaddress_full = $faktaddress->adressfull;

            $this->design->assign('faktaddress_full', $faktaddress_full);

            $contract = $this->contracts->get_contract($document->contract_id);

            $cards = $this->cards->get_cards(['user_id' => $contract->user_id]);
            $active_card = '';

            if (!empty($cards)) {
                foreach ($cards as $card) {
                    if ($card->base_card == 1)
                        $active_card = $card->pan;
                }
                $this->design->assign('active_card', $active_card);
            }

            $amount = OperationsORM::where('type', 'P2P')->where('order_id', $document->order_id)->first();
            $amount = $amount->amount;

            $this->design->assign('amount', $amount);
            $this->design->assign('contract', $contract);
            $this->design->assign('accept_sms', $order->accept_sms);

        }


        $query = $this->db->placehold("
        SELECT * 
        FROM __sms_messages
        WHERE phone=?
        AND message like '%$contract->accept_code%';  
        ",$document->params['phone']);

        $this->db->query($query);
        $sms_sent_date = $this->db->results();
        $this->design->assign('sms_sent_date', $sms_sent_date[0]->created);


        $order = $this->orders->get_order($document->order_id);
        $contract = $this->contracts->get_contract($order->contract_id);
        $service_insurance = $contract->service_insurance;
        $this->design->assign('service_insurance', $service_insurance);
        // $insurance = $this->request->get('insurance');

        $service_sms = $contract->service_sms;
        $this->design->assign('service_sms', $service_sms);

        $income = $user->income;
        $this->design->assign('income', $income);
        $expenses = $user->expenses;
        $this->design->assign('expenses', $expenses);

        $sms = 0;
        $transactions = $this->transactions->get_transactions(array('user_id' => $order->user_id));
        if($contract->service_sms){
            $sms = $transactions[0]->amount / 100;
        }

        if (!empty($insurance) || isset($contract) && !empty($contract->service_insurance)) {

            $operation = OperationsORM::where('type', 'INSURANCE')->where('order_id', $document->order_id)->first();
            $insurance = (float)$operation->amount;
            
            if (empty($insurance)) {
                if (empty($contract)) {
                    $order = $this->orders->get_order($document->order_id);
                    $amount = $order->amount;
                    $insurance = $order->amount * 0.1;
                }
                else{
                    $amount = $contract->amount;
                    $insurance = $contract->amount * 0.1;
                }
            }

            if ($amount <= 10000)
                $insuranceSum = 10000;
            elseif ($amount >= 10001 && $amount <= 20000)
                $insuranceSum = 20000;
            elseif ($amount >= 20000)
                $insuranceSum = 30000;

            $contract->amount += $insurance;

            $this->design->assign('insurance', $insurance);
            $this->design->assign('insuranceSum', $insuranceSum);
        }else{
            $order = $this->orders->get_order($document->order_id);
            $amount = $order->amount;
        }

        $amount = OperationsORM::where('type', 'P2P')->where('order_id', $document->order_id)->first();
        $amount = $amount->amount;

        $this->design->assign('sms', $sms);

        $this->design->assign('amount', $amount);

        $tpl = $this->design->fetch('pdf/' . $document->template);

        $this->pdf->create($tpl, $document->name, $document->template);

    }

    private function format_document($content, $document_name)
    {

//return $content;
        require_once $this->config->root_dir . 'phpquery-onefile.php';

        $dom = phpQuery::newDocument($content);

        $spans = $dom->find('*')->attr('style', '');

        if ($document_name == 'Условия договора микрозайма' || $document_name == 'Соглашение о продлении займа') {
            $div = $dom->find('div:first');

            $new_div = '<div><img width="140" src="' . $div->find('img:first')->attr('src') . '"/></div><br /><br />';
            $new_div .= '<table width="540" border="1" cellpading="2">';
            $new_div .= '<tr>';
            $new_div .= '<td width="180" align="center"><div> </div><img width="120" src="' . $div->find('.qr-code')->attr('src') . '"/></td>';
            $new_div .= '<td style="font-size:90%;" width="180" align="center"><br /><br />' . $div->find('div>.psk-info:last')->html() . '<br /></td>';
            $new_div .= '<td style="font-size:90%;" width="180" align="center"><br /><br />' . $div->find('div>.psk-info:first')->html() . '<br /></td>';
            $new_div .= '</tr>';
            $new_div .= '</table><br /><br />';

            $div->replaceWith($new_div);
            //echo __FILE__.' '.__LINE__.'<br /><pre>';echo(htmlspecialchars($div));echo '</pre><hr />';
        }

        $content = $dom->html();

        phpQuery::unloadDocuments();

        $replace = array(
            'https://storage.yandexcloud.net/creditapi/sandbox/4f355b1ae27aeec676ea6ca5bca10042_logoza-ru-hd.png'
            => '/theme/site/html/pdf/i/doc_logo.png',
            'https://sbapi.creditapi.ru/api/files/CREDITAPIDEV/c6707cbdf913f86dba213f614cb9a76a_logoza-ru-hd.png'
            => '/theme/site/html/pdf/i/doc_logo.png',
            'https://clients.oss.nodechef.com/checkbox_on.png'
            => '/theme/site/html/pdf/i/checkbox_on.png',
            'https://clients.oss.nodechef.com/checkbox_off.png'
            => '/theme/site/html/pdf/i/checkbox_off.png',
            'https://storage.yandexcloud.net/creditapi/clients/checkbox_off.png'
            => '/theme/site/html/pdf/i/checkbox_off.png',
            'https://storage.yandexcloud.net/creditapi/clients/checkbox_on.png'
            => '/theme/site/html/pdf/i/checkbox_on.png',
            'https://storage.yandexcloud.net/creditapi/clients/VSK_stamp1.png'
            => '/theme/site/html/pdf/i/polis_stamp.png',
            'https://storage.yandexcloud.net/creditapi/clients/Page_QR.png'
            => '/theme/site/html/pdf/i/contract_qr.png',
            'https://clients.oss.nodechef.com/Page_QR.png'
            => '/theme/site/html/pdf/i/contract_qr.png',
        );

        $content = str_replace(array_keys($replace), array_values($replace), $content);

        return $content;
    }

    private function action_preview()
    {
        $type = $this->request->get('type');
        $type = strtoupper(str_replace('.pdf', '', $type));
        if (empty($type))
            return false;

        if (!($template = $this->documents->get_template($type)))
            return false;

        if (!($template_name = $this->documents->get_template_name($type)))
            return false;

        if (!($contract_id = $this->request->get('contract_id', 'integer')))
            return false;

        if (!($contract = $this->contracts->get_contract($contract_id)))
            return false;


        $ob_date = new DateTime();
        $ob_date->add(DateInterval::createFromDateString($contract->period . ' days'));
        $return_date = $ob_date->format('Y-m-d H:i:s');

        $return_amount = round($contract->amount + $contract->amount * $contract->base_percent * $contract->period / 100, 2);
        $return_amount_rouble = (int)$return_amount;
        $return_amount_kop = ($return_amount - $return_amount_rouble) * 100;

        $contract_order = $this->orders->get_order((int)$contract->order_id);

        $insurance_cost = $this->insurances->get_insurance_cost($contract->amount);

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
        );

        $user = $this->users->get_user($contract_order->user_id);

        $regaddress = $this->Addresses->get_address($user->regaddress_id);
        $regaddress_full = $regaddress->adressfull;

        $params['regaddress_full'] = $regaddress_full;


        if ($type == 'POLIS') {
            $insurance = new StdClass();

            $insurance->create_date = date('Y-m-d H:i:s');
            $insurance->amount = round($insurance_cost, 2);
            $insurance->start_date = date('Y-m-d 00:00:00', time() + (1 * 86400));
            $insurance->end_date = date('Y-m-d 23:59:59', time() + (31 * 86400));

            $params['insurance'] = $insurance;
        }

        foreach ($params as $param_name => $param_value)
            $this->design->assign($param_name, $param_value);

        $tpl = $this->design->fetch('pdf/' . $template);

        $this->pdf->create($tpl, $template_name, $template);

    }

}