<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Results extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('Ticket');
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->load->model('tickets/Tickets_model', '', TRUE);
        $this->load->model('tickets/Tickets_statusmodel', '', TRUE);
        $this->load->model('results/Results_model', '', TRUE);
        $this->load->model('years/Year_model', '', TRUE);
    }

    public function index() {
        if (!isLogged()) {
            redirect('login');
        }
        
        $businessId = $this->session->userdata('user')->BusinessId;

        $year = date('Y');
        $options = array();
        $bookyears = $this->Year_model->getAll($businessId)->result();
        foreach ($bookyears as $bookyear) {
            $options[$bookyear->Year] = $bookyear->Year;
        }
        
        $extra = 'class="form-control" onchange="this.form.submit()"';

        if (isset($_GET['year'])) {
            $year = $_GET['year'];
        }

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
        }
        
        $data['year'] = form_dropdown('year', $options, $year, CLASSSELECTBOOTSTRAPSUBMIT);
        $data['bookyears'] = $bookyears;
        
        for($i = 1; $i < 13; $i++){
            $startMonth = date('d-m-Y', strtotime('01-'.$i.'-'.$year));
            $endMonth = date('t-m-Y', strtotime('23-'.$i.'-'.$year));
            $data['month'.$i.'Sold'] = $this->Results_model->getSold($businessId,  $startMonth, $endMonth)->row();
            $data['month'.$i.'Bought'] = $this->Results_model->getBought($businessId,  $startMonth, $endMonth)->row();
            $data['month'.$i.'SoldPayment'] = $this->Results_model->getSoldPayment($businessId,  $startMonth, $endMonth)->row();
        }
		
		if (isset($_GET['export_csv']) && $_GET['export_csv'] == true) {
			$this->exportCsv($data);
		}
		else{
			$this->load->view('results/default', $data);
		}
        
    }
	
	private function exportCsv($data)
	{
		setlocale(LC_ALL, 'nl_NL');
		
		header('Content-Type: application/excel');
	    header('Content-Disposition: attachment; filename="Overzicht-in-en-verkoop-totalen-per-maand-per-'.$_GET['year'].'.csv"');
		
		$fp = fopen('php://output', 'w');
		$row = array('Maand', 'Verkoop (excl. BTW)', 'Inkoop (excl. BTW)', 'Betalingen (incl. BTW)');
		fputcsv($fp, $row);
		
		$totalSold = 0;
		$totalBought = 0;
		$totalPayment = 0;
		for($i = 1; $i < 13; $i++){
			$totalSold = $totalSold + ($data["month" . $i . "Sold"]->TotalEx ?? 0);
			$totalBought = $totalBought + ($data["month" . $i . "Bought"]->TotalEx ?? 0);
			$totalPayment = $totalPayment + ($data["month" . $i . "SoldPayment"]->Amount ?? 0);
			$row = array(
				ucfirst(strftime('%B', strtotime('01-' . $i . '-2015'))),
				isset($data["month" . $i . "Sold"]->TotalEx) ? number_format($data["month" . $i . "Sold"]->TotalEx, 2, '.', '') : '0.00',
				isset($data["month" . $i . "Bought"]->TotalEx) ? number_format($data["month" . $i . "Bought"]->TotalEx, 2, '.', '') : '0.00',
				isset($data["month" . $i . "SoldPayment"]->Amount) ? number_format($data["month" . $i . "SoldPayment"]->Amount, 2, '.', '') : '0.00'
			);
			fputcsv($fp, $row);
		}
		$row = array('Totaal', number_format($totalSold, 2, '.', ''), number_format($totalBought, 2, '.', ''), number_format($totalPayment, 2, '.', ''));
		fputcsv($fp, $row);
		
		fclose($fp);
	}

}
