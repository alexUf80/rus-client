<?php

class StageWorkController extends Controller
{
    public function fetch()
    {
        if (empty($this->user))
        {
            header('Location: /lk/login');
            exit;
        }

        if (!empty($this->user->stage_work))
        {
            header('Location: /stage/files');
            exit;
        }

        if (empty($this->user->stage_address))
        {
            header('Location: /stage/address');
            exit;
        }

        if ($this->request->get('step') == 'prev')
        {
            $this->users->update_user($this->user->id, array('stage_address'=>0));
            header('Location: /stage/address');
            exit;
        }

        if ($this->request->method('post'))
        {
            $workplace = (string)$this->request->post('workplace');
            $workaddress = (string)$this->request->post('workaddress');
            $profession = (string)$this->request->post('profession');
            $workphone = (string)$this->request->post('workphone');
            $income = (string)$this->request->post('income');
            $expenses = (string)$this->request->post('expenses');

            $average_pay = (string)$this->request->post('average_pay');
            $amount_pay = (string)$this->request->post('amount_pay');
            $juicescore_session_id = (string)$this->request->post('juicescore_session_id');

            $this->design->assign('workplace', $workplace);
            $this->design->assign('profession', $profession);
            $this->design->assign('workphone', $workphone);
            $this->design->assign('workaddress', $workaddress);
            $this->design->assign('income', $income);
            $this->design->assign('expenses', $expenses);
            $this->design->assign('average_pay', $average_pay);
            $this->design->assign('amount_pay', $amount_pay);

            $errors = array();

            if (empty($workplace))
                $errors[] = 'empty_workplace';
            if (empty($profession))
                $errors[] = 'empty_profession';
            if (empty($workphone))
                $errors[] = 'empty_workphone';
            if (empty($income))
                $errors[] = 'empty_income';
            if (empty($expenses))
                $errors[] = 'empty_expenses';
/***
            if (empty($contact_person2_name))
                $errors[] = 'empty_contact_person2_name';
            if (empty($contact_person2_phone))
                $errors[] = 'empty_contact_person2_phone';
***/
            $this->design->assign('errors', $errors);

            //Расчет ПДН
            if ($average_pay > 0) {
                $a = preg_replace("/[^0-9]/", "", $average_pay);
                $b = preg_replace("/[^0-9]/", "", $income);

                if ($income < 1 || is_null($income)) {
                    $b = 75000;
                }

                $pdn = $a / $b;
            } else {
                //$pdn = 0;

                $a = preg_replace("/[^0-9]/", "", $this->user->first_loan_amount);
                $b = preg_replace("/[^0-9]/", "", $income);

                if ($income < 1 || is_null($income)) {
                    $b = 75000;
                }

                $pdn = $a / $b;
            }

            if (empty($errors))
            {
                $update = array(
                    'workplace' => $workplace,
                    'workaddress' => $workaddress,
                    'profession' => $profession,
                    'workphone' => $workphone,
                    'income' => $income,
                    'expenses' => $expenses,
                    'average_pay' => $average_pay,
                    'amount_pay' => $amount_pay,
                    'pdn' => $pdn,
                    'stage_work' => 1,
                    'juicescore_session_id' => $juicescore_session_id,
                );

                $update = array_map('strip_tags', $update);
                $this->users->update_user($this->user->id, $update);

                header('Location: /stage/files');
                exit;
            }
        }
        else
        {
            $this->design->assign('workplace', $this->user->workplace);
            $this->design->assign('workaddress', $this->user->workaddress);
            $this->design->assign('profession', $this->user->profession);
            $this->design->assign('workphone', $this->user->workphone);
            $this->design->assign('income', $this->user->income);
            $this->design->assign('expenses', $this->user->expenses);

        }

    	return $this->design->fetch('stage/work.tpl');
    }

}