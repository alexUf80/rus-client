<?php

class Orders extends Core
{
    private $statuses = array(
        0 => 'Принята',
        1 => 'На рассмотрении',
        2 => 'Одобрена',
        3 => 'Отказ',
        4 => 'Готов к выдаче',
        5 => 'Займ выдан',
        6 => 'Не удалось выдать',
        7 => 'Погашен',
        8 => 'Отказ клиента',
    );
    
    public function get_statuses()
    {
        return $this->statuses;
    }
    
    public function get_last_order($user_id)
    {
        $query = $this->db->placehold("
            SELECT * 
            FROM __orders 
            WHERE user_id = ? 
            ORDER BY date DESC
            LIMIT 1
        ", $user_id);
        $this->db->query($query);
        $result = $this->db->result();
        
        return $result;
    }
    
	public function get_order($id)
	{
		$query = $this->db->placehold("
            SELECT 
                o.id AS order_id,
                o.manager_id,
                o.contract_id,
                o.date,
                o.user_id,
                o.card_id,
                o.ip,
                o.amount,
                o.period,
                o.status,
                o.first_loan,
                o.id_1c,
                o.status_1c,
                o.reject_reason,
                o.reason_id,
                o.utm_source,
                o.utm_medium,
                o.utm_campaign,
                o.utm_content,
                o.utm_term,
                o.webmaster_id,
                o.click_hash,
                o.juicescore_session_id,
                o.local_time,
                o.accept_date,
                o.reject_date,
                o.approve_date,
                o.confirm_date,
                o.client_status,
                o.loantype_id,
                u.UID AS user_uid,
                u.service_sms,
                u.service_insurance,
                u.service_reason,
                u.phone_mobile,
                u.email,
                u.lastname,
                u.firstname,
                u.patronymic,
                u.gender,
                u.birth,
                u.birth_place,
                u.passport_serial,
                u.subdivision_code,
                u.passport_date,
                u.passport_issued,
                u.snils,
                u.workplace,
                u.workaddress,
                u.workcomment,
                u.profession,
                u.workphone,
                u.income,
                u.expenses,
                u.social
            FROM __orders AS o
            LEFT JOIN __users AS u
            ON u.id = o.user_id
            WHERE o.id = ?
        ", (int)$id);
        $this->db->query($query);
        $result = $this->db->result();

        return $result;
    }
    
	public function get_orders($filter = array())
	{
		$id_filter = '';
		$user_id_filter = '';
        $date_from_filter = '';
        $date_to_filter = '';
        $status_filter = '';
        $search_filter = '';
        $keyword_filter = '';
        $limit = 10000;
		$page = 1;
        $sort = 'order_id DESC';
        
        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND o.id IN (?@)", array_map('intval', (array)$filter['id']));
            
        if (!empty($filter['user_id']))
            $user_id_filter = $this->db->placehold("AND o.user_id IN (?@)", array_map('intval', (array)$filter['user_id']));
        
        if (!empty($filter['date_from']))
            $date_from_filter = $this->db->placehold("AND DATE(o.date) >= ?", $filter['date_from']);
            
        if (!empty($filter['date_to']))
            $date_to_filter = $this->db->placehold("AND DATE(o.date) <= ?", $filter['date_to']);
        
        if (isset($filter['status']))
            $status_filter = $this->db->placehold("AND o.status IN (?@)", (array)$filter['status']);
        
        if (!empty($filter['search']))
        {
            if (!empty($filter['search']['order_id']))
                $search_filter .= $this->db->placehold(' AND o.id = ?', (int)$filter['search']['order_id']);
            if (!empty($filter['search']['date']))
                $search_filter .= $this->db->placehold(' AND DATE(o.date) = ?', date('Y-m-d', strtotime($filter['search']['date'])));
            if (!empty($filter['search']['amount']))
                $search_filter .= $this->db->placehold(' AND o.amount = ?', (int)$filter['search']['amount']);
            if (!empty($filter['search']['period']))
                $search_filter .= $this->db->placehold(' AND o.period = ?', (int)$filter['search']['period']);
            if (!empty($filter['search']['fio']))
            {
                $fio_filter = array();
                $expls = array_map('trim', explode(' ', $filter['search']['fio']));
                $search_filter .= $this->db->placehold(' AND (');
                foreach ($expls as $expl)
                {
                    $expl = $this->db->escape($expl);
                    $fio_filter[] = $this->db->placehold("(u.lastname LIKE '%".$expl."%' OR u.firstname LIKE '%".$expl."%' OR u.patronymic LIKE '%".$expl."%')");
                }
                $search_filter .= implode(' AND ', $fio_filter);
                $search_filter .= $this->db->placehold(')');
            }
            if (!empty($filter['search']['birth']))
                $search_filter .= $this->db->placehold(' AND DATE(u.birth) = ?', date('Y-m-d', strtotime($filter['search']['birth'])));
            if (!empty($filter['search']['phone']))
                $search_filter .= $this->db->placehold(" AND u.phone_mobile LIKE '%".$this->db->escape(str_replace(array(' ', '-', '(', ')', '+'), '', $filter['search']['phone']))."%'");
            if (!empty($filter['search']['region']))
                $search_filter .= $this->db->placehold(" AND u.Regregion LIKE '%".$this->db->escape($filter['search']['region'])."%'");
             if (!empty($filter['search']['status']))
                $search_filter .= $this->db->placehold(" AND o.1c_status LIKE '%".$this->db->escape($filter['search']['status'])."%'");
        }
        
		if(isset($filter['keyword']))
		{
			$keywords = explode(' ', $filter['keyword']);
			foreach($keywords as $keyword)
				$keyword_filter .= $this->db->placehold('AND (o.name LIKE "%'.$this->db->escape(trim($keyword)).'%" )');
		}
        
		if(isset($filter['limit']))
			$limit = max(1, intval($filter['limit']));

		if(isset($filter['page']))
			$page = max(1, intval($filter['page']));
            
        $sql_limit = $this->db->placehold(' LIMIT ?, ? ', ($page-1)*$limit, $limit);
        
        if (!empty($filter['sort']))
        {
            switch ($filter['sort']):
                
                case 'order_id_asc':
                    $sort = 'order_id ASC';
                break;
                
                case 'order_id_desc':
                    $sort = 'order_id DESC';
                break;
                
                case 'date_asc':
                    $sort = 'o.date ASC';
                break;
                
                case 'date_desc':
                    $sort = 'o.date DESC';
                break;
                
                case 'amount_desc':
                    $sort = 'o.amount DESC';
                break;
                
                case 'amount_asc':
                    $sort = 'o.amount ASC';
                break;
                
                case 'period_asc':
                    $sort = 'o.period ASC';
                break;
                
                case 'period_desc':
                    $sort = 'o.period DESC';
                break;
                
                case 'fio_asc':
                    $sort = 'u.lastname ASC';
                break;
                
                case 'fio_desc':
                    $sort = 'u.lastname DESC';
                break;
                                
                case 'birth_asc':
                    $sort = 'u.birth ASC';
                break;
                
                case 'birth_desc':
                    $sort = 'u.birth DESC';
                break;

                case 'phone_asc':
                    $sort = 'u.phone_mobile ASC';
                break;
                
                case 'phone_desc':
                    $sort = 'u.phone_mobile DESC';
                break;
                                
                case 'region_asc':
                    $sort = 'u.Regregion ASC';
                break;
                
                case 'region_desc':
                    $sort = 'u.Regregion DESC';
                break;
                                
                case 'status_asc':
                    $sort = 'o.1c_status ASC';
                break;
                
                case 'status_desc':
                    $sort = 'o.1c_status DESC';
                break;
                                
                                
            endswitch;
        }

        $query = $this->db->placehold("
            SELECT 
                o.id AS order_id,
                o.manager_id,
                o.contract_id,
                o.date,
                o.user_id,
                o.card_id,
                o.ip,
                o.amount,
                o.period,
                o.status,
                o.first_loan,
                o.id_1c,
                o.status_1c,
                o.reject_reason,
                o.reason_id,
                o.utm_source,
                o.utm_medium,
                o.utm_campaign,
                o.utm_content,
                o.utm_term,
                o.webmaster_id,
                o.click_hash,
                o.juicescore_session_id,
                o.local_time,
                o.accept_date,
                o.reject_date,
                o.approve_date,
                o.confirm_date, 
                o.client_status,               
                u.UID AS user_uid,
                u.service_sms,
                u.service_insurance,
                u.service_reason,
                u.phone_mobile,
                u.email,
                u.lastname,
                u.firstname,
                u.patronymic,
                u.gender,
                u.birth,
                u.birth_place,
                u.passport_serial,
                u.subdivision_code,
                u.passport_date,
                u.passport_issued,
                u.snils,
                u.workplace,
                u.workaddress,
                u.workcomment,
                u.profession,
                u.workphone,
                u.income,
                u.expenses,
                u.social
            FROM __orders AS o
            LEFT JOIN __users AS u
            ON u.id = o.user_id
            WHERE 1
                $id_filter
                $user_id_filter 
                $date_from_filter
                $date_to_filter
                $status_filter
                $search_filter
                $keyword_filter
            ORDER BY $sort 
            $sql_limit
        ");
        $this->db->query($query);
        $results = $this->db->results();
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($query);echo '</pre><hr />';
        return $results;
	}
    
	public function count_orders($filter = array())
	{
        $id_filter = '';
        $date_from_filter = '';
        $date_to_filter = '';
        $status_filter = '';
        $search_filter = '';
        $keyword_filter = '';
        
        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND id IN (?@)", array_map('intval', (array)$filter['id']));
		
        if (!empty($filter['date_from']))
            $date_from_filter = $this->db->placehold("AND DATE(o.date) >= ?", $filter['date_from']);
            
        if (!empty($filter['date_to']))
            $date_to_filter = $this->db->placehold("AND DATE(o.date) <= ?", $filter['date_to']);

        if (isset($filter['status']))
            $status_filter = $this->db->placehold("AND o.status IN (?@)", (array)$filter['status']);
        
        if (!empty($filter['search']))
        {
            if (!empty($filter['search']['order_id']))
                $search_filter .= $this->db->placehold(' AND o.id = ?', (int)$filter['search']['order_id']);
            if (!empty($filter['search']['date']))
                $search_filter .= $this->db->placehold(' AND DATE(o.date) = ?', date('Y-m-d', strtotime($filter['search']['date'])));
            if (!empty($filter['search']['amount']))
                $search_filter .= $this->db->placehold(' AND o.amount = ?', (int)$filter['search']['amount']);
            if (!empty($filter['search']['period']))
                $search_filter .= $this->db->placehold(' AND o.period = ?', (int)$filter['search']['period']);
            if (!empty($filter['search']['fio']))
            {
                $fio_filter = array();
                $expls = array_map('trim', explode(' ', $filter['search']['fio']));
                $search_filter .= $this->db->placehold(' AND (');
                foreach ($expls as $expl)
                {
                    $expl = $this->db->escape($expl);
                    $fio_filter[] = $this->db->placehold("(u.lastname LIKE '%".$expl."%' OR u.firstname LIKE '%".$expl."%' OR u.patronymic LIKE '%".$expl."%')");
                }
                $search_filter .= implode(' AND ', $fio_filter);
                $search_filter .= $this->db->placehold(')');
            }
            if (!empty($filter['search']['birth']))
                $search_filter .= $this->db->placehold(' AND DATE(u.birth) = ?', date('Y-m-d', strtotime($filter['search']['birth'])));
            if (!empty($filter['search']['phone']))
                $search_filter .= $this->db->placehold(" AND u.phone_mobile LIKE '%".$this->db->escape(str_replace(array(' ', '-', '(', ')', '+'), '', $filter['search']['phone']))."%'");
            if (!empty($filter['search']['region']))
                $search_filter .= $this->db->placehold(" AND u.Regregion LIKE '%".$this->db->escape($filter['search']['region'])."%'");
             if (!empty($filter['search']['status']))
                $search_filter .= $this->db->placehold(" AND o.1c_status LIKE '%".$this->db->escape($filter['search']['status'])."%'");
        }
        
        if(isset($filter['keyword']))
		{
			$keywords = explode(' ', $filter['keyword']);
			foreach($keywords as $keyword)
				$keyword_filter .= $this->db->placehold('AND (name LIKE "%'.$this->db->escape(trim($keyword)).'%" )');
		}
                
		$query = $this->db->placehold("
            SELECT COUNT(id) AS count
            FROM __orders AS o
            WHERE 1
                $id_filter
                $date_from_filter
                $date_to_filter
                $search_filter
                $status_filter
                $keyword_filter
        ");
        $this->db->query($query);
        $count = $this->db->result('count');

        return $count;
    }
    
    public function add_order($order)
    {
		$order = (array)$order;
        
        if (empty($order['date']))
            $order['date'] = date('Y-m-d H:i:s');
        
        $query = $this->db->placehold("
            INSERT INTO __orders SET ?%
        ", $order);
        $this->db->query($query);
        $id = $this->db->insert_id();
        
        $order_date = strtotime($order['date']);
        $uid = 'a0'.$id.'-'.date('Y', $order_date).'-'.date('md', $order_date).'-'.date('Hi', $order_date).'-01771ca07de7';
        $this->update_order($id, array('uid' => $uid));
        
        return $id;
    }
    
    public function update_order($id, $order)
    {
		$query = $this->db->placehold("
            UPDATE __orders SET ?% WHERE id = ?
        ", (array)$order, (int)$id);
        $this->db->query($query);
        
        return $id;
    }
    
    public function delete_order($id)
    {
		$query = $this->db->placehold("
            DELETE FROM __orders WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
    }
}