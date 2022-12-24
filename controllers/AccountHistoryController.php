<?php

class AccountHistoryController extends Controller
{
    public function fetch()
    {
        if (empty($this->user))
        {
            header('Location: /lk/login');
            exit;
        }
        
        $history_items = array();

        $orders = $this->orders->get_orders(array('user_id'=>$this->user->id, 'sort' => 'id_desc'));
        foreach ($orders as $order)
        {
            if (!empty($order->contract_id))
                $order->contract = $this->contracts->get_contract($order->contract_id);
        }

        foreach ($history_items as $k => $history_item)
        {
            if (!empty($orders))
                foreach ($orders as $order)
                {
                    if (!empty($order->contract) && $history_item->number == $order->contract->number)
                        unset($history_items[$k]);
                }
        }

        $this->design->assign('orders', $orders);
    	
        $statuses = $this->orders->get_statuses();
        $this->design->assign('statuses', $statuses);
        
        $this->design->assign('history_items', $history_items);

        return $this->design->fetch('account/history.tpl');
    }
    
}