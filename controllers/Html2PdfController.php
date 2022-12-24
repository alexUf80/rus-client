<?php

class Html2PdfController extends Controller
{
    public function fetch()
    {
        $user_id = $this->request->get('user_id');
        $contract_id = $this->request->get('contract_id');

        switch ($document_name = $this->request->get('document_name')):

            case 'DOP_SOGLASHENIE':
                $document_template = 'prolongation.tpl';
                $this->dop_soglashenie_prolongation($user_id, $document_template, $contract_id);
                break;

            case 'POLIS':
                $document_template = 'polis.tpl';
                $this->polis_strahovaniya($user_id, $document_template, $contract_id);
                break;

        endswitch;

    }

    private function polis_strahovaniya($user_id, $document_template, $contract_id)
    {
        $user = $this->users->get_user($user_id);

        $contract = $this->contracts->get_contract($contract_id);

        foreach ($user as $param_name => $param_value) {
            $this->design->assign($param_name, $param_value);
        }

        foreach ($contract as $param_name => $param_value) {
            $this->design->assign($param_name, $param_value);
        }


        $contract->insurance->amount      = $this->settings->prolongation_amount;
        $contract->insurance->start_date  = date('Y-m-d');
        $contract->insurance->end_date    = date('Y-m-d H:i:s', time() + 86400 * $this->settings->prolongation_period);
        $contract->insurance->create_date = date('Y-m-d');

        $regaddress = $this->Addresses->get_address($user->regaddress_id);
        $regaddress_full = $regaddress->adressfull;

        $this->design->assign('regaddress_full', $regaddress_full);

        $tpl = $this->design->fetch('pdf/' . $document_template);

        $this->pdf->create($tpl, 'Полис страхования', $document_template);
    }

    private function dop_soglashenie_prolongation($user_id, $document_template, $contract_id)
    {
        $user = $this->users->get_user($user_id);

        $contract = $this->contracts->get_contract($contract_id);

        foreach ($user as $param_name => $param_value) {
            $this->design->assign($param_name, $param_value);
        }

        foreach ($contract as $param_name => $param_value) {
            $this->design->assign($param_name, $param_value);
        }

        $this->design->assign('contract', $contract);

        $return_amount_percents = ($contract->amount * $contract->base_percent * $contract->period) / 100;

        $contract->insurance->amount      = $this->settings->prolongation_amount;
        $contract->insurance->start_date  = date('Y-m-d');
        $contract->insurance->end_date    = date('Y-m-d H:i:s', time() + 86400 * $this->settings->prolongation_period);
        $contract->insurance->create_date = date('Y-m-d');

        $regaddress = $this->Addresses->get_address($user->regaddress_id);
        $regaddress_full = $regaddress->adressfull;

        $this->design->assign('regaddress_full', $regaddress_full);

        $this->design->assign('return_amount_percents', $return_amount_percents);

        $tpl = $this->design->fetch('pdf/' . $document_template);

        $this->pdf->create($tpl, 'Дополнительное соглашение о пролонгации', $document_template);
    }
}