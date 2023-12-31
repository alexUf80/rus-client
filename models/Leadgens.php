<?php

class Leadgens extends Core
{
    public function send_pending_postback_click2money($order_id, $order)
    {

        $base_link = 'https://c2mpbtrck.com/cpaCallback';
        $link_lead = $base_link.'?cid='.$order['click_hash'].'&action=reject&partner=ecozaym&lead_id='.$order_id;

        $ch = curl_init($link_lead);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_exec($ch);
        curl_close($ch);
    }




	public function get_postback($id)
	{
		$query = $this->db->placehold("
            SELECT * 
            FROM s_leadgen_postbacks
            WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
        $result = $this->db->result();
	
        return $result;
    }
    
	public function get_postbacks($filter = array())
	{
		$id_filter = '';
        $keyword_filter = '';
        $limit = 1000;
		$page = 1;
        
        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND id IN (?@)", array_map('intval', (array)$filter['id']));
        
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
            FROM s_leadgen_postbacks
            WHERE 1
                $id_filter
				$keyword_filter
            ORDER BY id DESC 
            $sql_limit
        ");
        $this->db->query($query);
        $results = $this->db->results();
        
        return $results;
	}
    
	public function count_postbacks($filter = array())
	{
        $id_filter = '';
        $keyword_filter = '';
        
        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND id IN (?@)", array_map('intval', (array)$filter['id']));
		
        if(isset($filter['keyword']))
		{
			$keywords = explode(' ', $filter['keyword']);
			foreach($keywords as $keyword)
				$keyword_filter .= $this->db->placehold('AND (name LIKE "%'.$this->db->escape(trim($keyword)).'%" )');
		}
                
		$query = $this->db->placehold("
            SELECT COUNT(id) AS count
            FROM s_leadgen_postbacks
            WHERE 1
                $id_filter
                $keyword_filter
        ");
        $this->db->query($query);
        $count = $this->db->result('count');
	
        return $count;
    }
    
    public function add_postback($postback)
    {
		$query = $this->db->placehold("
            INSERT INTO s_leadgen_postbacks SET ?%
        ", (array)$postback);
        $this->db->query($query);
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    public function update_postback($id, $postback)
    {
		$query = $this->db->placehold("
            UPDATE s_leadgen_postbacks SET ?% WHERE id = ?
        ", (array)$postback, (int)$id);
        $this->db->query($query);
        
        return $id;
    }
    
    public function delete_postback($id)
    {
		$query = $this->db->placehold("
            DELETE FROM s_leadgen_postbacks WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
    }
}
