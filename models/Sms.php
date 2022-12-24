<?php

define("SMS_LOGIN", "BF02092021");
define("SMS_PASSWORD", "82e63217cdda82fae80ec99e231b47967e99e2fc");

class Sms extends Core
{
    private $login;
    private $password;
    private $originator;
    private $connect_id;
    
    public function __construct()
    {
    	parent::__construct();
        
        $this->login = $this->settings->apikeys['sms']['login'];
        $this->password = $this->settings->apikeys['sms']['password'];

        $this->aero_login = 'webmaster@finreactor.ru';
        $this->aero_api_key = '4ITlid0NnDePJe0awybOA5IxCv4g';
    }
    
    public function clear_phone($phone)
    {
        $remove_symbols = array(
            '(', 
            ')', 
            '-', 
            ' ', 
            '+'
        );
        $clear_phone = str_replace($remove_symbols, '', $phone);

        if (substr($clear_phone, 0, 1) == 8)
            $clear_phone = '7'.substr($clear_phone, 1);
        
        return $clear_phone;
    }
    
    public function send($phone, $message)
    {
        $phone = $this->clear_phone($phone);
        
    	return $this->send_smsc($phone, $message);
    }

    public function send_code_via_call($phone)
    {
        $url = 'https://smsc.ru/sys/send.php?login=' . SMS_LOGIN . '&psw=' . SMS_PASSWORD . '&phones=' . $phone . '&mes=code&call=3';
        $resultString = file_get_contents($url);
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($url);echo '</pre><hr />';
        return $resultString;
    }
    
    
    public function send_easysms($phone, $message)
    {
        $params = http_build_query(array(
            'login' => $this->login,
            'password' => $this->password,
            'text' => $message,
            'phone' => $phone,
            'originator' => $this->originator
        ));
        
        $url = 'https://xml.smstec.ru/api/v1/easysms/'.$this->connect_id.'/send_sms?'.$params;

        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $resp = curl_exec($ch);
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($resp, $url);echo '</pre><hr />';    

        curl_close($ch);
        
        return $resp;
    }
    
    public function send_smsc($phone, $message)
    {

    	$url = 'http://smsc.ru/sys/send.php?login='.SMS_LOGIN.'&psw='.SMS_PASSWORD.'&phones='.$phone.'&mes='.$message.'';
        
        $resp = file_get_contents($url);
        
        return array('url'=>$url, 'resp'=>$resp);
    }
    
    
    public function get_code($phone)
    {
    	$query = $this->db->placehold("
            SELECT code
            FROM __sms_messages
            WHERE phone = ?
            ORDER BY id DESC
            LIMIT 1
        ", $this->clear_phone($phone));
        $this->db->query($query);
        
        $code = $this->db->result('code');
        
        return $code;
    }
    
	public function get_message($id)
	{
		$query = $this->db->placehold("
            SELECT * 
            FROM __sms_messages
            WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
        $result = $this->db->result();
	
        return $result;
    }
    
	public function get_messages($filter = array())
	{
		$id_filter = '';
        $keyword_filter = '';
        $phone_filter = '';
        $limit = 1000;
		$page = 1;
        
        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND id IN (?@)", array_map('intval', (array)$filter['id']));
        
        if (!empty($filter['phone']))
            $phone_filter = $this->db->placehold("AND phone = ?", $this->clear_phone($filter['phone']));
		
		if(isset($filter['keyword']))
		{
			$keywords = explode(' ', $filter['keyword']);
			foreach($keywords as $keyword)
				$keyword_filter .= $this->db->placehold('AND (name LIKE "%'.$this->db->escape(trim($keyword)).'%" )');
		}
        
		if(isset($filter['limit']))
			$limit = max(1, intval($filter['limit']));

		if(isset($filter['page']))
			$page = max(1, intval($filter['page']));
            
        $sql_limit = $this->db->placehold(' LIMIT ?, ? ', ($page-1)*$limit, $limit);

        $query = $this->db->placehold("
            SELECT * 
            FROM __sms_messages
            WHERE 1
                $id_filter
                $phone_filter
				$keyword_filter
            ORDER BY id DESC 
            $sql_limit
        ");
        $this->db->query($query);
        $results = $this->db->results();
        
        return $results;
	}
    
	public function count_messages($filter = array())
	{
        $id_filter = '';
        $phone_filter = '';
        $keyword_filter = '';
        
        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND id IN (?@)", array_map('intval', (array)$filter['id']));
		
        if (!empty($filter['phone']))
            $phone_filter = $this->db->placehold("AND phone = ?", $this->clear_phone($filter['phone']));
		
        if(isset($filter['keyword']))
		{
			$keywords = explode(' ', $filter['keyword']);
			foreach($keywords as $keyword)
				$keyword_filter .= $this->db->placehold('AND (name LIKE "%'.$this->db->escape(trim($keyword)).'%" )');
		}
                
		$query = $this->db->placehold("
            SELECT COUNT(id) AS count
            FROM __sms_messages
            WHERE 1
                $id_filter
                $phone_filter
                $keyword_filter
        ");
        $this->db->query($query);
        $count = $this->db->result('count');
	
        return $count;
    }
    
    public function add_message($message)
    {
		$message = (array)$message;
        
        if (isset($message['phone']))
            $message['phone'] = $this->clear_phone($message['phone']);
        
        $query = $this->db->placehold("
            INSERT INTO __sms_messages SET ?%
        ", $message);
        $this->db->query($query);
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    public function update_message($id, $message)
    {
		$message = (array)$message;
        
        if (isset($message['phone']))
            $message['phone'] = $this->clear_phone($message['phone']);
        
		$query = $this->db->placehold("
            UPDATE __sms_messages SET ?% WHERE id = ?
        ", $message, (int)$id);
        $this->db->query($query);
        
        return $id;
    }
    
    public function delete_message($id)
    {
		$query = $this->db->placehold("
            DELETE FROM __sms_messages WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
    }
    
}