<?php

class StageContactPersonController extends Controller
{
    public function fetch()
    {
        if (empty($this->user))
        {
            header('Location: /lk/login');
            exit;
        }
        
        /*if (!empty($this->user->stage_personal))
        {
            header('Location: /stage/passport');
            exit;
        }*/
        
        $errors = array();    
        if ($this->request->method('post'))
        {
            $fio = trim((string)$this->request->post('fio'));
            $phonePersons = trim((string)$this->request->post('phonePersons'));
            $relation = trim((string)$this->request->post('relation'));
            /*$email = trim((string)$this->request->post('email'));
            $gender = trim((string)$this->request->post('gender'));
            $birth = trim((string)$this->request->post('birth'));
            $birth_place = trim((string)$this->request->post('birth_place'));
            $social = trim((string)$this->request->post('social'));*/

            $this->design->assign('fio', $fio);
            $this->design->assign('phonePersons', $phonePersons);
            $this->design->assign('relation', $relation);
            /*$this->design->assign('email', $email);
            $this->design->assign('gender', $gender);
            $this->design->assign('birth', $birth);
            $this->design->assign('birth_place', $birth_place);
            $this->design->assign('social', $social);*/
            
            
            if (empty($fio))
                $errors[] = 'empty_fio';
            if (empty($phonePersons))
                $errors[] = 'empty_phonePersons';
            if (empty($relation))
                $errors[] = 'empty_relation';
            /*if (empty($email))
                $errors[] = 'empty_email';
            if (empty($gender))
                $errors[] = 'empty_gender';
            if (empty($birth))
                $errors[] = 'empty_birth';
            if (empty($birth_place))
                $errors[] = 'empty_birth_place';*/

            /*$min_birth = (date('Y') - 16).'-'.date('m-d');
            if (strtotime($min_birth) < strtotime($birth)) 
                $errors[] = 'bad_birth';*/
                
            
            if (empty($errors))
            {
                /*$update = array(
                    'lastname' => $lastname,
                    'firstname' => $firstname,
                    'patronymic' => $patronymic,
                    'email' => $email,
                    'gender' => $gender,
                    'birth' => $birth,
                    'birth_place' => $birth_place,
                    'social' => $social,
                    'stage_personal' => 1,
                );

                $update = array_map('strip_tags', $update);

                $this->users->update_user($this->user->id, $update);
            
                header('Location: /stage/passport');
                exit;*/

                $id = $this->user->id;

                // $fio = strtoupper($this->request->post('fio'));
                // $phone = trim($this->request->post('phonePersons'));
                // $relation = $this->request->post('relation');
                // $comment = $this->request->post('comment');
                $phone = $phonePersons;
                $comment = '';
        
                $contact =
                    [
                        'name' => $fio,
                        'phone' => $phone,
                        'relation' => $relation,
                        'comment' => $comment
                    ];
        
                $result =$this->Contactpersons->update_contactperson($id, $contact);


                // $this->json_output(array(
                //     'success' => 1,
                //     'created' => date('d.m.Y H:i:s'),
                //     // 'text' => (string) $document_id,
                //     'text' => json_encode($result),
                //     // 'official' => $official,
                //     // 'manager_name' => $this->manager->name,
                //   ));
                  $this->json_output(array(
                    'success' => 1,
                    'created' => date('d.m.Y H:i:s'),
                    // 'text' => (string) $document_id,
                    //'text' => $this->user,
                    'text' => 'проверка',
                    // 'official' => $official,
                    // 'manager_name' => $this->manager->name,
                  ));

                //header('Location: /stage/personal');
                //exit;
            }            
        }
        else
        {
            $this->design->assign('fio', $this->user->fio);
            /*$this->design->assign('firstname', $this->user->firstname);
            $this->design->assign('patronymic', $this->user->patronymic);
            $this->design->assign('email', $this->user->email);
            $this->design->assign('gender', $this->user->gender);
            $this->design->assign('birth', $this->user->birth);
            $this->design->assign('birth_place', $this->user->birth_place);
            $this->design->assign('social', $this->user->social);*/
            
        }
    	
        $this->design->assign('errors', $errors);
        
        return $this->design->fetch('stage/contact_persons.tpl');
    }
    
}