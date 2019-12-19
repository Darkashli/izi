<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'inkoopfacturen');
define('PAGE', 'invoiceS');

include 'application/views/inc/header.php';?>

<!-- <div class="row">
	<div class="col-auto ml-auto">
		<a class="btn btn-success" href="<?= base_url('SalesOrders/createorder'); ?>">Order toevoegen</a>
	</div>
</div> -->

<div class="row">
	<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">visibility</i>
				</div>
				<div class="card-title">
					<h4 class="float-left">Overzicht facturen voor eenmalige leveranciers</h4>
					<div class="float-right">
						<form method="post">
							<select name="invoiceFilter" class="selectpicker" data-style="btn btn-info btn-round" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
								<option value="open" <?= set_select('invoiceFilter', 'open', true) ?>>Openstaand</option>
								<option value="closed" <?= set_select('invoiceFilter', 'closed', false) ?>>Gefactureerd</option>
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
								<td>Leverancier</td>
								<td>Totaal incl. BTW</td>
								<td>Vervaldatum</td>
								<td>Status</td>
								<td>StatusId</td>
								<td class="text-right">Actie</td>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($invoices as $invoice) { ?>
							<tr>
								<td data-href="<?= base_url('supplier/openinvoice/'.$invoice->Id) ?>"><?= $invoice->InvoiceNumber; ?></td>
								<td data-href="<?= base_url('supplier/openinvoice/'.$invoice->Id) ?>"><?= date('d-m-Y', $invoice->InvoiceDate); ?></td>
								<td data-href="<?= base_url('supplier/openinvoice/'.$invoice->Id) ?>"><?= $invoice->CompanyName != null ? $invoice->CompanyName : "$invoice->FrontName $invoice->Insertion $invoice->LastName"; ?></td>
								<td data-href="<?= base_url('supplier/openinvoice/'.$invoice->Id) ?>"><?= $invoice->TotalIn; ?></td>
								<td data-href="<?= base_url('supplier/openinvoice/'.$invoice->Id) ?>"><?= date('d-m-Y', $invoice->ExpirationDate); ?></td>
								<td data-href="<?= base_url('supplier/openinvoice/'.$invoice->Id) ?>"><?= ($invoice->PaymentDate) ? 'Betaald op ' . date('d-m-Y', $invoice->PaymentDate) : round((strtotime(date('d-m-Y')) - strtotime(date('d-m-Y', $invoice->ExpirationDate))) / 60 / 60 / 24) . ' dagen vervallen'; ?></td>
								<td><?= ($invoice->PaymentDate) ? '1' : '5' ?></td>
								<td class="td-actions text-right">
									<a href="<?= base_url(); ?>invoices/editInvoiceS/<?= $invoice->Id; ?>" class="btn btn-info btn-round btn-fab btn-fab-mini <?= ($invoice->PaymentDate) ? 'disabled' : ''; ?>" title="Aanpassen">
										<i class="material-icons">edit</i>
									</a>
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

	$(document).ready(function () {
		$.fn.dataTable.moment('DD-MM-YYYY');

		var table = $('#invoiceTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [[1, "desc"]],
			columnDefs: [
				{
					"targets": [6],
					"visible": false
				},
				{
					"targets": [7],
					"orderable": false
				}
			]
		});

		$('#tableFilter').change(function () {
			table.draw();
		});

		$("#checkAll").click(function () {
			$(".check:not(:disabled)").prop('checked', $(this).prop('checked'));
		});

	});
</script>

<?php include 'application/views/inc/footer.php'; ?>
