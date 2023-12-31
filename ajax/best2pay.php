<?php
error_reporting(-1);
ini_set('display_errors', 'On');

chdir('..');

require 'autoload.php';

class BestPayAjax extends Ajax
{
    public function run()
    {
        $action = $this->request->get('action', 'string');
        
        switch($action):
            
            case 'attach_card':
                $this->attach_card();
            break;
            
            case 'get_payment_link':
                $this->get_payment_link();
            break;
            
            case 'recurrent':
                $this->recurrent_action();                
            break;

            case 'recurrent_close':
                $this->recurrent_close();
                break;

            case 'get_payment_link_to_kd':
                $this->get_payment_link_to_kd();
                break;
            
            default:
                $this->response['error'] = 'UNDEFINED_ACTION';
            
        endswitch;
        
        $this->output();
    }
    
    private function recurrent_action()
    {
        if (!empty($_SESSION['looker_mode']))
            return false;

        $card_id = $this->request->get('card_id', 'integer');
        $amount = (float)str_replace(',', '.', $this->request->get('amount'));
        $contract_id = $this->request->get('contract_id', 'integer');
        $prolongation = $this->request->get('prolongation', 'integer');

        if (empty($amount))
        {
            $this->response['error'] = 'EMPTY_AMOUNT';
        }
        elseif (empty($card_id))
        {
            $this->response['error'] = 'EMPTY_CARD';
        }
        elseif (!($card = $this->cards->get_card($card_id)))
        {
            $this->response['error'] = 'UNDEFINED_CARD';
        }
        else
        {
            $description = "Оплата по договору ".$contract_id;
            $amount = $amount * 100;
            $response = $this->BestPay->recurrent_pay($card->id, $amount, $description, $contract_id, $prolongation);
            if (empty($response))
                $this->response['error'] = $response;
            else
                $this->response['success'] = 1;
        }
    }

    private function recurrent_close()
    {
        if (!empty($_SESSION['looker_mode']))
            return false;

        $card_id = $this->request->get('card_id', 'integer');
        $amount = (float)str_replace(',', '.', $this->request->get('amount'));
        $contract_id = $this->request->get('contract_id', 'integer');
        $contract = $this->contracts->get_contract($contract_id);

        if (empty($amount))
        {
            $this->response['error'] = 'EMPTY_AMOUNT';
        }
        elseif (empty($card_id))
        {
            $this->response['error'] = 'EMPTY_CARD';
        }
        elseif (!($card = $this->cards->get_card($card_id)))
        {
            $this->response['error'] = 'UNDEFINED_CARD';
        }
        else
        {
            $description = "Оплата по договору ".$contract->number;
            $amount = $amount * 100;
            $response = $this->BestPay->purchase_by_token($card->id, $amount, $description, $contract_id);

            if (empty($response))
                $this->response['error'] = $response;
            else
                $this->response['success'] = 1;
        }
    }
    
    private function get_payment_link()
    {
        if (!empty($_SESSION['looker_mode']))
            return false;
        
        $amount = (float)str_replace(',', '.', $this->request->get('amount'));
        $contract_id = $this->request->get('contract_id', 'integer');
        $prolongation = $this->request->get('prolongation', 'integer');
        $sms = $this->request->get('code_sms', 'string');
        $card_id = $this->request->get('card_id', 'integer');
        
        if (empty($amount))
        {
            $this->response['error'] = 'EMPTY_AMOUNT';
        }
        else
        {
            $amount = $amount * 100;
            $this->response['link'] = $this->BestPay->get_payment_link($amount, $contract_id, $prolongation, $card_id, $sms);
        }
    }

    private function get_payment_link_to_kd()
    {
        if (!empty($_SESSION['looker_mode']))
            return false;
        
        $amount = (float)str_replace(',', '.', $this->request->get('amount'));
        $user_id = $this->request->get('user_id', 'integer');
        
        if (empty($amount))
        {
            $this->response['error'] = 'EMPTY_AMOUNT';
        }
        else
        {
            $amount = $amount * 100;
            $this->response['amount'] = $amount;
            $this->response['user_id'] = $user_id;
            $this->response['link'] = $this->BestPay->get_payment_link_to_kd($amount, $user_id);
        }
    }
    
    private function attach_card()
    {
        if (!empty($_SESSION['looker_mode']))
            return false;
        
        $this->response['user_id'] = $this->user->id;
    	$this->response['link'] = $this->BestPay->add_card_enroll($this->user->id);
    }
    
}
$ajax = new BestPayAjax();
$ajax->run();