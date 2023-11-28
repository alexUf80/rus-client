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
            
            case 'edit_services':
                $this->edit_services();
            break;
            
            default:
                $this->response['error'] = 'UNDEFINED_ACTION';
            
        endswitch;
        
        $this->output();
    }
    
    private function edit_services()
    {
        if (!empty($_SESSION['looker_mode']))
            return false;

        $contract_id = $this->request->get('contractId', 'integer');
        $code = $this->request->get('code', 'integer');

        $contract = $this->contracts->get_contract($contract_id);
        $user = $this->users->get_user($contract->user_id);

        if (!isset($contract)) {
            $this->response['error'] = 'WRONG_CONTRACT';
        }
        else{
            if ($contract->edit_services != $code) {
                $this->response['error'] = 'WRONG_CODE';
            }
            else{

                // Списать сумму допов (сначала с %, потом с ОД)
                $amount = 0;

                $refund_for_services = $this->RefundForServices->get($contract_id);
                $operations_str = $refund_for_services->operations_ids;
                $operations_arr = explode(',', $operations_str);
                
                $inssuance_amount = [];
                $sms_amount = [];
                foreach ($operations_arr as $operation_id) {
                    $operation = $this->operations->get_operation($operation_id);
                    if (in_array($operation->type, ['BUD_V_KURSE', 'INSURANCE', 'INSURANCE_BC'])) {
                        $amount += $operation->amount;
                    }
                    if (in_array($operation->type, ['INSURANCE', 'INSURANCE_BC'])) {
                        $inssuance_amount[] = $operation->amount;
                    }
                    if (in_array($operation->type, ['BUD_V_KURSE'])) {
                        $sms_amount[] = $operation->amount;
                    }
                }
                $operation_amount = $amount;

                $loan_peni_summ = $contract->loan_peni_summ;
                $loan_percents_summ = $contract->loan_percents_summ;
                $loan_body_summ = $contract->loan_body_summ;

                if ($amount >= $loan_peni_summ) {
                    $amount -= $loan_peni_summ;
                    $loan_peni_summ = 0;

                    if ($amount >= $loan_percents_summ) {
                        $amount -= $loan_percents_summ;
                        $loan_percents_summ = 0;

                        $loan_body_summ -= $amount;

                    } else {
                        $loan_percents_summ -= $amount;
                    }

                } else {
                    $loan_peni_summ -= $amount;
                }

                $upd_contract = $this->contracts->update_contract($contract_id, array(
                    'edit_services' => null,
                    'loan_body_summ' => $loan_body_summ,
                    'loan_percents_summ' => $loan_percents_summ,
                    'loan_peni_summ' => $loan_peni_summ,
                ));

                // Добавить операцию
                $this->operations->add_operation(array(
                    'contract_id' => $contract->id,
                    'user_id' => $contract->user_id,
                    'order_id' => $contract->order_id,
                    'type' => 'SERVICE_REFUND',
                    'amount' => $operation_amount,
                    'created' => date('Y-m-d H:i:s'),
                    'loan_body_summ' => $loan_body_summ,
                    'loan_percents_summ' => $loan_percents_summ,
                    'loan_peni_summ' => $loan_peni_summ,
                ));
    
                // Добавить документ
                $params = array(
                    'lastname' => $user->lastname,
                    'firstname' => $user->firstname,
                    'patronymic' => $user->patronymic,
                    'gender' => $user->gender,
                    'phone' => $user->phone_mobile,
                    'birth' => $user->birth,
                    'birth_place' => $user->birth_place,
                    'inn' => $user->inn,
                    'snils' => $user->snils,
                    'email' => $user->email,
                    'created' => $user->created,
    
                    'passport_serial' => $user->passport_serial,
                    'passport_date' => $user->passport_date,
                    'passport_code' => $user->subdivision_code,
                    'passport_issued' => $user->passport_issued,
                    'profession' => $user->profession,
                    'workplace' => $user->workplace,
                    'workphone' => $user->workphone,
                    'income' => $user->income,
                    'expenses' => $user->expenses,
    
                    'first_loan_amount' => $user->first_loan_amount,
                    'first_loan_period' => $user->first_loan_period,
    
                    'number' => $contract->number,
                    'create_date' => date('Y-m-d'),
                    'asp' => $code,
                    'accept_code' => $contract->accept_code,
                    'inssuance_date' => $contract->inssuance_date,
                    'inssuance_amount' => implode(",", $inssuance_amount),
                    'sms_amount' => implode(",", $sms_amount),
                    'refund_for_services_id' => $refund_for_services->id,
                );

                if (!empty($user->contact_person_name))
                {
                    $params['contactperson_phone'] = $user->contact_person_phone;
    
                    $contact_person_name = explode(' ', $user->contact_person_name);
                    $params['contactperson_name'] = $user->contact_person_name;
                    $params['contactperson_lastname'] = isset($contact_person_name[0]) ? $contact_person_name[0] : '';
                    $params['contactperson_firstname'] = isset($contact_person_name[1]) ? $contact_person_name[1] : '';
                    $params['contactperson_patronymic'] = isset($contact_person_name[2]) ? $contact_person_name[2] : '';
                }
                if (!empty($user->contact_person2_name))
                {
                    $params['contactperson2_phone'] = $user->contact_person_phone;
    
                    $contact_person2_name = explode(' ', $user->contact_person2_name);
                    $params['contactperson2_name'] = $user->contact_person2_name;
                    $params['contactperson2_lastname'] = isset($contact_person2_name[0]) ? $contact_person2_name[0] : '';
                    $params['contactperson2_firstname'] = isset($contact_person2_name[1]) ? $contact_person2_name[1] : '';
                    $params['contactperson2_patronymic'] = isset($contact_person2_name[2]) ? $contact_person2_name[2] : '';
                }
    
                $this->documents->create_document(array(
                    'user_id' => $user->id,
                    'order_id' => $contract->order_id,
                    'contract_id' => $contract->id,
                    'type' => 'SERVICE_FUNDS_REFUND',
                    'params' => json_encode($params),
                ));

                // Добавить флаг возврата услуг
                $operations_str = $this->RefundForServices->update_by_contract($contract_id,['done' => 1]);
    
                $this->response['success'] = $upd_contract;
            }
            
        }

    }
    
}
$ajax = new BestPayAjax();
$ajax->run();