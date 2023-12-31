<?php

class Operations extends Core
{
	public function get_onec_operation($number_onec)
	{
		$query = $this->db->placehold("
            SELECT * 
            FROM __operations
            WHERE number_onec = ?
        ", (string)$number_onec);
        $this->db->query($query);
        $result = $this->db->result();
	
        return $result;
    }
    
	public function get_transaction_operation($transaction_id)
	{
		$query = $this->db->placehold("
            SELECT * 
            FROM __operations
            WHERE transaction_id = ?
        ", (int)$transaction_id);
        $this->db->query($query);
        $result = $this->db->result();
	
        return $result;
    }
    
	public function get_operation($id)
	{
		$query = $this->db->placehold("
            SELECT * 
            FROM __operations
            WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
        $result = $this->db->result();
	
        return $result;
    }
    
	public function get_operations($filter = array())
	{
		$id_filter = '';
        $contract_id_filter = '';
        $keyword_filter = '';
        $limit = 1000;
		$page = 1;
        
        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND id IN (?@)", array_map('intval', (array)$filter['id']));
        
        if (!empty($filter['contract_id']))
            $contract_id_filter = $this->db->placehold("AND contract_id IN (?@)", array_map('intval', (array)$filter['contract_id']));
        
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
            FROM __operations
            WHERE 1
                $id_filter
                $contract_id_filter
                $keyword_filter
            ORDER BY id DESC 
            $sql_limit
        ");
        $this->db->query($query);
        $results = $this->db->results();
        
        return $results;
	}
    
	public function count_operations($filter = array())
	{
        $id_filter = '';
        $contract_id_filter = '';
        $keyword_filter = '';
        
        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND id IN (?@)", array_map('intval', (array)$filter['id']));
		
        if (!empty($filter['contract_id']))
            $contract_id_filter = $this->db->placehold("AND contract_id IN (?@)", array_map('intval', (array)$filter['contract_id']));
        
        if(isset($filter['keyword']))
		{
			$keywords = explode(' ', $filter['keyword']);
			foreach($keywords as $keyword)
				$keyword_filter .= $this->db->placehold('AND (name LIKE "%'.$this->db->escape(trim($keyword)).'%" )');
		}
                
		$query = $this->db->placehold("
            SELECT COUNT(id) AS count
            FROM __operations
            WHERE 1
                $id_filter
                $contract_id_filter
                $keyword_filter
        ");
        $this->db->query($query);
        $count = $this->db->result('count');
	
        return $count;
    }
    
    public function add_operation($operation)
    {
		$query = $this->db->placehold("
            INSERT INTO __operations SET ?%
        ", (array)$operation);
        $this->db->query($query);
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    public function update_operation($id, $operation)
    {
		$query = $this->db->placehold("
            UPDATE __operations SET ?% WHERE id = ?
        ", (array)$operation, (int)$id);
        $this->db->query($query);
        
        return $id;
    }
    
    public function delete_operation($id)
    {
		$query = $this->db->placehold("
            DELETE FROM __operations WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
    }

    public function max_service_number(){
        $query = $this->db->placehold("
        SELECT max(`service_number`) as max_service_number FROM `s_operations` WHERE 1
        ");

        $this->db->query($query);
        
        $result = $this->db->results()[0]->max_service_number;

        $result_first = substr($result, 0, 15);
        $result_last = substr($result, 15, 5);
        $result_last =  str_pad(strval(1 + $result_last), 5, '0', STR_PAD_LEFT);

        $result = $result_first . $result_last;

        return $result;
    }
}