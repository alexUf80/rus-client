<?php

class AccountResetPasswordController extends Controller
{
    public function fetch()
    {
    	if ($this->request->method('post'))
        {
            if ($phone = $this->request->post('phone'))
            {
                $clear_phone = $this->sms->clear_phone($phone);
                if ($user_id = $this->users->get_phone_user($clear_phone))
                {
                    $password = rand(00000,99999);
                    $this->users->update_user($user_id, array('password' => $password));
                    
                    $send_response = $this->sms->send($clear_phone, $password);
                    
                    $sms_message = array(
                        'code' => $password,
                        'phone' => $phone,
                        'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
                        'created' => date('Y-m-d H:i:s'),                                
                    );
                    $this->sms->add_message($sms_message);

                    
                    $this->design->assign('success', 'На указанный номер телефона отправлен временный пароль для входа в кабинет');
                }
                else
                {
                    $this->design->assign('message_error', 'Указанный телефон не найден');
                }
            }
            else
            {
                $this->design->assign('message_error', 'Укажите номер телефона который Вы использовали при регистрации');
            }
        }
        
        return $this->design->fetch('account/reset_password.tpl');
    }
    
}