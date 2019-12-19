<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Facturen');
define('PAGE', 'supplier');
define('SUBTITEL', 'Facturen: ' . getSupplierName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');

define('SUBMENUPAGE', 'invoices');
define('SUBMENU', $this->load->view('supplier/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success float-right" href="<?= base_url(); ?>invoices/createinvoiceS/<?= $this->uri->segment(3); ?>">Factuur toevoegen</a>
	</div>
</div>

<div class="row">
	<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">local_offer</i>
				</div>
				<div class="card-title">
					<h4 class="float-left">Facturen</h4>
					<div class="float-right"><?= $tableFilter; ?></div>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="invoiceTable" width="100%">
						<thead>
							<tr>
								<td>Factuurnummer</td>
								<td>Inkoopnummer</td>
								<td>Factuurdatum</td>
								<td>Totaal incl. BTW</td>
								<td>Vervaldatum</td>
								<td>Dagen vervallen</td>
								<td>StatusId</td>
								<td>&nbsp;</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($invoices as $invoice) { ?>
								<tr data-href="<?= base_url('supplier/openinvoice/'.$invoice->Id) ?>">
									<td><?= $invoice->InvoiceNumber; ?></td>
									<td><?= $invoice->PurchaseNumber; ?></td>
									<td><?= date('d-m-Y', $invoice->InvoiceDate); ?></td>
									<td><?= $invoice->TotalIn; ?></td>
									<td><?= date('d-m-Y', $invoice->ExpirationDate); ?></td>
									<td><?= ($invoice->PaymentDate) ? 'Betaald op ' . date('d-m-Y', $invoice->PaymentDate) : round((strtotime(date('d-m-Y')) - strtotime(date('d-m-Y', $invoice->ExpirationDate))) / 60 / 60 / 24) . ' dagen vervallen'; ?></td>
									<td><?= ($invoice->PaymentDate) ? '1' : '5' ?></td>
									<td class="td-actions text-center">
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
	
	$.fn.dataTable.ext.search.push(
		function (settings, data, dataIndex) {
			var msdsSearch = parseInt($("#tableFilter").val(), 10);
			var msdsValue = parseFloat(data[6]) || 0; // use data for the age column

			if (msdsSearch == msdsValue)
			{
				return true;
			}

			return false;
		}
	);

	$(document).ready(function () {
		$.fn.dataTable.moment('DD-MM-YYYY');

		var table = $('#invoiceTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [
				[2, "desc"],
				[1, "desc"]
			],
			columnDefs: [
				{
					"targets": [6],
					"visible": false
				}
			]
			// "sDom": "lfrtip" // default is lfrtip, where the f is the filter
		});

		$('#tableFilter').change(function () {
			table.draw();
		});
	});
	
</script>

<?php include 'application/views/inc/footer.php'; ?>
