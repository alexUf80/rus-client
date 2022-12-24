<?php

class Contracts extends Core
{
    private $statuses = array(
        0 => 'Новый',
        1 => 'Подписан',
        2 => 'Выдан',
        3 => 'Закрыт',
        4 => 'Просрочен',
        5 => 'Истек срок подписания',
        6 => 'Мы не смогли зачислить деньги на карту',
        7 => 'Цессия',
        8 => 'Суд',
    );
    
    public function get_statuses()
    {
    	return $this->statuses;
    }
    
    public function find_active_contracts($id)
    {
    	$query = $this->db->placehold("
            SELECT c.* 
            FROM __contracts AS c
            WHERE c.status IN (2, 4, 7)
            AND c.user_id = ?
        ", $id);
        $this->db->query($query);
        
        return $this->db->result();
    }
    
    
    public function get_number_contract($number)
    {
    	$query = $this->db->placehold("
            SELECT *
            FROM __contracts
            WHERE number = ?
            ORDER BY id DESC
            LIMIT 1
        ", $number);
        $this->db->query($query);
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($query);echo '</pre><hr />';        
        return $this->db->result();        
    }
    
	public function get_last_contract($user_id)
    {
    	$query = $this->db->placehold("
            SELECT *
            FROM __contracts
            WHERE user_id = ?
            ORDER BY id DESC
            LIMIT 1
        ", (int)$user_id);
        $this->db->query($query);
        
        return $this->db->result();        
    }
    
	public function get_contract($id)
	{
		$query = $this->db->placehold("
            SELECT * 
            FROM __contracts
            WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
        $result = $this->db->result();
	
        return $result;
    }
    
	public function get_contracts($filter = array())
	{
		$id_filter = '';
        $user_id_filter = '';
        $order_id_filter = '';
        $status_filter = '';
        $keyword_filter = '';
        $limit = 1000;
		$page = 1;
        
        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND id IN (?@)", array_map('intval', (array)$filter['id']));
        
        if (!empty($filter['user_id']))
            $user_id_filter = $this->db->placehold("AND user_id = ?", (int)$filter['user_id']);
        
        if (!empty($filter['status']))
            $status_filter = $this->db->placehold("AND status IN (?@)", (array)$filter['status']);
        
        if (!empty($filter['order_id']))
            $order_id_filter = $this->db->placehold("AND order_id = ?", (int)$filter['order_id']);
        
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
            FROM __contracts
            WHERE 1
                $id_filter
                $user_id_filter
                $status_filter
                $order_id_filter
                $keyword_filter
            ORDER BY id DESC 
            $sql_limit
        ");
        $this->db->query($query);
        $results = $this->db->results();
        
        return $results;
	}
    
	public function count_contracts($filter = array())
	{
        $id_filter = '';
        $user_id_filter = '';
        $order_id_filter = '';
        $status_filter = '';
        $keyword_filter = '';
        $issuance_date_filter = '';

        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND id IN (?@)", array_map('intval', (array)$filter['id']));
		
        if (!empty($filter['user_id']))
            $user_id_filter = $this->db->placehold("AND user_id = ?", (int)$filter['user_id']);
        
        if (!empty($filter['status']))
            $status_filter = $this->db->placehold("AND status IN (?@)", (array)$filter['status']);
        
        if (!empty($filter['order_id']))
            $order_id_filter = $this->db->placehold("AND order_id = ?", (int)$filter['order_id']);

        if (isset($filter['issuance_date_from']))
            $issuance_date_filter = $this->db->placehold("AND inssuance_date >= ", $filter['issuance_date_from']);
        
        if(isset($filter['keyword']))
		{
			$keywords = explode(' ', $filter['keyword']);
			foreach($keywords as $keyword)
				$keyword_filter .= $this->db->placehold('AND (name LIKE "%'.$this->db->escape(trim($keyword)).'%" )');
		}
                
		$query = $this->db->placehold("
            SELECT COUNT(id) AS count
            FROM __contracts
            WHERE 1
                $id_filter
                $user_id_filter
                $status_filter
                $order_id_filter
                $keyword_filter
                $issuance_date_filter
        ");
        $this->db->query($query);
        $count = $this->db->result('count');
	
        return $count;
    }
    
    public function add_contract($contract)
    {
        $contract = (array)$contract;
        
		$query = $this->db->placehold("
            INSERT INTO __contracts SET ?%
        ", (array)$contract);
        $this->db->query($query);
        $id = $this->db->insert_id();
        
        $contract_date = strtotime($contract['create_date']);
        $uid = 'c0'.$id.'-'.date('Y', $contract_date).'-'.date('md', $contract_date).'-'.date('Hi', $contract_date).'-c041777ac177';
    
        if (empty($contract['number']))
        {
            $contract_number = date('md', $contract_date).'-'.$id;
            $this->update_contract($id, array('uid' => $uid, 'number'=>$contract_number));
        }
        else
        {
            $this->update_contract($id, array('uid' => $uid));
        }

            
        return $id;
    }
    
    public function update_contract($id, $contract)
    {
		$query = $this->db->placehold("
            UPDATE __contracts SET ?% WHERE id = ?
        ", (array)$contract, (int)$id);
        $this->db->query($query);
        
        return $id;
    }
    
    public function delete_contract($id)
    {
		$query = $this->db->placehold("
            DELETE FROM __contracts WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
    }
}