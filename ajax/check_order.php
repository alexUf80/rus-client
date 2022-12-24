<?php

chdir('..');

require 'autoload.php';

class CheckOrderAjax extends Ajax
{
    public function run()
    {
        $status = $this->request->get('status', 'integer');
        if ($order_id = $this->request->get('order_id', 'integer'))
        {
            if ($order = $this->orders->get_order($order_id))
            {
                if ($order->user_id == $this->user->id)
                {
                    $this->response['success'] = 1;
                    $this->response['status'] = $order->status;
                    $this->response['reload'] = 0;
                    
                    if ($status != $order->status)
                        $this->response['reload'] = 1;
                    if (empty($this->user->stage_files))
                        $this->response['reload'] = 1;
                    
//$this->response['reload'] = $order->status > 1 || empty($this->user->stage_files);
                    
                }
                else
                {
                    $this->response['error'] = 'access_denied';
                }
            }
            else
            {
                $this->response['error'] = 'undefined_order';
            }
        }
        else
        {
            $this->response['error'] = 'empty_order_id';
        }
        
        $this->output();
    }
    

}
$ajax = new CheckOrderAjax();
$ajax->run();