<?php

class C2oPaymentController extends Controller
{
    
    public function fetch()
    {
        if (!($code = $this->request->get('code')))
            return false;

        if (!($id = $this->helpers->c2o_decode($code)))
            return false;

        if(!($contract = $this->contracts->get_contract($id)))
            return false;
        
        $order = $this->orders->get_order($contract->order_id);
        $this->design->assign('order', $order);
        
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