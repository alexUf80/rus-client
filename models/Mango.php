<?php

class Mango extends Core
{
    private $api_key = 'h3m2k6wya0r9caehvgqn8vmb35xkqmbx';
    private $api_salt = 'fnqqkcel3vl6wqehxvuvg7x92kkjwl72';
    private $line_number = '78003332484';
    private $url = 'https://app.mango-office.ru/vpbx/commands/callback';
    
    public function __construct()
    {
        parent::__construct();
        
        $this->api_key = $this->settings->apikeys['mango']['api_key'];
        $this->api_salt = $this->settings->apikeys['mango']['api_salt'];
//        $this->line_number = $this->settings->apikeys['mango']['line_number'];

//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($this->api_key, $this->api_salt);echo '</pre><hr />';
//exit;
    }
    
    public function call($phone, $mango_number)
    {
        $data = array(
            "command_id" => "ID" . rand(10000000,99999999),
            "from" => array(
            "extension" => $mango_number, // внутренний номер, за счет которого производится звонок. (например 101)
            
            ) ,
            "to_number" => $phone, // <- кому звонит
            "line_number" => $this->line_number // <- какой АОН 
        );

//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($this->api_key, $this->api_salt);echo '</pre><hr />';
        $json = json_encode($data);
        $sign = hash('sha256', $this->api_key . $json . $this->api_salt);
        $postdata = array(
            'vpbx_api_key' => $this->api_key,
            'sign' => $sign,
            'json' => $json
        );
        $post = http_build_query($postdata);
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response; 
    }
}