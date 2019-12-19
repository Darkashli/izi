<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is the update controller.
 * It is mainly used to convert database data to a new structure.
 * IMPORTANT: All functions should be called once.
 */
class Update extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('account');
		
		if ( ! isLogged())
		{
			redirect('login');
		}
		
		if (!checkPerm(15)) {
			$this->session->set_tempdata('err_message', 'Je hebt hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("dashboard");
		}
		
	}
	
	public function v201801231516()
	{
		$this->benchmark->mark('code_start');
		
		$this->load->database();
		$this->load->helper('ticket');
		
		$this->db->trans_start();
		
		$tickets = $this->db->get("Ticket")->result();
		
		$this->db->reset_query();
		
		$ids = array();
		
		foreach ($tickets as $ticket) {
			$latestTicketRule = getLastTicketRule($ticket->Id);
			
			if ($latestTicketRule == null) {
				$dataT = array(
					'LatestTicketRule' => 0
				);
				$ids[] = $ticket->Id;
			}
			else {
				$dataT = array(
					'LatestTicketRule' => $latestTicketRule->Id
				);
			}
			
			$this->db->where('Id', $ticket->Id);
			$this->db->update('Ticket', $dataT);
			$this->db->reset_query();
		}
		
		$this->db->trans_complete();
		
		$this->benchmark->mark('code_end');

		echo "Action performed in " . $this->benchmark->elapsed_time('code_start', 'code_end') . "s." . "<br>";
		if (!empty($ids)) {
			echo "The following tickets have no ticketrules:"."<br>";
			foreach ($ids as $id) {
				echo base_url('work/update/'.$id)."<br>";
			}
		}
	}
	
	/**
	 * Update function for the new domains feature.
	 * This function merges the two table rows "IsStock" and "IsWork" into "ProductKind"
	 *
	 */
	public function v201812101316()
	{
		$this->load->model('product/Product_model', '', TRUE);
		
		$dataP = array();
		
		$products = $this->db->get('Product')->result();
		// Set the value for the columns of the new table row.
		if ($products != null) {
			foreach ($products as $product) {
				if ($product->IsStock == 1) {
					$dataP[$product->Id]['ProductKind'] = 1;
				}
				elseif ($product->IsWork == 1) {
					$dataP[$product->Id]['ProductKind'] = 2;
				}
				else {
					$dataP[$product->Id]['ProductKind'] = 0;
				}
			}
		}
		
		// Merge table columns.
		$this->db->query("ALTER TABLE `Product` DROP `IsStock`;");
		$this->db->query("ALTER TABLE `Product` CHANGE `IsWork` `ProductKind` INT(11) NOT NULL;");
		
		// Update the products.
		foreach ($dataP as $productId => $data) {
			$this->Product_model->updateProduct($productId, $data);
			echo "Product met ID #$productId geupdate, ProductKind = ".$data['ProductKind'].".<br>";
		}
		
		echo "Update voltooid!";
	}
	
}
