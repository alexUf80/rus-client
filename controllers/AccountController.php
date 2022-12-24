<?php


class AccountController extends Controller
{


    public function fetch()
    {
        if (empty($this->user)) {
            header('Location: /lk/login');
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

        // подача повторной заявки
        if ($this->request->method('post')) {
            if (!empty($_SESSION['looker_mode']))
                return false;

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
                    'service_sms' => 1,
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
        $documents = $this->documents->get_documents(array('user_id' => $this->user->id, 'client_visible'=>1));
        $this->design->assign('documents', $documents);

        if ($active_contract = $this->contracts->find_active_contracts($this->user->id)) {
            $order = $this->orders->get_order((int)$active_contract->order_id);

        } else {
            $orders = $this->orders->get_orders(array('user_id' => $this->user->id, 'sort' => 'date_desc'));

            $order = reset($orders);

        }

        if (!empty($order)) {

            $order = $this->orders->get_order($order->order_id);

            if($order->loantype_id != 0)
            {
                $loantype = $this->Loantypes->get_loantype($order->loantype_id);
                $stdPercent = $loantype->percent/100;
            }else
                $stdPercent = 0.01;


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


            $prolongation_amount = 0;
            if (empty($order->contract->stop_profit)) {
                if (empty($order->contract->hide_prolongation)) {
                    if ($order->contract->type == 'base' && ($order->contract->status == 2 || $order->contract->status == 4)) // выдан
                    {
                        if ($order->contract->prolongation < 5 || ($order->contract->prolongation >= 5 && $order->contract->sold)) {
                            if ($order->contract->loan_percents_summ > 0) {
                                $prolongation_amount = $order->contract->loan_percents_summ;
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

                if($now_date > $card_exp){
                    $last_four_digits = substr($card->pan, -4);
                    $warning_card .= "Пожалуйста, замените карту *$last_four_digits. Она не активна, мы не сможем зачислить займ<br>";
                }

            }

            $this->design->assign('warning_card', $warning_card);
            return $this->design->fetch('account/home.tpl');
        }
    }

}