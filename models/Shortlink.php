<?php

class ShortLink extends Core
{

	public function get_link($url)
	{
		
		$where = $this->db->placehold(' WHERE url=? ', $url);
		
		
		$query = "SELECT id, url, link
		          FROM __short_link $where LIMIT 1";

		$this->db->query($query);
		return $this->db->result();
	}
	
}
