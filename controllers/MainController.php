<?php

error_reporting(0);
ini_set('display_errors', 'Off');
ini_set('html_errors', 'Off');
class MainController extends Controller
{

	function fetch()
	{
        if (isset($this->user->contract) && $this->user->contract->status == 6)
        {
            header('Location: '.$this->config->root_url.'/account');
            exit;
        }

        if ($amount = $this->request->get('amount'))
            $default_summ = $amount;
        else
            if (empty($_COOKIE['loan_summ']))
                $default_summ = $this->settings->loan_default_summ;
            else
                $default_summ = $_COOKIE['loan_summ'];

        if ($period = $this->request->get('period'))
            $default_period = $period;
        else
            if (empty($_COOKIE['loan_period']))
                $default_period = $this->settings->loan_default_period;
            else
                $default_period = $_COOKIE['loan_period'];


        $documents = $this->documents->get_documents(array('user_id' => $this->user->id, 'client_visible'=>1));
        $this->design->assign('documents', $documents);

        $min_summ = $this->settings->loan_min_summ;
		$max_summ = $this->settings->loan_max_summ;
        $min_period = $this->settings->loan_min_period;
        $max_period = $this->settings->loan_max_period;
        $current_summ = $default_summ;
        $current_period = $default_period;
        $loan_percent = $this->settings->loan_default_percent;

        $this->design->assign('min_summ', $min_summ);
        $this->design->assign('max_summ', $max_summ);
        $this->design->assign('min_period', $min_period);
        $this->design->assign('max_period', $max_period);
        $this->design->assign('current_summ', $current_summ);
        $this->design->assign('current_period', $current_period);
        $this->design->assign('loan_percent', $loan_percent);

        if ($this->request->method('post'))
        {
            $amount = $this->request->post('amount', 'string');
            $period = $this->request->post('period', 'string');
            $phone = $this->request->post('phone', 'string');
            $code = $this->request->post('code', 'string');

            $service_insurance = $this->request->post('service_insurance', 'integer');
            $service_reason = $this->request->post('service_reason', 'integer');

            $phone = $this->sms->clear_phone($phone);

            $db_code = $this->sms->get_code($phone);

            if ($db_code != $code)
            {
                $this->design->assign('message_error', 'Код из СМС не совпадает');
            }
            elseif ($this->users->get_phone_user($phone))
            {
                $this->design->assign('message_error', 'Клиент с номером телефона '.$phone.' уже зарегистрирован');
            }
            // проверить сумму и срок кредита
            else
            {
                $user = array(
                    'first_loan_amount' => $amount,
                    'first_loan_period' => $period,
                    'phone_mobile' => $phone,
                    'sms' => $code,
                    'service_reason' => $service_reason,
                    'service_insurance' => $service_insurance,
                    'service_sms' => 1,
                    'reg_ip' => $_SERVER['REMOTE_ADDR'],
                    'last_ip' => $_SERVER['REMOTE_ADDR'],
                    'enabled' => 1,
                    'created' => date('Y-m-d H:i:s'),
                );

                $user_id = $this->users->add_user($user);

                $_SESSION['user_id'] = $user_id;

                setcookie('loan_amount', null);
                setcookie('loan_period', null);

                header('Location: /stage/personal');
                exit;
            }
        }

        if($this->page)
		{
			$this->design->assign('meta_title', $this->page->meta_title);
			$this->design->assign('meta_keywords', $this->page->meta_keywords);
			$this->design->assign('meta_description', $this->page->meta_description);
		}

		return $this->design->fetch('main.tpl');
	}
}
