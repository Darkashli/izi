<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Get the latest repeating invoice date of the given domain.
 *
 * @param int $domainId The id of the domain.
 * @return string The date of the repeating invoice.
 *
 */
function getLatestInvoiceDate($domainId)
{
	$ci = & get_instance();
	
	$dates = array();
	
	$user = $ci->session->userdata('user');
	$repeatingInvoices = $ci->Customers_model->getAllRepeatingInvoice($user->BusinessId)->result();
	
	foreach ($repeatingInvoices as $repeatingInvoice) {
		$repeatingInvoiceRules = (object) unserialize($repeatingInvoice->InvoiceRules);
		
		foreach ($repeatingInvoiceRules as $repeatingInvoiceRule) {
			if (!isset($repeatingInvoiceRule->Domain)) {
				break;
			}
			if ($repeatingInvoiceRule->Domain == $domainId) {
				$dates[] = $repeatingInvoice->InvoiceDate;
				break;
			}
		}
	}
	
	asort($dates);
	return end($dates);
}
