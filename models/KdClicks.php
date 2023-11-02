<?php

class KdClicks extends Core
{

    public function add($kd_click)
    {
		$query = $this->db->placehold("
            INSERT INTO __kd_clicks SET ?%
        ", (array)$kd_click);
        $this->db->query($query);
        $id = $this->db->insert_id();
        
        return $id;
    }

    public function get($id)
    {
        $query = $this->db->placehold("
            SELECT * 
            FROM s_payments_to_schedules 
            WHERE id = ?
        ", $id);

        $this->db->query($query);
        $result = $this->db->result();

        return $result;
    }


    public function get_next($contract_id)
    {
        $query = $this->db->placehold("
            SELECT * 
            FROM s_payments_to_schedules 
            WHERE contract_id = ?
            AND status = 0
            ORDER by id asc
            limit 1
        ", $contract_id);

        $this->db->query($query);
        $result = $this->db->result();

        return $result;
    }
}