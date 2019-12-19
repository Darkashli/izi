<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Verkooporder');
define('PAGE', 'purchaseorder');

include 'application/views/inc/header.php';?>

<div class="row">
	<div class="col-auto ml-auto">
		<a class="btn btn-success float-right" href="<?= base_url(); ?>PurchaseOrders/createorder">Order maken</a>
    </div>
</div>

<div class="row">
	<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">visibility</i>
				</div>
				<div class="card-title">
					<h4 class="float-left">Overzicht orders voor eenmalige leveranciers</h4>
					<div class="float-right">
						<form method="get" class="form-inline">
							<input class="form-control" type="number" name="year" title="Jaar" value="<?= $_GET['year'] ?? date('Y') ?>">
							<select name="status" class="selectpicker" data-style="btn btn-info btn-round" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
								<option value="open" <?= (!empty($_GET['status']) && $_GET['status'] == 'open') ? 'selected' : null ?>>Openstaand</option>
								<option value="closed" <?= (!empty($_GET['status']) && $_GET['status'] == 'closed') ? 'selected' : null ?>>Gefactureerd</option>
							</select>
							<button type="submit" class="btn btn-round btn-fab btn-fab-mini btn-success ml-2"><i class="material-icons">send</i></button>
						</form>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="SalesOrderTable" width="100%">
						<thead>
							<tr>
								<td>Ordernummer</td>
								<td>Leverancier naam</td>
								<td>Orderdatum</td>
								<td>Referentie</td>
								<td class="text-right">Actie</td>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($orders as $order) { ?>
							<tr>
								<td><?= $order->OrderNumber ?></td>
								<td><?= "$order->FrontName $order->Insertion $order->LastName" ?></td>
								<td><?= date('d-m-Y', strtotime($order->OrderDate)); ?></td>
								<td><?= $order->Reference ?></td>
								<td class="td-actions text-right">
									<a href="<?= base_url('PurchaseOrders/editorder/'.$order->Id) ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Aanpassen">
										<i class="material-icons">edit</i>
									</a>
									<?php if ($this->input->server('REQUEST_METHOD') != 'POST' || ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['invoiceFilter']) && $_POST['invoiceFilter'] != 'closed')) { ?>
										<a href="<?= base_url('PurchaseOrders/promotePurchaseOrder/'.$order->Id) ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Zet om naar factuur">
											<i class="material-icons">file_upload</i>
										</a>
									<?php } ?>
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

		var table = $('#SalesOrderTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [[2, "desc"]],
			"columnDefs": [
				{ "orderable": false, "targets": 4 }
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
