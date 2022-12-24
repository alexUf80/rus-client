<?php

/*
$salt = 'v1XY4b9nM51J';

$date = date('Ymd');
$phone = '79272604676';
$uid = '5c9e8941-b7d3-11ea-80d3-ac1f6b7d528f';
$ip = $_SERVER['REMOTE_ADDR']; // 94.154.39.244

$hash = md5(md5($date.$phone.$uid.$ip).$salt);


$link = 'https://nalichnoeplus.com/import?p='.$phone.'&u='.$uid.'&h='.$hash;
echo $link;

// результат
//'https://nalichnoeplus.com/import?p=79272604676&u=5c9e8941-b7d3-11ea-80d3-ac1f6b7d528f&h=ee03015deb1b1460b5153f53b88438ce';
*/


class UserImportController extends Controller
{
    private $salt = 'v1XY4b9nM51J';
    
    public function fetch()
    {
    	$phone = $this->request->get('p');
        $uid = $this->request->get('u');
        $hash = $this->request->get('h');
        
        if ($this->check_hash($phone, $uid, $hash))
        {
            // проверяем есть ли в базе клиент с таким номером
            if ($user_id = $this->users->get_phone_user($this->sms->clear_phone($phone)))
            {
                $user = $this->users->get_user((int)$user_id);
                if ($user->UID == $uid)
                {
                    $_SESSION['user_id'] = $user_id;
                        
                    $this->users->update_user($user_id, array('last_ip' => $_SERVER['REMOTE_ADDR']));
                        
                    if (empty($this->is_developer))
                    {
                        $this->authorizations->add_authorization(array(
                            'user_id' => $user_id,
                            'ip' => $_SERVER['REMOTE_ADDR'],
                            'created' => date('Y-m-d H:i:s'),
                            'user_agent' => $_SERVER['HTTP_USER_AGENT']
                        ));
                    }
                        
                    // проверяем есть ли открытый займ
                    $balance = $this->soap1c->get_user_balance($resp_1c->uid);
                    $this->import1c->import_balance($user_id, $balance);

                    // сохраняем историю займов из 1с
                    $credits_history = $this->soap1c->get_client_credits($resp_1c->uid);
                    $this->users->save_loan_history($user_id, $credits_history);
                    
                    header('Location: /account');
                    exit;
                }
                else
                {
                    $_SESSION['splash'] = 'Параметры ссылки не совпадают!';
                    
                    header('Location: /lk/login');
                    exit;
                }
            }
            else
            {
                $resp_1c = $this->soap1c->get_uid_by_phone($this->soap1c->format_phone($phone));
                if ($resp_1c->error == 'Множество совпадений')
                {
                    $_SESSION['splash'] = 'По номеру телефона '.$phone.' слишком много совпадений. Свяжитесь с контактным центром.';

                    header('Location: /lk/login');
                    exit;
                }
                elseif ($resp_1c->error == 'Не найден телефон')
                {
                    $_SESSION['splash'] = 'Клиент с таким номером телефона в базе не найден.';

                    header('Location: /lk/login');
                    exit;                    
                }
                elseif (!empty($resp_1c->uid))
                {
                    // клиент есть в 1с но нет в базе, сохраняем его в базе
                    $user_details = $this->soap1c->get_client_details($resp_1c->uid);                        
                    
                    $birth = date('d.m.Y', strtotime(str_replace('.', '-', $user_details->ДатаРожденияПоПаспорту)));
                    $lastname = $user_details->Фамилия;
                    $firstname = $user_details->Имя;
                    $patronymic = $user_details->Отчество;
                    $passport_serial = $user_details->ПаспортСерия.'-'.$user_details->ПаспортНомер;
                    
                    $found_id = $this->users->find_clone($passport_serial, $lastname, $firstname, $patronymic, $birth);
                    if (!empty($found_id))
                    {
                        $_SESSION['splash'] = 'Внимание, пользователь с такими данными уже существует. Свяжитесь с контактным центром.';
    
                        header('Location: /lk/login');
                        exit;
                        
                    }
                    elseif ($user_id = $this->import1c->import_user($resp_1c->uid, $user_details, $code))
                    {
                        $client_images = $this->soap1c->get_client_images($resp_1c->uid);
                        $this->import1c->import_images($user_id, $client_images);
                        
                        
                        $this->users->update_user($user_id, array('last_ip' => $_SERVER['REMOTE_ADDR']));
                        
                        // проверяем есть ли открытый займ
                        $balance = $this->soap1c->get_user_balance($resp_1c->uid);
                        $this->import1c->import_balance($user_id, $balance);

                        // сохраняем историю займов из 1с
                        $credits_history = $this->soap1c->get_client_credits($resp_1c->uid);
                        $this->users->save_loan_history($user_id, $credits_history);
                        
                        $_SESSION['user_id'] = $user_id;

                        if (empty($this->is_developer))
                        {
                            $this->authorizations->add_authorization(array(
                                'user_id' => $user_id,
                                'ip' => $_SERVER['REMOTE_ADDR'],
                                'created' => date('Y-m-d H:i:s'),
                                'user_agent' => $_SERVER['HTTP_USER_AGENT']
                            ));
                        }
                        
                        header('Location: /account');
                        exit;                                            

                    }
                    else
                    {
                        $_SESSION['splash'] = 'Произошла непредвиденная ошибка.';
    
                        header('Location: /lk/login');
                        exit;                    
                    }
                    
                }
            }

        }
        else
        {
            $_SESSION['splash'] = 'Ссылка неправильная или устарела!';
            
            header('Location: /lk/login');
            exit;
        }
    }
    
    private function check_hash($phone, $uid, $hash)
    {
        $date = date('Ymd');
        $ip = $_SERVER['REMOTE_ADDR'];
        
        return $hash == md5(md5($date.$phone.$uid.$ip).$this->salt);
        
        
    }
}