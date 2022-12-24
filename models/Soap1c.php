<?php
ini_set("soap.wsdl_cache_enabled", 0);
class Soap1c extends Core
{
    private $log = 1;
    private $log_dir  = 'logs/';
    
    private $partner = 'nalichnoeplus.com';
    private $password = '856jrfnj68urtj4yilge638yoy5hhgt';
    
    
    public function __construct()
    {
    	parent::__construct();
        
        $this->log_dir = $this->config->root_dir.$this->log_dir;
    }

    public function remains($date_from, $date_to)
    {
    	$request = new StdClass();
		$request->НачПериода = $date_from; // date('Ymd', strtotime($date_from));
		$request->КонПериода = $date_to; //date('Ymd', strtotime($date_to));

		$response = $this->send('WebCRM', 'Remains', $request);
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($response);echo '</pre><hr />';        
		return empty($response->return) ? array() : json_decode($response->return);
    }

    public function get_client_images($uid)
    {
        if (empty($uid))
            return false;
        
    	$request = new StdClass();
		$request->UID = $uid;

		$response = $this->send('WebCRM', 'RequestFoto', $request);
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($response);echo '</pre><hr />';        
		return empty($response->return) ? array() : json_decode($response->return);
    }
    
    
    /**
     * Soap1c::get_client_credits()
     * Возвращает историю займов клиента по уид
     * @param string $uid
     * @return
     */
    public function get_client_credits($uid)
    {
        if (empty($uid))
            return false;
        
    	$request = new StdClass();
		$request->UID = $uid;

		$response = $this->send('WebCRM', 'HistoryZaim', $request);
        
		return empty($response->return) ? array() : json_decode($response->return);
    }
    
    /**
     * Soap1c::get_movements()
     * Возвращает движения по кредиту по номеру договора
     * 
     * @param string $number
     * @return void
     */
    public function get_movements($number)
    {
    	$request = new StdClass();
        $request->Number = $number;

		$response = $this->send('WebCRM', 'Movements', $request);
        
		return empty($response->return) ? array() : json_decode($response->return);
    }    
    
    /**
     * Soap1c::send_order()
     * ОТправляет заявку в 1с
     * 
     * @param object $order
     * @return
     */
    public function send_order($order)
    {
        $order_passport_serial = str_replace(array(' ', '-'), '', $order->passport_serial);
		$passport_number = (string)substr($order_passport_serial, 4, 6);
        $passport_serial = (string)substr($order_passport_serial, 0, 4);        
        
        $request = (object)array(
            'partner' => 'NalPlus',
            'WebMaster' => '',
            'id' => $order->order_id,
            'last_name' => $order->lastname,
            'first_name' => $order->firstname,
            'middle_name' => $order->patronymic,
            'phone' => $this->format_phone($order->phone_mobile),
            'birthday' => date('Ymd', strtotime($order->birth)),
            'email' => $order->email,
            'PassportSerial' => $passport_serial,
            'PassportNumber' => $passport_number,
            'passport_date' => date('Ymd', strtotime($order->passport_date)),
            'subdivision_code' => $order->subdivision_code,
            'passport_issued' => $order->passport_issued,
            'Regindex' => $order->Regindex,
            'Regregion' => trim($order->Regregion.' '.$order->Regregion_shorttype),
            'Regdistrict' => '',
            'Regcity' => trim($order->Regcity.' '.$order->Regcity_shorttype),
            'Reglocality' => '',
            'Regstreet' => trim($order->Regstreet.' '.$order->Regstreet_shorttype),
            'Regbuilding' => empty($order->Regbuilding) ? '' : $order->Regbuilding,
            'Reghousing' => $order->Reghousing,
            'Regroom' => $order->Regroom,
            'Faktindex' => $order->Faktindex,
            'Faktregion' => trim($order->Faktregion.' '.$order->Faktregion_shorttype),
            'Faktdistrict' => '',
            'Faktcity' => trim($order->Faktcity.' '.$order->Faktcity_shorttype),
            'Faktlocality' => '',
            'Faktstreet' => trim($order->Faktstreet.' '.$order->Faktstreet_shorttype),
            'Faktbuilding' => empty($order->Faktbuilding) ? '' : $order->Faktbuilding,
            'Fakthousing' => $order->Fakthousing,
            'Faktroom' => $order->Faktroom,
            'BirthPlace' => $order->birth_place,
            'snils' => $order->snils,
            'contact_person_1_name' => $order->contact_person_name,
            'contact_person_1_phone' => $order->contact_person_phone,
            'contact_person_1_accounts' => $order->contact_person_relation,
            'contact_person_2_name' => $order->contact_person2_name,
            'contact_person_2_phone' => $order->contact_person2_phone,
            'contact_person_2_accounts' => $order->contact_person2_relation,
            'job_profession' => $order->profession,
            'job_address' => $order->workaddress,
            'job_phone' => $order->workphone,
            'job_employment' => '',
            'job_name' => $order->workplace,
            'job_how_long' => '',
            'job_activity' => '',
            'gender' => $order->gender == 'male' ? 'Мужской' : 'Женский',
            'education' => '',
            'marital_status' => '',
            'children' => '',
            'match_addresses' => '',
            'revenue_main' => '',
            'revenue_additional' => '',
            'revenue_family' => '',
            'costs_credit' => '',
            'costs_communa' => '',
            'costs_aliments' => '',
            'credit_history' => '',
            'credit_max' => '',
            'credit_last' => '',
            'bankrot' => '',
            'amount' => $order->amount,
            'period' => $order->period,
            'АвтоОтказНаСайте' => '',
            'ПричинаАвтоОтказа' => '',
            'utm_source' => '',
            'utm_medium' => '',
            'utm_campaign' => '',
            'utm_content' => '',
            'utm_term' => '',
            'click_hash' => '',
            'CodeSMS' => '',
            'TextQuery' => '',
        );
		$response = $this->send('Teleport', 'ObmenFull', $request);
        
		return $response->return;
        
    }
    
