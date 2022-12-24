<?php

class ContractAcceptController extends Controller
{
    public function fetch()
    {
        $id = $this->request->get('id', 'integer');
        
        if (!($contract = $this->contracts->get_contract($id)))
            return false;
        
        if ($contract->status > 0)
            return false;
        
        if ($this->user->id != $contract->user_id)
            return false;
        
        if ($this->request->method('post'))
        {
            $phone = $this->request->post('phone');
            $accept_code = $this->request->post('accept_code', 'integer');
            $service_insurance = $this->request->post('service_insurance', 'integer');
            $service_reason = $this->request->post('service_reason', 'integer');
            
            if ($contract->status != 0)
            {
                $this->design->assign('message_error', 'Договор не находится в статусе Новый');
            }
            elseif ($contract->accept_code != $accept_code)
            {
                $this->design->assign('message_error', 'Код из СМС не совпадает');
            }
            else
            {
                $this->contracts->update_contract($id, array(
                    'status' => 1,
                    'service_reason' => $service_reason,
                    'service_insurance' => $service_insurance,
                    'accept_date' => date('Y-m-d H:i:s'),
                    'accept_ip' => $_SERVER['REMOTE_ADDR']
                ));
                
                $this->orders->update_order($contract->order_id, array('status' => 4));
            }
        }
        
        $this->design->assign('contract', $contract);
        
    	return $this->design->fetch('contract_accept.tpl');
    }
}