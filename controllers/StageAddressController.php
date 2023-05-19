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
                $faktaddress['adressfull'] = $this->request->post('Faktaddressfull');
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

            $UTC2 = ["Калининградская"];

            $UTC3 = ["Москва", "Санкт-Петербург", "Севастополь", "Архангельская", 
            "Белгородская", "Брянская", "Владимирская", "Волгоградская", "Вологодская", 
            "Воронежская", "Ивановская", "Калужская", "Кировская", "Костромская", 
            "Курская", "Ленинградская", "Липецкая", "Московская", "Мурманская", 
            "Нижегородская", "Новгородская", "Орловская", "Пензенская", "Псковская", 
            "Ростовская", "Рязанская", "Смоленская", "Тамбовская", "Тверская", 
            "Тульская", "Ярославская", "Краснодарский", "Ставропольский", "Адыгея", 
            "Дагестан", "Ингушетия", "Кабардино-Балкарская", "Калмыкия", "Карачаево-Черкесская", 
            "Карелия", "Коми", "Крым", "Марий Эл", "Мордовия", 
            "Северная Осетия - Алания", "Татарстан", "Чеченская", "Чувашская республика", "Ненецкий"];

            $UTC4 = ["Астраханская", "Самарская", "Саратовская",
            "Ульяновская", "Удмуртская"];

            $UTC5 = ["Курганская", "Оренбургская", "Свердловская", "Тюменская", "Челябинская",
            "Пермский", "Башкортостан", "Ханты-Мансийский Автономный округ - Югра", "Ямало-Ненецкий"];
            
            $UTC6 = ["Омская"];
            
            $UTC7 = ["Кемеровская", "Новосибирская", "Томская", "Алтайский", "Красноярский", 
            "Алтай", "Тыва", "Хакасия"];
            
            $UTC8 = ["Иркутская", "Бурятия"];
            
            $UTC9 = ["Амурская", "Забайкальский"];
            
            $UTC10 = ["Саха /Якутия/", "Приморский", "Хабаровский", "Еврейская"];

            $UTC11 = ["Магаданская", "Сахалинская"];
            
            $UTC12 = ["Камчатский", "Чукотский"];

            if(!$faktaddress['region']){
                $adressfull = $faktaddress['adressfull'];
                $Fakt_adress = json_decode($this->dadata->get_all($adressfull))->suggestions[0];
                $faktaddress['region'] = $Fakt_adress->data->region;
            }

            if (in_array($faktaddress['region'], $UTC2))
                $UTC = 'UTC+2';
            else if(in_array($faktaddress['region'], $UTC3))
                $UTC = 'UTC+3';
            else if(in_array($faktaddress['region'], $UTC4))
                $UTC = 'UTC+4';
            else if(in_array($faktaddress['region'], $UTC5))
                $UTC = 'UTC+5';
            else if(in_array($faktaddress['region'], $UTC6))
                $UTC = 'UTC+6';
            else if(in_array($faktaddress['region'], $UTC7))
                $UTC = 'UTC+7';
            else if(in_array($faktaddress['region'], $UTC8))
                $UTC = 'UTC+8';
            else if(in_array($faktaddress['region'], $UTC9))
                $UTC = 'UTC+9';
            else if(in_array($faktaddress['region'], $UTC10))
                $UTC = 'UTC+10';
            else if(in_array($faktaddress['region'], $UTC11))
                $UTC = 'UTC+11';
            else if(in_array($faktaddress['region'], $UTC12))
                $UTC = 'UTC+12';
            else
                $UTC = null;


            $user = $this->users->get_user($this->user->id);

            $Faktaddressfull = $this->Addresses->get_address($user->faktaddress_id);
            $this->design->assign('Faktaddressfull', $Faktaddressfull);

            $Regaddressfull = $this->Addresses->get_address($user->regaddress_id);
            $this->design->assign('Regaddressfull', $Regaddressfull);

            $errors = array();

            if (empty($regaddress['adressfull'])) {
                $errors[] = 'empty_regregion';
            }

            if (empty($faktaddress['adressfull'])) {
                $errors[] = 'empty_faktregion';
            }

            $this->design->assign('errors', $errors);

            if (empty($errors)) {

                $regaddress_id = $this->Addresses->add_address($regaddress);
                $faktaddress_id = $this->Addresses->add_address($faktaddress);
                $this->users->update_user($this->user->id, array('regaddress_id' => $regaddress_id, 'faktaddress_id' => $faktaddress_id, 'stage_address' => 1, 'time_zone' => $UTC));

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