    /**
     * Soap1c::get_user_balance()
     * 
     * Возвращает баланс клиента из 1с
     * @param string $uid
     * @return
     */
    public function get_user_balance($uid)
    {
        if (empty($uid))
            return false;

   	    $request = new StdClass();
		$request->UID = $uid;
        $request->Partner = $this->partner;
        $request->Пароль = $this->password;

		$response = $this->send('WebLK', 'GetLK', $request);
        
		return $response->return;
    }    
    
    /**
     * Soap1c::get_uid_by_phone()
     * 
     * @param string $phone
     * @return
     */
    public function get_uid_by_phone($phone)
    {
    	$request = new StdClass();
		$request->Телефон = $this->format_phone($phone);

		$response = $this->send('Tinkoff', 'SearchTel', $request);
        
		return json_decode($response->return);
    }
    
    /**
     * Soap1c::get_client_details()
     * 
     * @param string $uid
     * @return
     */
    public function get_client_details($uid)
    {
        if (empty($uid))
            return false;
        
    	$request = new StdClass();
		$request->UID = $uid;

		$response = $this->send('WebCRM', 'Details', $request);
        
		return json_decode($response->return);
    	
    }
        
    /**
     * Soap1c::format_phone()
     * Форматирует номер телефона в формат принимаемый 1с
     * формат 8(ххх)ххх-хх-хх
     * 
     * @param string $phone
     * @return string $format_phone
     */
    public function format_phone($phone)
    {
        if (empty($phone))
            return '';
        
        $replace_params = array('(', ')', ' ', '-', '+');
        $clear_phone = str_replace($replace_params, '', $phone);
        
        $substr_phone = substr($clear_phone, -10, 10);
        $format_phone = '8('.substr($substr_phone, 0, 3).')'.substr($substr_phone, 3, 3).'-'.substr($substr_phone, 6, 2).'-'.substr($substr_phone, 8, 2);
        
        return $format_phone;
    }
    
    /**
     * Soap1c::send()
     * 
     * @param string $service
     * @param string $method
     * @param array $request
     * @return
     */
    private function send($service, $method, $request)
    {
/*
        try {
			$service_url = "http://46.29.78.222:88/work/ws/".$service.".1cws?wsdl";
            $client = new SoapClient($service_url);

			$response = $client->__soapCall($method, array($request));
		} catch (Exception $fault) {
			$response = $fault;
		}
*/        
        if (!empty($this->log))
            $this->logging(__METHOD__, $service.' '.$method, (array)$request, (array)$response);
        
            return $response;
    }

    private function logging($local_method, $service, $request, $response, $filename = 'soap.txt')
    {
        $log_filename = $this->log_dir.$filename;
        
        if (date('d', filemtime($log_filename)) != date('d'))
        {
            $archive_filename = $this->log_dir.'archive/'.date('ymd', filemtime($log_filename)).'.'.$filename;
            rename($log_filename, $archive_filename);
            file_put_contents($log_filename, "\xEF\xBB\xBF");            
        }


        $str = PHP_EOL.'==================================================================='.PHP_EOL;
        $str .= date('d.m.Y H:i:s').PHP_EOL;
        $str .= $service.PHP_EOL;
        $str .= var_export($request, true).PHP_EOL;
        $str .= var_export($response, true).PHP_EOL;
        $str .= 'END'.PHP_EOL;
        
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($str);echo '</pre><hr />';
        
        file_put_contents($this->log_dir.$filename, $str, FILE_APPEND);
    }
}