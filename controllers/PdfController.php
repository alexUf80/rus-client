<?php

class PdfController extends Controller
{
    public function fetch()
    {
        $type = $this->request->get('type');
        switch ($type):
            
            case 'polis-strahovaniya.pdf':
                $this->polis_strahovaniya();
            break;
            
            case 'anketa-zayavlenie-pep.pdf':
                $this->anketa_zayavlenie_pep();
            break;
            
            case 'dopolnitelnoe-soglashenie-o-prolongatsii.pdf':
                $this->dopolnitelnoe_soglashenie_o_prolongatsii();
            break;
            
            case 'individualnie-usloviya-nl.pdf':
                $this->individualnie_usloviya_nl();
            break;
            
            case 'soglasie-dlya-megafona.pdf':
                $this->soglasie_dlya_megafona();
            break;
            
            case 'soglasie-dlya-skoringa.pdf':
                $this->soglasie_dlya_skoringa();
            break;
            
            case 'soglasie-na-okazanie-dopuslugi-pri-prolongatsii.pdf':
                $this->soglasie_na_okazanie_dopuslugi_pri_prolongatsii();
            break;
            
            case 'soglasie-na-okazanie-dopuslugi-pri-vidache.pdf':
                $this->soglasie_na_okazanie_dopuslugi_pri_vidache();
            break;
            
            case 'soglasie-na-spisanie.pdf':
                $this->soglasie_na_spisanie();
            break;
            
            case 'soglasie-na-vzaimodeystvie.pdf':
                $this->soglasie_na_vzaimodeystvie();
            break;
            
            case 'zayavlenie-na-uslugu-uznay-prichinu-otkaza.pdf':
                $this->zayavlenie_na_uslugu_uznay_prichinu_otkaza();
            break;
            
            default:
                return false;
            
        endswitch;
        
    }
    
    public function polis_strahovaniya()
    {
    	$tpl = $this->design->fetch('pdf/polis-strahovaniya.tpl');
        
        $this->pdf->create($tpl, '', 'polis-strahovaniya.pdf');
    }
    
    public function anketa_zayavlenie_pep()
    {
    	$tpl = $this->design->fetch('pdf/anketa-zayavlenie-pep.tpl');
        
        $this->pdf->create($tpl, '', 'soglasie-dlya-skoringa.pdf');
    }
    
    public function dopolnitelnoe_soglashenie_o_prolongatsii()
    {
    	$tpl = $this->design->fetch('pdf/dopolnitelnoe-soglashenie-o-prolongatsii.tpl');
        
        $this->pdf->create($tpl, '', 'dopolnitelnoe-soglashenie-o-prolongatsii.pdf');
    }
    
    public function individualnie_usloviya_nl()
    {
    	$tpl = $this->design->fetch('pdf/individualnie-usloviya-nl.tpl');
        
        $this->pdf->create($tpl, '', 'individualnie-usloviya-nl.pdf');
    }
    
    public function soglasie_dlya_megafona()
    {
    	$tpl = $this->design->fetch('pdf/soglasie-dlya-megafona.tpl');
        
        $this->pdf->create($tpl, '', 'soglasie-dlya-megafona.pdf');
    }
    
    public function soglasie_dlya_skoringa()
    {
    	$tpl = $this->design->fetch('pdf/soglasie-dlya-skoringa.tpl');
        
        $this->pdf->create($tpl, '', 'soglasie-dlya-skoringa.pdf');
    }
    
    public function soglasie_na_okazanie_dopuslugi_pri_prolongatsii()
    {
        $user_id = $this->request->get('object', 'integer');
        if (!($user = $this->users->get_user($user_id)))
            return false;

        $clientname = $user->lastname.' '.$user->firstname.' '.$user->patronymic;
        $this->design->assign('clientname', $clientname);
        
        $clientpassport = 'Паспорт '.$user->passport_serial.', выдан '.$user->passport_date.' '.$user->passport_issued.' '.$user->subdivision_code;
        $this->design->assign('clientpassport', $clientpassport);
        
    	$tpl = $this->design->fetch('pdf/soglasie-na-okazanie-dopuslugi-pri-prolongatsii.tpl');
        
        $this->pdf->create($tpl, 'Согласие на оказание доп услуги при пролонгации', 'soglasie-na-okazanie-dopuslugi-pri-prolongatsii.pdf');
    }
    
    public function soglasie_na_okazanie_dopuslugi_pri_vidache()
    {
    	$tpl = $this->design->fetch('pdf/soglasie-na-okazanie-dopuslugi-pri-vidache.tpl');
        
        $this->pdf->create($tpl, '', 'soglasie-na-okazanie-dopuslugi-pri-vidache.pdf');
    }
    
    public function soglasie_na_spisanie()
    {
    	$tpl = $this->design->fetch('pdf/soglasie-na-spisanie.tpl');
        
        $this->pdf->create($tpl, '', 'soglasie-na-spisanie.pdf');
    }
    
    public function soglasie_na_vzaimodeystvie()
    {
    	$tpl = $this->design->fetch('pdf/soglasie-na-vzaimodeystvie.tpl');
        
        $this->pdf->create($tpl, '', 'soglasie-na-vzaimodeystvie.pdf');
    }
    
    public function zayavlenie_na_uslugu_uznay_prichinu_otkaza()
    {
    	$tpl = $this->design->fetch('pdf/zayavlenie-na-uslugu-uznay-prichinu-otkaza.tpl');
        
        $this->pdf->create($tpl, '', 'zayavlenie-na-uslugu-uznay-prichinu-otkaza.pdf');
    }
    
}