<?php

class AccountPayController extends Controller
{
    public function fetch()
    {
        if(empty($this->user))
		{
			header('Location: '.$this->config->root_url.'/lk/login');
			exit();
		}

        $t_2350 = strtotime(date('Y-m-d 23:50:00'));
        $t_2359 = strtotime(date('Y-m-d 23:59:59'));

        if (time() >= $t_2350 && time() < $t_2359)
        {
            $this->design->assign('error_time', 1);
        }
        elseif ($this->request->method('post'))
        {
            // пролонгация
            $prolongation = $this->request->post('prolongation', 'integer');
            $code_sms = $this->request->post('code', 'string');
            $this->design->assign('prolongation', $prolongation);
            $this->design->assign('code_sms', $code_sms);

            $amount = str_replace(',', '.', $this->request->post('amount'));

            $contract_id = $this->request->post('contract_id', 'integer');

            $user_balance_id = $this->request->post('user_balance_id', 'integer');
            $this->design->assign('contract_id', $contract_id);
            if (!empty($user_balance_id))
            {
                if (!($user_balance = $this->balances->get_balance($user_balance_id)))
                    return false;

                $max_payment = $user_balance->loan_body_summ + $user_balance->loan_percents_summ + $user_balance->loan_peni_summ + $user_balance->loan_charge_summ;
            }
            else
            {

                if (!($contract = $this->contracts->get_contract($contract_id)))
                    return false;

                if ($contract->user_id != $this->user->id)
                    return false;

                $max_payment = $contract->loan_body_summ + $contract->loan_percents_summ + $contract->loan_peni_summ + $contract->loan_charge_summ;
            }


            $prolongation_amount = 0;
            if ($contract->type == 'base' && ($contract->status == 2 || $contract->status == 4)) // выдан
            {
                if ($contract->prolongation < 5)
                {
                    // сделать проверку что бы в тот же день не было видно пролонгации
                    $prolongation_amount = $amount;
                }
            }

            if (!empty($prolongation) && ($prolongation_amount > $amount))
            {
                $this->design->assign('error', 'Минимальная сумма для пролонгации: '.$prolongation_amount.' руб');
                $amount = $prolongation_amount;
            }
            elseif ($amount > $max_payment)
            {
                $this->design->assign('error', 'Максимальная сумма к оплате: '.$max_payment.' руб');
                $amount = $max_payment;
            }
            elseif ($max_payment != $amount && ($max_payment - $amount) < 1)
            {
                $this->design->assign('error', 'нельзя оставлять долг меньше 1 руб');
                $amount = $max_payment;
            }

            $this->design->assign('amount', $amount);
            $this->design->assign('prolongation_amount', $prolongation_amount);
            $this->design->assign('prolongation', $prolongation);


            $card_list = $this->cards->get_cards(array('user_id' => $this->user->id));

            $cards = array();
            if($card_list)
            {
    			foreach($card_list as $card)
                {
                    $cards[] = $card;
                }
    		}
            $this->design->assign('cards', $cards);

        }
        else
        {
            return false;
        }

        $order_id = $this->contracts->get_contract($contract_id);
        $order_id = $order_id->order_id;

        $this->design->assign('user_id', ($this->user->id));

        $full_amount = $contract->loan_body_summ + $contract->loan_percents_summ + $contract->loan_peni_summ + $contract->loan_charge_summ;
        $this->design->assign('full_amount', $full_amount);
        $this->design->assign('contract', $contract);

        $this->design->assign('order_id', $order_id);

        return $this->design->fetch('account/payment.tpl');
    }
}