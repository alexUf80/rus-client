<?php

class PaymentsToSchedules extends Core
{
    public function update($id, $params)
    {
        $query = $this->db->placehold("
            UPDATE s_payments_to_schedules SET ?% WHERE id = ?
        ", $params, $id);
        $this->db->query($query);

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

    public function get_count_remaining($contract_id)
    {
        $query = $this->db->placehold("
            SELECT count(*) as `count` 
            FROM s_payments_to_schedules 
            WHERE contract_id = ?
            AND status = 0
        ", $contract_id);

        $this->db->query($query);
        $result = $this->db->result('count');

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