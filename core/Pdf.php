<?php

require_once dirname(__FILE__).'/../tcpdf/tcpdf.php';

class Pdf extends Core
{
    private $document_author = '';
    
    private $tcpdf;
    
    public function create($template, $name, $filename)
    {
        $this->tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $this->tcpdf->SetCreator(PDF_CREATOR);
        $this->tcpdf->SetAuthor($this->document_author);
        $this->tcpdf->SetTitle($name);
        $this->tcpdf->SetSubject('TCPDF Tutorial');
        $this->tcpdf->SetKeywords('');
        $this->tcpdf->setFooterMargin(0);
        $this->tcpdf->SetAutoPageBreak(TRUE, 0);
        
        // set font
        $this->tcpdf->SetFont('dejavuserif', '', 9);
        
        $this->tcpdf->SetPrintHeader(false);
        $this->tcpdf->SetPrintFooter(false);

        $this->tcpdf->AddPage();
        
        $this->tcpdf->writeHTML($template, true, false, true, false, '');
        
        $this->tcpdf->Output($filename, 'I');
    }

    public function prolongation($template, $name, $filename)
    {
        $this->tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $this->tcpdf->SetCreator(PDF_CREATOR);
        $this->tcpdf->SetAuthor($this->document_author);
        $this->tcpdf->SetTitle($name);
        $this->tcpdf->SetSubject('TCPDF Tutorial');
        $this->tcpdf->SetKeywords('');

        // set font
        $this->tcpdf->SetFont('dejavuserif', '', 9);

        $this->tcpdf->SetPrintHeader(false);
        $this->tcpdf->SetPrintFooter(false);

        $this->tcpdf->AddPage();

        $this->tcpdf->writeHTML($template, true, false, true, false, '');

        $this->tcpdf->Output($filename, 'I');
    }
}