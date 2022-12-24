<?php
error_reporting(-1);
ini_set('display_errors', 'On');

chdir('..');

require 'autoload.php';

class AuditCron extends Core
{
    public function __construct()
    {
    	parent::__construct();
    }
    
    public function run()
    {
    	$audits = $this->scorings->get_audits(array('status'=>'new'));
        foreach ($audits as $audit)
        {
//            $this->scorings->update_audit($audit->id, array('types'=>array('fms', 'fssp', 'location', 'local_time', 'scorista')));
            $this->run_audit($audit);
        }
    }
    
    public function run_audit($audit)
    {
    	foreach ($audit->types as $type)
        {
            $scoring_type = $this->scorings->get_type($type);
            
            $classname = $type."_scoring";
            $scoring_type_result = $this->$classname->run($audit->id, $audit->user_id, $audit->order_id);
            
            if (!$scoring_type_result && $scoring_type->negative_action == 'stop')
            {
                $this->scorings->update_audit($audit->id, array('status'=>'stopped'));
                return false;
            }
            
        }
        
        $this->scorings->update_audit($audit->id, array('status'=>'completed'));
        return true;
    }
    
}

$cron = new AuditCron();
$cron->run();
