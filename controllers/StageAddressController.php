<?php

class StageAddressController extends Controller
{
    public function fetch()
    {
        if (empty($this->user)) {
            header('Location: /lk/login');
            exit;
        }

        if (!empty($this->user->stage_address)) {
            header('Location: /stage/work');
            exit;
        }

        if (empty($this->user->stage_passport)) {
            header('Location: /stage/passport');
            exit;
        }

        if ($this->request->get('step') == 'prev') {
            $this->users->update_user($this->user->id, array('stage_passport' => 0));
            header('Location: /stage/passport');
            exit;
        }

        if ($this->request->method('post')) {

            $Regadress = json_decode($this->request->post('Regadress'));

            $regaddress = [];
            $regaddress['adressfull'] = $this->request->post('Regadressfull');
            $regaddress['zip'] = $Regadress->data->postal_code ?? '';
            $regaddress['region'] = $Regadress->data->region ?? '';
            $regaddress['region_type'] = $Regadress->data->region_type ?? '';
            $regaddress['city'] = $Regadress->data->city ?? '';
            $regaddress['city_type'] = $Regadress->data->city_type ?? '';
            $regaddress['district'] = $Regadress->data->city_district ?? '';
            $regaddress['district_type'] = $Regadress->data->city_district_type ?? '';
            $regaddress['locality'] = $Regadress->data->settlement ?? '';
            $regaddress['locality_type'] = $Regadress->data->settlement_type ?? '';
            $regaddress['street'] = $Regadress->data->street ?? '';
            $regaddress['street_type'] = $Regadress->data->street_type ?? '';
            $regaddress['house'] = $Regadress->data->house ?? '';
            $regaddress['building'] = $Regadress->data->block ?? '';
            $regaddress['room'] = $Regadress->data->flat ?? '';
            $regaddress['okato'] = $Regadress->data->okato ?? '';
            $regaddress['oktmo'] = $Regadress->data->oktmo ?? '';

            if ($this->request->post('clone_address', 'integer')) {
                $faktaddress = $regaddress;
            } else {
                $Fakt_adress = json_decode($this->request->post('Fakt_adress'));

                $faktaddress = [];
                $faktaddress['adressfull'] = $this->request->post('Faktadressfull');
                $faktaddress['zip'] = $Fakt_adress->data->postal_code ?? '';
                $faktaddress['region'] = $Fakt_adress->data->region ?? '';
                $faktaddress['region_type'] = $Fakt_adress->data->region_type ?? '';
                $faktaddress['city'] = $Fakt_adress->data->city ?? '';
                $faktaddress['city_type'] = $Fakt_adress->data->city_type ?? '';
                $faktaddress['district'] = $Fakt_adress->data->city_district ?? '';
                $faktaddress['district_type'] = $Fakt_adress->data->city_district_type ?? '';
                $faktaddress['locality'] = $Fakt_adress->data->settlement ?? '';
                $faktaddress['locality_type'] = $Fakt_adress->data->settlement_type ?? '';
                $faktaddress['street'] = $Fakt_adress->data->street ?? '';
                $faktaddress['street_type'] = $Fakt_adress->data->street_type ?? '';
                $faktaddress['house'] = $Fakt_adress->data->house ?? '';
                $faktaddress['building'] = $Fakt_adress->data->block ?? '';
                $faktaddress['room'] = $Fakt_adress->data->flat ?? '';
                $faktaddress['okato'] = $Fakt_adress->data->okato ?? '';
                $faktaddress['oktmo'] = $Fakt_adress->data->oktmo ?? '';
            }

            $user = $this->users->get_user($this->user->id);

            $Faktaddressfull = $this->Addresses->get_address($user->faktaddress_id);
            $this->design->assign('Faktaddressfull', $Faktaddressfull);

            $Regaddressfull = $this->Addresses->get_address($user->regaddress_id);
            $this->design->assign('Regaddressfull', $Regaddressfull);

            $errors = array();

            if (empty($regaddress['adressfull'])) {
                $errors[] = 'empty_regregion';
            }

            $this->design->assign('errors', $errors);

            if (empty($errors)) {

                $regaddress_id = $this->Addresses->add_address($regaddress);
                $faktaddress_id = $this->Addresses->add_address($faktaddress);
                $this->users->update_user($this->user->id, array('regaddress_id' => $regaddress_id, 'faktaddress_id' => $faktaddress_id, 'stage_address' => 1));

                header('Location: /stage/work');
                exit;
            } else {
                $this->design->assign('Faktaddressfull', $faktaddress['adressfull']);
                $this->design->assign('Regaddressfull', $regaddress['adressfull']);
            }
        }

        return $this->design->fetch('stage/address.tpl');
    }

}