<?php

class Managers extends Core
{
    private $salt = '0c7540eb7e65b553ec1ba6b20de79608';

	public function get_manager($id)
	{
		$query = $this->db->placehold("
            SELECT * 
            FROM __managers
            WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
        $result = $this->db->result();
	
        return $result;
    }
    
	public function get_managers($filter = array())
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
            FROM __managers
            WHERE 1
                $id_filter
 	           $keyword_filter
            ORDER BY id ASC 
            $sql_limit
        ");
        $this->db->query($query);
        $results = $this->db->results();
        
        return $results;
	}
    
	public function count_managers($filter = array())
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
            FROM __managers
            WHERE 1
                $id_filter
                $keyword_filter
        ");
        $this->db->query($query);
        $count = $this->db->result('count');
	
        return $count;
    }
    
    public function add_manager($manager)
    {
		$manager = (array)$manager;
        
        if (!empty($manager['password']))
            $manager['password'] = $this->hash_password($manager['password']);
            
		$query = $this->db->placehold("
            INSERT INTO __managers SET ?%
        ", (array)$manager);
        $this->db->query($query);
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    public function update_manager($id, $manager)
    {
		$manager = (array)$manager;
        
        if (!empty($manager['password']))
            $manager['password'] = $this->hash_password($manager['password']);
        
        $query = $this->db->placehold("
            UPDATE __managers SET ?% WHERE id = ?
        ", (array)$manager, (int)$id);
        $this->db->query($query);

        return $id;
    }
    
    public function delete_manager($id)
    {
		$query = $this->db->placehold("
            DELETE FROM __managers WHERE id = ?
        ", (int)$id);
        $this->db->query($query);
    }
    
    public function get_roles()
    {
        $roles = array(
            'developer' => 'Разработчик',
            'admin' => 'Администратор',
            'user' => 'Менеджер',
            'verificator' => 'Верификатор',
            'analitic' => 'Аналитик',
            'technic' => 'Импорт из 1С',
        );
        
        return $roles;
    }
    
    public function get_permissions($role)
    {
        $roles = $this->get_roles();
        
        if (!isset($roles[$role]))
            throw new Exception('Неизвестная роль пользователя: '.$role);
        
        $list_permissions = array(
            'managers' => array('developer', 'admin'), // просмотр менеджеров
            'create_managers' => array('developer', 'admin'), // создание и редактирование менеджеров
            
        );
        
        $access_permissions = array();
        foreach ($list_permissions  as $permission => $permission_roles)
            if (in_array($role, $permission_roles))
                $access_permissions[] = $permission;
        
        return $access_permissions;
    }

    public function check_password($login, $password)
    {
        $password = $this->hash_password($password);
        
    	$query = $this->db->placehold("
            SELECT id 
            FROM __managers 
            WHERE login = ?
            AND password = ?
        ", $login, $password);
        $this->db->query($query);
        
        return $this->db->result('id');        
    }
    
    private function hash_password($password)
    {
        return md5(sha1($this->salt.$password).$this->salt);
    }
}