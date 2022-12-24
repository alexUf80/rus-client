<?php

class StageFilesController extends Controller
{
    public function fetch()
    {
        if (empty($this->user))
        {
            header('Location: /lk/login');
            exit;
        }
        
        if (!empty($this->user->stage_files))
        {
            header('Location: /stage/card');
            exit;
        }
        
        if (empty($this->user->stage_work))
        {
            header('Location: /stage/work');
            exit;
        }
        
        if ($this->request->get('step') == 'prev')
        {
            $this->users->update_user($this->user->id, array('stage_work'=>0));
            header('Location: /stage/work');
            exit;
        }
        
        if ($this->request->method('post'))
        {

            if (empty($errors))
            {
                $update = array(
                    'stage_files' => 1,
                );

                $this->users->update_user($this->user->id, $update);
            
                header('Location: /stage/card');
                exit;
            }            
        }
        else
        {
            
        }
            
        $user_files = array();
        $other_user_files = array();
        
        $have_reject_files = 0;
        
        foreach ($this->users->get_files(array('user_id'=>$this->user->id)) as $f)
        {
            if ($f->status == 3)
                $have_reject_files = 1;
            
            if (in_array($f->type, array('passport1', 'passport2', 'face', 'card')))
                $user_files[$f->type] = $f;
            else
                $other_user_files[] = $f;
        }
        $this->design->assign('user_files', $user_files);
        $this->design->assign('other_user_files', $other_user_files);
        $this->design->assign('have_reject_files', $have_reject_files);

    	return $this->design->fetch('stage/files.tpl');
    }
    
}