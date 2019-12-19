<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Periodieke facturen');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Periodieke facturen: ' . getCustomerName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
define('PAGE', 'customer');
define('SUBMENUPAGE', 'repeatingInvoice');
define('SUBMENU', $this->load->view('customers/tab', array(), true));

include 'application/views/inc/header.php';
?>
<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success" href="<?= base_url('customers/createRepeatingInvoice/'.$this->uri->segment(3)) ?>">Factuur toevoegen</a>
	</div>
</div>

<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">local_offer</i>
				</div>
				<h4 class="card-title">Periodieke facturen</h4>
			</div>
			<div class="card-body">
				<form method="post">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="repeatingInvoiceTable" width="100%">
							<thead>
								<tr>
									<td>Factuurdatum</td>
									<td>Tijdsperiode</td>
									<td>Factuur beschrijving</td>
									<td>T.A.V</td>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($repeatingInvoices as $repeatingInvoice) { ?>
									<tr data-href="<?= base_url('customers/updateRepeatingInvoice/'.$repeatingInvoice->Id) ?>">
										<td> <?= date('d-m-Y', $repeatingInvoice->InvoiceDate); ?></td>
										<td> <?= getTimePeriodFromDropdown($repeatingInvoice->TimePeriod); ?></td>
										<td> <?= $repeatingInvoice->InvoiceDescription; ?></td>
										<td> <?= getContactName($repeatingInvoice->ContactId); ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function () {
		
		$.fn.dataTable.moment('DD-MM-YYYY');
		
		$('#repeatingInvoiceTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [[2, "asc"]]
		});
		
	});

	var calculatePurchase = 0;
	
</script>

<?php include 'application/views/inc/footer.php'; ?>
