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

        if (!empty($document->params)) {

            $document->params = json_decode($document->params, true);

            if(in_array($document->type, ['DOP_RESTRUCT', 'GRAPH_RESTRUCT']))
                $document->params['schedules']['payment_schedules'] = json_decode($document->params['schedules']['payment_schedules'], true);

            foreach ($document->params as $param_name => $param_value)
            {
                if($param_name == 'insurance')
                    $this->design->assign('insurances', (object)$param_value);
                if($param_name == 'contract')
                    $this->design->assign('insurances', (object)$param_value['insurance']);
                else
                    $this->design->assign($param_name, $param_value);
            }

            foreach ($user as $key => $value)
                $this->design->assign($key, $value);

            $regaddress = $this->Addresses->get_address($user->regaddress_id);
            $regaddress_full = $regaddress->adressfull;

            $this->design->assign('regaddress_full', $regaddress_full);

            $contract = $this->contracts->get_contract($document->contract_id);

            $cards = $this->cards->get_cards(['user_id' => $contract->user_id]);
            $active_card = '';

            if (!empty($cards)) {
                foreach ($cards as $card) {
                    if($card->base_card == 1)
                        $active_card = $card->pan;
                }
                $this->design->assign('active_card', $active_card);
            }

            $this->design->assign('contract', $contract);

        }

        $insurance = $this->request->get('insurance');

        if (!empty($insurance) || isset($contract) && !empty($contract->service_insurance)) {
            if ($contract->amount <= 10000)
            {
                $insurance = 390;
                $insuranceSum = 10000;
                $contract->amount += $insurance;
            }
            elseif ($contract->amount >= 10001 && $contract->amount <= 20000)
            {
                $insurance = 490;
                $insuranceSum = 20000;
                $contract->amount += $insurance;
            }
            elseif ($contract->amount >= 20000)
            {
                $insurance = 590;
                $insuranceSum = 30000;
                $contract->amount += $insurance;
            }

            $this->design->assign('insurance', $insurance);
            $this->design->assign('insuranceSum', $insuranceSum);


        }

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