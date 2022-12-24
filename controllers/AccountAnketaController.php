<?php

class AccountAnketaController extends Controller
{
    public function fetch()
    {
        if (empty($this->user))
        {
            header('Location: /lk/login');
            exit;
        }
    	
        if ($this->request->method('post'))
        {
            if (!empty($_SESSION['looker_mode']))
                return false;
            
            $update = array();

            if ($lastname = $this->request->post('lastname'))
                $update['lastname'] = $lastname;
            if ($firstname = $this->request->post('firstname'))
                $update['firstname'] = $firstname;
            if ($patronymic = $this->request->post('patronymic'))
                $update['patronymic'] = $patronymic;
            if ($email = $this->request->post('email'))
                $update['email'] = $email;
            if ($gender = $this->request->post('gender'))
                $update['gender'] = $gender;
            if ($birth = $this->request->post('birth'))
                $update['birth'] = $birth;
            if ($birth_place = $this->request->post('birth_place'))
                $update['birth_place'] = $birth_place;
            
            if ($passport_serial = $this->request->post('passport_serial'))
                $update['passport_serial'] = $passport_serial;
            if ($passport_date = $this->request->post('passport_date'))
                $update['passport_date'] = $passport_date;
            if ($subdivision_code = $this->request->post('subdivision_code'))
                $update['subdivision_code'] = $subdivision_code;
            if ($passport_issued = $this->request->post('passport_issued'))
                $update['passport_issued'] = $passport_issued;
            
            if ($Faktindex = $this->request->post('Faktindex'))
                $update['Faktindex'] = $Faktindex;
            if ($Faktregion = $this->request->post('Faktregion'))
                $update['Faktregion'] = $Faktregion;
            if ($Faktcity = $this->request->post('Faktcity'))
                $update['Faktcity'] = $Faktcity;
            if ($Faktdistrict = $this->request->post('Faktdistrict'))
                $update['Faktdistrict'] = $Faktdistrict;
            if ($Faktdistrict_shorttype = $this->request->post('Faktdistrict_shorttype'))
                $update['Faktdistrict_shorttype'] = $Faktdistrict_shorttype;
            if ($Faktlocality = $this->request->post('Faktlocality'))
                $update['Faktlocality'] = $Faktlocality;
            if ($Faktlocality_shorttype = $this->request->post('Faktlocality_shorttype'))
                $update['Faktlocality_shorttype'] = $Faktlocality_shorttype;
            if ($Faktstreet = $this->request->post('Faktstreet'))
                $update['Faktstreet'] = $Faktstreet;
            if ($Fakthousing = $this->request->post('Fakthousing'))
                $update['Fakthousing'] = $Fakthousing;
            if ($Faktbuilding = $this->request->post('Faktbuilding'))
                $update['Faktbuilding'] = $Faktbuilding;
            if ($Faktroom = $this->request->post('Faktroom'))
                $update['Faktroom'] = $Faktroom;
            if ($Faktregion_shorttype = $this->request->post('Faktregion_shorttype'))
                $update['Faktregion_shorttype'] = $Faktregion_shorttype;
            if ($Faktcity_shorttype = $this->request->post('Faktcity_shorttype'))
                $update['Faktcity_shorttype'] = $Faktcity_shorttype;
            if ($Faktstreet_shorttype = $this->request->post('Faktstreet_shorttype'))
                $update['Faktstreet_shorttype'] = $Faktstreet_shorttype;
            
            if ($this->request->post('clone_address', 'integer'))
            {
                $Regindex = $Faktindex;
                $Regregion = $Faktregion;
                $Regcity = $Faktcity;
                $Regdistrict = $Faktdistrict;
                $Regdistrict_shorttype = $Faktdistrict_shorttype;
                $Reglocality = $Faktlocality;
                $Reglocality_shorttype = $Faktlocality_shorttype;
                $Regstreet = $Faktstreet;
                $Reghousing = $Fakthousing;
                $Regbuilding = $Faktbuilding;
                $Regroom = $Faktroom;
                $Regregion_shorttype = $Faktregion_shorttype;
                $Regcity_shorttype = $Faktcity_shorttype;
                $Regstreet_shorttype = $Faktstreet_shorttype;
            }
            else
            {
                if ($Regindex = $this->request->post('Regindex'))
                    $update['Regindex'] = $Regindex;
                if ($Regregion = $this->request->post('Regregion'))
                    $update['Regregion'] = $Regregion;
                if ($Regcity = $this->request->post('Regcity'))
                    $update['Regcity'] = $Regcity;
                if ($Regdistrict = $this->request->post('Regdistrict'))
                    $update['Regdistrict'] = $Regdistrict;
                if ($Regdistrict_shorttype = $this->request->post('Regdistrict_shorttype'))
                    $update['Regdistrict_shorttype'] = $Regdistrict_shorttype;
                if ($Reglocality = $this->request->post('Reglocality'))
                    $update['Reglocality'] = $Reglocality;
                if ($Reglocality_shorttype = $this->request->post('Reglocality_shorttype'))
                    $update['Reglocality_shorttype'] = $Reglocality_shorttype;
                if ($Regstreet = $this->request->post('Regstreet'))
                    $update['Regstreet'] = $Regstreet;
                if ($Reghousing = $this->request->post('Reghousing'))
                    $update['Reghousing'] = $Reghousing;
                if ($Regbuilding = $this->request->post('Regbuilding'))
                    $update['Regbuilding'] = $Regbuilding;
                if ($Regroom = $this->request->post('Regroom'))
                    $update['Regroom'] = $Regroom;
                if ($Regregion_shorttype = $this->request->post('Regregion_shorttype'))
                    $update['Regregion_shorttype'] = $Regregion_shorttype;
                if ($Regcity_shorttype = $this->request->post('Regcity_shorttype'))
                    $update['Regcity_shorttype'] = $Regcity_shorttype;
                if ($Regstreet_shorttype = $this->request->post('Regstreet_shorttype'))
                    $update['Regstreet_shorttype'] = $Regstreet_shorttype;
            }
  

            if ($workplace = $this->request->post('workplace'))
                $update['workplace'] = $workplace;
            if ($profession = $this->request->post('profession'))
                $update['profession'] = $profession;
            if ($workphone = $this->request->post('workphone'))
                $update['workphone'] = $workphone;
            if ($workaddress = $this->request->post('workaddress'))
                $update['workaddress'] = $workaddress;
            if ($income = $this->request->post('income'))
                $update['income'] = $income;
            if ($expenses = $this->request->post('expenses'))
                $update['expenses'] = $expenses;
            if ($chief_name = $this->request->post('chief_name'))
                $update['chief_name'] = $chief_name;
            if ($chief_position = $this->request->post('chief_position'))
                $update['chief_position'] = $chief_position;
            if ($chief_phone = $this->request->post('chief_phone'))
                $update['chief_phone'] = $chief_phone;


            if ($contact_person_name = $this->request->post('contact_person_name'))
                $update['contact_person_name'] = $contact_person_name;
            if ($contact_person_phone = $this->request->post('contact_person_phone'))
                $update['contact_person_phone'] = $contact_person_phone;
            if ($contact_person_relation = $this->request->post('contact_person_relation'))
                $update['contact_person_relation'] = $contact_person_relation;
            if ($contact_person2_name = $this->request->post('contact_person2_name'))
                $update['contact_person2_name'] = $contact_person2_name;
            if ($contact_person2_phone = $this->request->post('contact_person2_phone'))
                $update['contact_person2_phone'] = $contact_person2_phone;
            if ($contact_person2_relation = $this->request->post('contact_person2_relation'))
                $update['contact_person2_relation'] = $contact_person2_relation;
            


            if (!empty($update))
            {
                $update = array_map('strip_tags', $update);

                $this->users->update_user($this->user->id, $update);
            }
            
            
            $this->user = $this->users->get_user((int)$this->user->id);
        }
        
        $need_fields = $this->users->check_fields($this->user);
        if (empty($need_fields))
        {
            header('Location: '.$this->config->root_url.'/account');
            exit;
        }
// 70000210609        
        $this->design->assign('need_fields', $need_fields);
        
//echo __FILE__.' '.__LINE__.'<br /><pre>';var_dump($need_fields);echo '</pre><hr />';        
        
        return $this->design->fetch('account/anketa.tpl');
    }
}