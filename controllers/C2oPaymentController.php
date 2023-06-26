<?php

class C2oPaymentController extends Controller
{
    
    public function fetch()
    {
        if (!($code = $this->request->get('code')))
            return false;

            // $file = 'c:\OSPanel\people.txt';
            // file_put_contents($file, $code);
        if (!($id = $this->helpers->c2o_decode($code)))
            return false;

        if(!($contract = $this->contracts->get_contract($id)))
            return false;
        
        $order = $this->orders->get_order($contract->order_id);
        $this->design->assign('order', $order);


        $show_prolongation = false;
        $date1 = new DateTime(date('Y-m-d', strtotime($contract->inssuance_date)));
        $date2 = new DateTime(date('Y-m-d'));
        $date3 = new DateTime(date('Y-m-d', strtotime($contract->return_date)));

        $currenDate = date('Y-m-d', time());

        $pro_date = $contract->return_date;


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

        $prolongation_amount = 0;
        if (empty($contract->stop_profit)) {
            if (empty($contract->hide_prolongation)) {
                if ($contract->type == 'base' && ($contract->status == 2 || $contract->status == 4)) // выдан
                {
                    if ($contract->prolongation < 5 || ($contract->prolongation >= 5 && $contract->sold)) {
                        if ($contract->loan_percents_summ > 0) {
                            if ($percents_sum < $contract->amount * 1.5) {
                                $prolongation_amount = $contract->loan_percents_summ + $this->settings->prolongation_amount;
                            }
                        }
                    }
                }
            }
        }

        if (date_diff($date2, $date3)->days <= 3 || $date2 > $date3)
            $this->design->assign('prolongation_amount', $prolongation_amount);

        $this->design->assign('show_prolongation', $show_prolongation);
        $this->design->assign('prolongation_amount', $prolongation_amount);
        
        $this->design->assign('contract', $contract);
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($this->config->root_url, $this->config->cession_url);echo '</pre><hr />';
        
        if (empty($contract->sold) && $this->config->root_url != $this->config->front_url)
        {
            header('Location: '.$this->config->front_url.'/p/'.$code);
            exit;
        }
        if (!empty($contract->sold) && $this->config->root_url != $this->config->cession_url)
        {
            header('Location: '.$this->config->cession_url.'/p/'.$code);
            exit;
        }
        
        return $this->design->fetch('c2o_payment.tpl');
    }
    
}