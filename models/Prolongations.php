<?php

class Prolongations extends Core
{
	public function get_prolongation($id)
	{
		$query = $this->db->placehold("
            SELECT * 
            FROM __prolongations
            WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
        $result = $this->db->result();
	
        return $result;
    }
    
	public function get_prolongations($filter = array())
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
            FROM __prolongations
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
    
	public function count_prolongations($filter = array())
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
            FROM __prolongations
            WHERE 1
                $id_filter
                $keyword_filter
        ");
        $this->db->query($query);
        $count = $this->db->result('count');
	
        return $count;
    }
    
    public function add_prolongation($prolongation)
    {
		$query = $this->db->placehold("
            INSERT INTO __prolongations SET ?%
        ", (array)$prolongation);
        $this->db->query($query);
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    public function update_prolongation($id, $prolongation)
    {
		$query = $this->db->placehold("
            UPDATE __prolongations SET ?% WHERE id = ?
        ", (array)$prolongation, (int)$id);
        $this->db->query($query);
        
        return $id;
    }
    
    public function delete_prolongation($id)
    {
		$query = $this->db->placehold("
            DELETE FROM __prolongations WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
    }
}