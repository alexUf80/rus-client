<?php

class Users extends Core
{
    private $salt = '59e6b15488e86a2b3c811c5479d6e182';
    
    public function check_client_status($user)
    {
        $client_status = 'nk';
        if ($close_contracts = $this->contracts->get_contracts(array('user_id' => $user->id, 'status' => 3)))
        {
            $have_close_crm = 0;
            foreach ($close_contracts as $cc)
                if ($cc->type == 'base')
                    $have_close_crm = 1;
            
            $client_status = empty($have_close_crm) ? 'pk' : 'crm';
        }
        else
        {
            if ($credits_history = $this->soap1c->get_client_credits($user->UID))
            {
                $have_close_onec = 0;
                $loan_history = $this->save_loan_history($user->id, $credits_history);
                if (!empty($loan_history))
                {
                    foreach ($loan_history as $lh)
                        if (!empty($lh->close_date))
                            $client_status = 'pk';
                }
            }
        }
    	
        if ($client_status != 'pk' && $client_status != 'crm')
        {
            if ($orders = $this->orders->get_orders(array('user_id' => $user->id)))
            {
                $client_status = 'rep';
            }
        }
        
        return $client_status;
    }
    
    
    
    /**
     * Users::find_clone()
     * 
     * Осуществляет поиск клиента по номеру паспорта и возвращает его id
     * @param string $passport_serial
     * @return integer $id
     */
    public function find_clone($passport_serial, $lastname, $firstname, $patronymic, $birth)
    {
        $passport_serial = str_replace(array(' ', '-'), '', $passport_serial);
    	$passport_serial_prepare = substr($passport_serial, 0, 4).'-'.substr($passport_serial, 4, 6);
        $this->db->query("
            SELECT id FROM __users WHERE passport_serial = ?
        ", $passport_serial_prepare);
        $id = $this->db->result('id');

        if (!empty($id))
            return $id;
        
        $this->db->query("
            SELECT id FROM __users 
            WHERE lastname LIKE '%".$this->db->escape($lastname)."%'
            AND firstname LIKE '%".$this->db->escape($firstname)."%'
            AND patronymic LIKE '%".$this->db->escape($patronymic)."%'
            AND birth = ?
        ", $birth);

        $id = $this->db->result('id');

        if (!empty($id))
            return $id;

        return NULL;
    }
    
    
    public function check_fields($user)
    {
        $response = array();
        
    	$user = (object)$user;
    
        
        $personal = array();
        
        if (empty($user->lastname))
            $personal[] = 'lastname';
        if (empty($user->firstname))
            $personal[] = 'firstname';
        if (empty($user->patronymic))
            $personal[] = 'patronymic';    
        if (empty($user->gender))
            $personal[] = 'gender';
        if (empty($user->birth))
            $personal[] = 'birth';
        if (empty($user->birth_place))
            $personal[] = 'birth_place';
        if (empty($user->email))
            $personal[] = 'email';
    
        if (!empty($personal))
            $response['personal'] = $personal;
    

        $passport = array();
        
        if (empty($user->passport_serial))
            $passport[] = 'passport_serial';
        if (empty($user->passport_date))
            $passport[] = 'passport_date';
        if (empty($user->subdivision_code))
            $passport[] = 'subdivision_code';
        if (empty($user->passport_issued))
            $passport[] = 'passport_issued';
        
        if (!empty($passport))
            $response['passport'] = $passport;

        $files = array();
        $isset_files = array();
        foreach ($this->get_files(array('user_id' => $user->id)) as $f)
            $isset_files[$f->type] = $f;
        
        if (empty($isset_files['passport1']))
            $files[] = 'passport1';
        if (empty($isset_files['passport2']))
            $files[] = 'passport2';
        if (empty($isset_files['card']))
            $files[] = 'card';
        if (empty($isset_files['face']))
            $files[] = 'face';
        
        if (!empty($files))
            $response['files'] = $files;

        return $response;
    }
    
    
    /**
     * Users::save_loan_history()
     * Сохраняем кредитную историю полученную из 1с
     * 
     * @param integer $user_id
     * @param array $credits_history
     * @return void
     */
    public function save_loan_history($user_id, $credits_history)
    {
        $loan_history = array();
        if (!empty($credits_history))
        {
            foreach ($credits_history as $credits_history_item)
            {
                $loan_history_item = new StdClass();
                
                $loan_history_item->date = $credits_history_item->ДатаЗайма;
                $loan_history_item->close_date = $credits_history_item->ДатаЗакрытия;
                $loan_history_item->number = $credits_history_item->НомерЗайма;
                $loan_history_item->amount = $credits_history_item->СуммаЗайма;
                $loan_history_item->loan_body_summ = $credits_history_item->ОстатокОД;
                $loan_history_item->loan_percents_summ = $credits_history_item->ОстатокПроцентов;
                $loan_history_item->sold = $credits_history_item->Продан;
                $loan_history_item->total_paid = $credits_history_item->ОплатаПроцентов;
                
                $loan_history[] = $loan_history_item;
/*                
                if (!empty($loan_history_item->close_date))
                {
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($loan_history_item);echo '</pre><hr />';
                    if ($current_contract = $this->contracts->get_number_contract($loan_history_item->number))
                    {
                        if ($current_contract->type == 'onec' && empty($current_contract->sud))
                        {
                            $this->contracts->update_contract($current_contract->id, array(
                                'status' => 3,
                                'close_date' => date('Y-m-d H:i:s', strtotime($loan_history_item->close_date))
                            ));
                            $this->orders->update_order($current_contract->order_id, array(
                                'status' => 7
                            ));
                        }
                    }
                }
*/
            }
        }

        $this->users->update_user($user_id, array('loan_history' => json_encode($loan_history)));
        
        return $loan_history;
    }
    
    public function get_phone_user($phone)
    {
        $query = $this->db->placehold("
            SELECT id FROM __users WHERE phone_mobile = ?
        ", (string)$phone);
        $this->db->query($query);
        
        return $this->db->result('id');
    }

    public function get_uid_user($uid)
    {
        $query = $this->db->placehold("
            SELECT id FROM __users WHERE UID = ?
        ", (string)$uid);
        $this->db->query($query);
        
        return $this->db->result('id');
    }

	public function get_user($id)
	{
		$query = $this->db->placehold("
            SELECT * 
            FROM __users
            WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
        if ($result = $this->db->result())
        {
            $result->loan_history = json_decode($result->loan_history);
        }
	
        return $result;
    }
    
	public function get_users($filter = array())
	{
		$id_filter = '';
        $keyword_filter = '';
        $search_filter = '';
        $limit = 1000;
		$page = 1;
        $sort = 'id DESC';
        
        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND id IN (?@)", array_map('intval', (array)$filter['id']));
        
		if(isset($filter['keyword']))
		{
			$keywords = explode(' ', $filter['keyword']);
			foreach($keywords as $keyword)
				$keyword_filter .= $this->db->placehold('
                    AND (
                        firstname LIKE "%'.$this->db->escape(trim($keyword)).'%" 
                        OR lastname LIKE "%'.$this->db->escape(trim($keyword)).'%" 
                        OR patronymic LIKE "%'.$this->db->escape(trim($keyword)).'%" 
                        OR phone_mobile LIKE "%'.$this->db->escape(trim($keyword)).'%" 
                        OR email LIKE "%'.$this->db->escape(trim($keyword)).'%" 
                    )
                ');
		}
        
        if (!empty($filter['search']))
        {
            if (!empty($filter['search']['user_id']))
                $search_filter .= $this->db->placehold(' AND id = ?', (int)$filter['search']['user_id']);
            if (!empty($filter['search']['created']))
                $search_filter .= $this->db->placehold(' AND DATE(created) = ?', date('Y-m-d', strtotime($filter['search']['created'])));
            if (!empty($filter['search']['fio']))
            {
                $fio_filter = array();
                $expls = array_map('trim', explode(' ', $filter['search']['fio']));
                $search_filter .= $this->db->placehold(' AND (');
                foreach ($expls as $expl)
                {
                    $expl = $this->db->escape($expl);
                    $fio_filter[] = $this->db->placehold("(lastname LIKE '%".$expl."%' OR firstname LIKE '%".$expl."%' OR patronymic LIKE '%".$expl."%')");
                }
                $search_filter .= implode(' AND ', $fio_filter);
                $search_filter .= $this->db->placehold(')');
            }
            if (!empty($filter['search']['phone']))
                $search_filter .= $this->db->placehold(" AND phone_mobile LIKE '%".$this->db->escape(str_replace(array(' ', '-', '(', ')', '+'), '', $filter['search']['phone']))."%'");
            if (!empty($filter['search']['email']))
                $search_filter .= $this->db->placehold(" AND email LIKE '%".$this->db->escape($filter['search']['email'])."%'");
        }
        
        if (!empty($filter['sort']))
        {
            switch ($filter['sort']):
                
                case 'id_desc':
                    $sort = 'id DESC';
                break;
                
                case 'id_asc':
                    $sort = 'id ASC';
                break;
                
                case 'date_desc':
                    $sort = 'created DESC';
                break;
                
                case 'date_asc':
                    $sort = 'created ASC';
                break;
                
                case 'fio_desc':
                    $sort = 'lastname DESC, firstname DESC, patronymic DESC';
                break;
                
                case 'fio_asc':
                    $sort = 'lastname ASC, firstname ASC, patronymic ASC';
                break;
                
                case 'email_desc':
                    $sort = 'email DESC';
                break;
                
                case 'email_asc':
                    $sort = 'email ASC';
                break;
                
                case 'phone_desc':
                    $sort = 'phone_mobile DESC';
                break;
                
                case 'phone_asc':
                    $sort = 'phone_mobile ASC';
                break;
                
            endswitch;
        }
        
		if(isset($filter['limit']))
			$limit = max(1, intval($filter['limit']));

		if(isset($filter['page']))
			$page = max(1, intval($filter['page']));
            
        $sql_limit = $this->db->placehold(' LIMIT ?, ? ', ($page-1)*$limit, $limit);

        $query = $this->db->placehold("
            SELECT * 
            FROM __users
            WHERE 1
                $id_filter
                $search_filter
                $keyword_filter
            ORDER BY $sort
            $sql_limit
        ");
        $this->db->query($query);
        if ($results = $this->db->results())
        {
            foreach ($results as $result)
            {
                $result->loan_history = json_decode($result->loan_history);
            }
        }
        
        return $results;
	}
    
	public function count_users($filter = array())
	{
        $id_filter = '';
        $keyword_filter = '';
        $search_filter = '';
        
        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND id IN (?@)", array_map('intval', (array)$filter['id']));
		
        if(isset($filter['keyword']))
		{
			$keywords = explode(' ', $filter['keyword']);
			foreach($keywords as $keyword)
				$keyword_filter .= $this->db->placehold('
                    AND (
                        firstname LIKE "%'.$this->db->escape(trim($keyword)).'%" 
                        OR lastname LIKE "%'.$this->db->escape(trim($keyword)).'%" 
                        OR patronymic LIKE "%'.$this->db->escape(trim($keyword)).'%" 
                        OR phone_mobile LIKE "%'.$this->db->escape(trim($keyword)).'%" 
                        OR email LIKE "%'.$this->db->escape(trim($keyword)).'%" 
                    )
                ');
		}
                
        if (!empty($filter['search']))
        {
            if (!empty($filter['search']['user_id']))
                $search_filter .= $this->db->placehold(' AND id = ?', (int)$filter['search']['user_id']);
            if (!empty($filter['search']['created']))
                $search_filter .= $this->db->placehold(' AND DATE(created) = ?', date('Y-m-d', strtotime($filter['search']['created'])));
            if (!empty($filter['search']['fio']))
            {
                $fio_filter = array();
                $expls = array_map('trim', explode(' ', $filter['search']['fio']));
                $search_filter .= $this->db->placehold(' AND (');
                foreach ($expls as $expl)
                {
                    $expl = $this->db->escape($expl);
                    $fio_filter[] = $this->db->placehold("(lastname LIKE '%".$expl."%' OR firstname LIKE '%".$expl."%' OR patronymic LIKE '%".$expl."%')");
                }
                $search_filter .= implode(' AND ', $fio_filter);
                $search_filter .= $this->db->placehold(')');
            }
            if (!empty($filter['search']['phone']))
                $search_filter .= $this->db->placehold(" AND phone_mobile LIKE '%".$this->db->escape(str_replace(array(' ', '-', '(', ')', '+'), '', $filter['search']['phone']))."%'");
            if (!empty($filter['search']['email']))
                $search_filter .= $this->db->placehold(" AND email LIKE '%".$this->db->escape($filter['search']['email'])."%'");
        }
        
		$query = $this->db->placehold("
            SELECT COUNT(id) AS count
            FROM __users
            WHERE 1
                $id_filter
                $search_filter
                $keyword_filter
        ");
        $this->db->query($query);
        $count = $this->db->result('count');
	
        return $count;
    }
    
    public function add_user($user)
    {
        $user = (array)$user;
        
        if (!empty($user['password']))
            $user['password'] = $this->hash_password($user['password']);
        
		$query = $this->db->placehold("
            INSERT INTO __users SET ?%
        ", (array)$user);
        $this->db->query($query);
        $id = $this->db->insert_id();

        return $id;
    }
    
    public function update_user($id, $user)
    {
        $user = (array)$user;
        
        if (!empty($user['password']))
            $user['password'] = $this->hash_password($user['password']);
        
		$query = $this->db->placehold("
            UPDATE __users SET ?% WHERE id = ?
        ", $user, (int)$id);
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($query);echo '</pre><hr />';        
        $this->db->query($query);
        return $id;
    }
    
    public function delete_user($id)
    {
		$query = $this->db->placehold("
            DELETE FROM __users WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
    }
    
       	public function get_file($id)
	{
		$query = $this->db->placehold("
            SELECT * 
            FROM __files
            WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
        $result = $this->db->result();
	
        return $result;
    }
    
	public function get_files($filter = array())
	{
		$id_filter = '';
        $user_id_filter = '';
        $status_filter = '';
        $type_filter = '';
        
        if (!empty($filter['id']))
            $id_filter = $this->db->placehold("AND id IN (?@)", array_map('intval', (array)$filter['id']));
        
        if (!empty($filter['user_id']))
            $user_id_filter = $this->db->placehold("AND user_id = ?", (int)$filter['user_id']);
            
        if (isset($filter['status']))
            $status_filter = $this->db->placehold("AND status = ?", (int)$filter['status']);
        
        if (isset($filter['type']))
            $type_filter = $this->db->placehold("AND type = ?", (string)$filter['type']);
        
        $query = $this->db->placehold("
            SELECT * 
            FROM __files
            WHERE 1
                $id_filter
                $user_id_filter
                $status_filter
                $type_filter
            ORDER BY id ASC 
        ");
        $this->db->query($query);
        $results = $this->db->results();
        
        return $results;
	}
    
    public function add_file($file)
    {
		$query = $this->db->placehold("
            INSERT INTO __files SET ?%, created = '".date('Y-m-d H:i:s')."'
        ", (array)$file);
        $this->db->query($query);
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    public function update_file($id, $file)
    {
		$query = $this->db->placehold("
            UPDATE __files SET ?% WHERE id = ?
        ", (array)$file, (int)$id);
        $this->db->query($query);
        
        return $id;
    }
    
    public function delete_file($id)
    {
		if ($file = $this->get_file($id))
        {
            if (file_exists($this->config->root_dir.$this->config->users_files_dir.$file->name))
                unlink($this->config->root_dir.$this->config->users_files_dir.$file->name);

            if (file_exists($this->config->root_dir.$this->config->original_images_dir.$file->name))
                unlink($this->config->root_dir.$this->config->original_images_dir.$file->name);
            
            // Удалить все ресайзы
            $filename = pathinfo($file->name, PATHINFO_FILENAME);
			$ext = pathinfo($file->name, PATHINFO_EXTENSION);

			$rezised_images = glob($this->config->root_dir.$this->config->resized_images_dir.$filename.".*x*.".$ext);
			if(is_array($rezised_images)) {
    			foreach (glob($this->config->root_dir.$this->config->resized_images_dir.$filename.".*x*.".$ext) as $f)
    				@unlink($f);
            }
            
            $query = $this->db->placehold("
                DELETE FROM __files WHERE id = ?
            ", (int)$id);
            $this->db->query($query);
            
        }
    }

    public function check_filename($filename)
    {
        $this->db->query("SELECT id FROM __files WHERE name = ?");
        return $this->db->result('id');
    }
    
    public function hash_password($password)
    {
		return md5($this->salt.$password.sha1($password));
    }
    
	public function check_password($phone, $password)
	{
        $clean_phone = $this->sms->clear_phone($phone);
        $encpassword = $this->hash_password($password);
        
		$query = $this->db->placehold("
            SELECT id 
            FROM __users 
            WHERE phone_mobile = ? 
            AND password = ? 
            LIMIT 1
        ", $clean_phone, $encpassword);
		$this->db->query($query);
        
		return $this->db->result('id');
	}
}