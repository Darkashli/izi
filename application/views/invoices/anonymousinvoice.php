<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Verkoopfacturen');
define('PAGE', 'invoice');

include 'application/views/inc/header.php';?>

<div class="row">
	<div class="col-auto ml-auto">
		<a href="<?= base_url('invoices/createinvoice') ?>" class="btn btn-success">Nieuwe factuur</a>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">visibility</i>
				</div>
				<div class="card-title">
					<h4 class="float-left">Overzicht facturen voor eenmalige klanten</h4>
					<div class="float-right">
						<form method="get">
							<select name="invoice_status" id="invoiceFilter" class="selectpicker" data-style="btn btn-info btn-round" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
								<option value="open" <?= !empty($_GET['invoice_status']) && $_GET['invoice_status'] == 'open' ? 'selected' : null ?>>Openstaand</option>
								<option value="closed" <?= !empty($_GET['invoice_status']) && $_GET['invoice_status'] == 'closed' ? 'selected' : null ?>>Betaald</option>
							</select>
						</form>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="invoiceTable" width="100%">
						<thead>
							<tr>
								<td>Factuurnummer</td>
								<td>Factuurdatum</td>
								<td>Klant</td>
								<td>Totaal incl. BTW</td>
								<td>Vervaldatum</td>
								<td>Status</td>
								<td>StatusId</td>
								<td>Herinneringen</td>
								<td class="text-right">Actie</td>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($invoices as $invoice) { ?>
							<tr id="invoiceRow<?= $invoice->Id ?>">
								<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= $invoice->InvoiceNumber; ?></td>
								<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= date('d-m-Y', $invoice->InvoiceDate); ?></td>
								<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= $invoice->CompanyName != null ? $invoice->CompanyName : "$invoice->FrontName $invoice->Insertion $invoice->LastName"; ?></td>
								<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= $invoice->TotalIn; ?></td>
								<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= date('d-m-Y', $invoice->ExpirationDate); ?></td>
								<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= ($invoice->PaymentDate) ? 'Betaald op ' . date('d-m-Y', $invoice->PaymentDate) : round((strtotime(date('d-m-Y')) - strtotime(date('d-m-Y', $invoice->ExpirationDate))) / 60 / 60 / 24) . ' dagen vervallen'; ?></td>
								<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= ($invoice->PaymentDate) ? '1' : '5' ?></td>
								<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><span class="badge badge-default"><?= countInvoiceReminders($invoice->Id, $invoice->BusinessId) ?></span></td>
								<td class="td-actions text-right">
									<a href="<?= base_url(); ?>invoices/editInvoice/<?= $invoice->Id; ?>" class="btn btn-info btn-round btn-fab btn-fab-mini <?= ($invoice->PaymentDate) ? 'disabled' : ''; ?>" title="Aanpassen">
										<i class="material-icons">edit</i>
									</a>
									<a href="<?= base_url("invoices/setToRegular/$invoice->Id") ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Zet om naar vaste klant">
										<i class="material-icons">people</i>
									</a>
									<button type="button" onclick="sendReminder(<?= $invoice->Id ?>)" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Verstuur herinnering">
										<i class="fas fa-bell"></i>
									</button>
									<button type="button" onclick="sendDunning(<?= $invoice->Id ?>)" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Verstuur aanmaning" <?= countInvoiceReminders($invoice->Id, $invoice->BusinessId) == 0 ? 'disabled' : null ?>>
										<i class="fas fa-stopwatch"></i>
									</button>
								</td>
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
	var invoiceTable;
	var invoiceForm = $("#invoiceForm");
	var zeroReminders = true;
	var oneReminder = false;
	var moreThanTwoReminders = false;
	
	$(document).ready(function () {
		$.fn.dataTable.moment('DD-MM-YYYY');

		var table = $('#invoiceTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [[0, "desc"]],
			columnDefs: [
				{
					"targets": [6],
					"visible": false
				},
				{
					"targets": [8],
					"orderable": false
				}
			]
		});

		// $('#tableFilter').change(function () {
		// 	table.draw();
		// });
	});
	
	function sendReminder(invoiceId) {
		var href = '<?= base_url() ?>invoices/sendReminder/' + invoiceId;
		var numberOfReminders = $('tr#invoiceRow' + invoiceId + ' td:nth-child(7) > span.badge').html();
		
		if (numberOfReminders >= 2) {
			swal({
				title: 'Weet u zeker dat u deze herinnering wilt versturen?',
				text: 'Voor deze factuur zijn al 2 of meer herinnering verstuurd. Wellicht wilt u een aanmaning versturen?',
				type: 'warning',
				showCancelButton: true,
				cancelButtonColor: '#d33',
				cancelButtonText: 'Annuleren',
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Herinneringen versturen'
			}).then(function(result) {
				if (result.value) {
					location.href = href;
				}
			}).catch(swal.noop);
		}
		else {
			location.href = href;
		}
	}
	
	function sendDunning(invoiceId) {
		var href = '<?= base_url() ?>invoices/sendDunning/' + invoiceId;
		var numberOfReminders = $('tr#invoiceRow' + invoiceId + ' td:nth-child(7) > span.badge').html();
		
		if (numberOfReminders == 1) {
			swal({
				title: 'Weet u zeker dat u deze aanmaningen wilt versturen?',
				text: 'Voor deze factuur is nog maar 1 herinnering verstuurd. Wellicht wilt u eerst nog een herinnering versturen?',
				type: 'warning',
				showCancelButton: true,
				cancelButtonColor: '#d33',
				cancelButtonText: 'Annuleren',
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Aanmaningen versturen'
			}).then(function(result) {
				if (result.value) {
					location.href = href;
				}
			}).catch(swal.noop);
		}
		else {
			location.href = href;
		}
	}
	
	$('#invoiceFilter').change(function () {
		$(this).closest("form").submit();
	});
	
</script>

<?php include 'application/views/inc/footer.php'; ?>
