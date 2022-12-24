<?php

class PostBackCron extends Core
{
    public function add($cron)
    {
        $this->db->query("
        INSERT INTO s_postbacks_cron 
        SET ?%
        ", $cron);
    }
}