<?php

class LoginController extends Controller
{

    function fetch()
    {
        // Выход
        if ($this->request->get('action') == 'logout') {
            unset($_SESSION['user_id']);
            unset($_SESSION['looker_mode']);
            if ($this->config->root_url != $this->config->front_url) {
                header('Location: ' . $this->config->front_url . '/lk/logout');
                exit();
            } else {
                header('Location: ' . $this->config->root_url);
                exit();
            }
        } // Вход
        elseif ($this->request->method('post')) {
            $phone = $this->request->post('phone', 'string');
            $code = $this->request->post('code', 'string');
            $password = $this->request->post('password');

            $clean_phone = $this->sms->clear_phone($phone);


            if (!empty($code)) {
                $db_code = $this->sms->get_code($clean_phone);

                if ($db_code != $code)
                    $error = 'Код из СМС не совпадает';

            } else {
                $user_id = $this->users->check_password($clean_phone, $password);

                if (empty($user_id))
                    $error = 'Пароль не совпадает';
            }


            if (!isset($error)) {
                if ($user_id = $this->users->get_phone_user($clean_phone)) {
                    $_SESSION['user_id'] = $user_id;

                    header('Location: /account');
                    exit;
                } else {
                    $this->design->assign('message_error', 'Пользователь с номером телефона ' . $phone . ' не найден');
                }

            } else {
                $this->design->assign('message_error', $error);
            }
        }


        return $this->design->fetch('login.tpl');
    }
}
