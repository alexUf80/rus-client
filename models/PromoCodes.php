<?php

class PromoCodes extends Core
{
    public function get_code($id)
	{
		$query = $this->db->placehold("
            SELECT * 
            FROM s_promocodes
            WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
        $result = $this->db->result();
	
        return $result;
    }

    public function get_code_by_code($code)
	{
		$query = $this->db->placehold("
            SELECT * 
            FROM s_promocodes
            WHERE code = ?
        ", $code);

        //echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($query);echo '</pre><hr />';

        $this->db->query($query);
        $result = $this->db->result();
	
        return $result;
    }

    public function check_code($code)
	{

        if (empty($code)) {
            return false;
        }

        if (!isset($code->date_from) || !isset($code->date_to) || !isset($code->percent)) {
            return false;
        }

        $start = strtotime($code->date_from);
        $end = strtotime($code->date_to);
        $now = time();

        if($now >= $start && $now <= $end) {
            return true;
        } else {
            return false;
        }
    }
    
	public function get_codes($filter = array())
	{
		$id_filter = '';
        $keyword_filter = '';
        $limit = 1000;
		$page = 1;
        $sort = 'id DESC';
        
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

        if(isset($filter['sort']))
        {
            $sort = $filter['sort'];
        }
            
        $sql_limit = $this->db->placehold(' LIMIT ?, ? ', ($page-1)*$limit, $limit);

        $query = $this->db->placehold("
            SELECT * 
            FROM s_promocodes
            WHERE 1
                $id_filter
				$keyword_filter
            ORDER BY $sort
            $sql_limit
        ");
        $this->db->query($query);
        $results = $this->db->results();
        
        return $results;
	}
    
	public function count_codes($filter = array())
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
            FROM s_promocodes
            WHERE 1
                $id_filter
                $keyword_filter
        ");
        $this->db->query($query);
        $count = $this->db->result('count');
	
        return $count;
    }

    public function add($promo_codes)
    {
		$query = $this->db->placehold("
            INSERT INTO s_promocodes SET ?%
        ", (array)$promo_codes);
        $this->db->query($query);
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    public function update($id, $promo_codes)
    {
		$query = $this->db->placehold("
            UPDATE s_promocodes SET ?% WHERE id = ?
        ", (array)$promo_codes, (int)$id);
        $this->db->query($query);
        
        return $id;
    }
    
    public function delete($id)
    {
		$query = $this->db->placehold("
            DELETE FROM s_promocodes WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
    }
}