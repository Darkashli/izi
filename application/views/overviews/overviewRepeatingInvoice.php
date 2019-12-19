<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Overzichten');
define('SUBTITEL', 'Overzicht periodieke facturen t/m ' . date('d-m-Y', $date));
define('PAGE', 'overviews');

include 'application/views/inc/header.php';
?>
<div class="row">
	<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">layers</i>
				</div>
				<div class="card-title">
			</div>
			<div class="card-body">
					<h4 class="float-left">Overzicht periodieke facturen</h4>
					<div class="float-right">
						<form method="post">
							<?= $period; ?>
						</form>
					</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="repeatingInvoiceTable" width="100%">
						<thead>
							<tr>
								<td>Factuurdatum</td>
								<td>Tijdsperiode</td>
								<td>Klantnaam</td>
								<td>Factuur beschrijving</td>
								<td>T.A.V</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($repeatingInvoices as $repeatingInvoice) { ?>
								<tr data-href="<?= base_url() . 'customers/updateRepeatingInvoice/' . $repeatingInvoice->Id; ?>">
									<td><?= date('d-m-Y', $repeatingInvoice->InvoiceDate); ?></td>
									<td><?= getTimePeriodFromDropdown($repeatingInvoice->TimePeriod); ?></td>
									<td><?= getCustomerName($repeatingInvoice->CustomerId); ?></td>
									<td><?= $repeatingInvoice->InvoiceDescription; ?></td>
									<td><?= getContactName($repeatingInvoice->ContactId); ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
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
			"order": [[0, "asc"]]
			// "sDom": "lfrtip" // default is lfrtip, where the f is the filter
		});
	});
	
</script>

<?php include 'application/views/inc/footer.php'; ?>
