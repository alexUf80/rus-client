<?php

class StagePassportController extends Controller
{
    public function fetch()
    {
        if (empty($this->user))
        {
            header('Location: /lk/login');
            exit;
        }
        
        if (!empty($this->user->stage_passport))
        {
            header('Location: /stage/address');
            exit;
        }
        
        if (empty($this->user->stage_personal))
        {
            header('Location: /stage/personal');
            exit;
        }
        
        if ($this->request->get('step') == 'prev')
        {
            $this->users->update_user($this->user->id, array('stage_personal'=>0));
            header('Location: /stage/personal');
            exit;
        }
        
        if ($this->request->method('post'))
        {
            $passport_serial = (string)$this->request->post('passport_serial');
            $passport_date = (string)$this->request->post('passport_date');
            $passport_issued = (string)$this->request->post('passport_issued');
            $subdivision_code = (string)$this->request->post('subdivision_code');
            $snils = $this->request->post('snils');

            $this->design->assign('passport_serial', $passport_serial);
            $this->design->assign('passport_date', $passport_date);
            $this->design->assign('passport_issued', $passport_issued);
            $this->design->assign('subdivision_code', $subdivision_code);
            $this->design->assign('snils', $snils);
            
            
            $errors = array();
            
            if (empty($passport_serial))
                $errors[] = 'empty_passport_serial';
            if (empty($passport_date))
                $errors[] = 'empty_passport_date';
            if (empty($passport_issued))
                $errors[] = 'empty_passport_issued';
            if (empty($subdivision_code))
                $errors[] = 'empty_subdivision_code';
            
            // проверяем по базе его паспортные данные
            if ($found_id = $this->users->find_clone($passport_serial, $this->user->lastname, $this->user->firstname, $this->user->patronymic, $this->user->birth))
            {
                if ($found_id != $this->user->id)
                    $errors[] = 'passport_found';
            }

            $this->design->assign('errors', $errors);
            
            if (empty($errors))
            {
                $update = array(
                    'passport_serial' => $passport_serial,
                    'passport_date' => $passport_date,
                    'passport_issued' => $passport_issued,
                    'subdivision_code' => $subdivision_code,
                    'snils' => $snils,
                    'stage_passport' => 1,
                );

                $update = array_map('strip_tags', $update);

                $this->users->update_user($this->user->id, $update);
            
                header('Location: /stage/address');
                exit;
            }            
        }
        else
        {
            $this->design->assign('passport_serial', $this->user->passport_serial);
            $this->design->assign('passport_date', $this->user->passport_date);
            $this->design->assign('passport_issued', $this->user->passport_issued);
            $this->design->assign('subdivision_code', $this->user->subdivision_code);
            $this->design->assign('snils', $this->user->snils);
        }

    	return $this->design->fetch('stage/passport.tpl');
    }
    
}