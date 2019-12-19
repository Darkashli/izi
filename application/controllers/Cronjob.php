<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('csv');
	}

	/* Dev (don't forget to comment this out before build) */
	// private $ftpServer = '5.79.113.10';
	// private $ftpUserName = 'iziaccount@devftpventa.nl';
	// private $ftpUserPass = '2dRk6vbvG';

	/* Live */
	private $ftpServer = '5.79.113.10';
	private $ftpUserName = 'iziaccount@ftpventa.nl';
	private $ftpUserPass = 'Wu0XJRgIN';

	public function ExportStockToCsv() {
		// Connect to ftp server.
		$connId = @ftp_connect($this->ftpServer) or die('Geen ftp verbinding');
		$loginResult = @ftp_login($connId, $this->ftpUserName, $this->ftpUserPass) or die('Kan niet inloggen op de server');

		// Direcroties.
		$tempDirectory = 'temp';
		$directory = 'public_html/stockexport';
		$filename = 'stocklist.csv';
		$tempLocation = $tempDirectory.'/'.$filename;
		$location = $directory.'/'.$filename;

		// This function is only for business 1 (in mijn.ventagroupn.nl).
		$stocklist = stocklist(1);

		@mkdir($tempDirectory);
		
		// Put array into csv.
		$fp = fopen($tempLocation, 'w');
		foreach ($stocklist as $row) {
			fputcsv($fp, $row, ';');
		}
		// Create a temp file.
		fwrite($fp, $tempLocation);
		fclose($fp);

		@ftp_mkdir($connId, './'.$directory);
		
		// Put csv to ftp.
		$ftp_put = ftp_put($connId, './'.$location, $tempLocation, FTP_ASCII);

		ftp_close($connId);

		if ($ftp_put == true) {
			echo "Voorraadlijst geëxporteerd!";
		}

	}
}
