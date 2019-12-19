<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Inkooporders');
if ($this->uri->segment(2) != 'create') {
	define('SUPPLIER', $this->uri->segment(3));
	define('SUBTITEL', getSupplierName(SUPPLIER) . ' (' . SUPPLIER . ')');
}
define('PAGE', 'supplier');

define('SUBMENUPAGE', 'SalesOrders');
define('SUBMENU', $this->load->view('supplier/tab', array(), true));

include 'application/views/inc/header.php';
?>

<?php if ($this->uri->segment(2) != 'create') { ?>
	<div class="row">
		<?= SUBMENU; ?>
		<div class="col-col-auto">
			<a class="btn btn-success" href="<?= base_url(); ?>purchaseOrders/createorder/<?= $this->uri->segment(3); ?>">Order toevoegen</a>
		</div>
	</div>
<?php } ?>

<div class="row">
	<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">local_offer</i>
				</div>
				<div class="card-title">
					<h4 class="float-left">Orders</h4>
					<div class="float-right">
						<form id="invoiceFilterForm" method="post">
							<select id="invoiceFilter" name="invoiceFilter" class="selectpicker" data-style="btn btn-info btn-round" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
								<option value="open" <?= set_select('invoiceFilter', 'open', true) ?>>Openstaand</option>
								<option value="closed" <?= set_select('invoiceFilter', 'closed', false) ?>>Gefactureerd</option>
							</select>
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
								<td>Orderdatum</td>
								<td>Referentie</td>
								<td class="text-right">Actie</td>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($orders as $order) { ?>
							<tr>
								<td><?= $order->OrderNumber ?></td>
								<td><?= date('d-m-Y', strtotime($order->OrderDate)); ?></td>
								<td><?= $order->Reference ?></td>
								<td class="td-actions text-right">
									<a href="<?= base_url('PurchaseOrders/editorder/'.$order->Id) ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Aanpassen">
										<i class="material-icons">edit</i>
									</a>
									
									<a href="<?= base_url('PurchaseOrders/purchaseOrderPDF/'.$order->Id) ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Download pakbon">
										<i class="material-icons">picture_as_pdf</i>
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
				{ "orderable": false, "targets": 3 }
			]
		});

		$("#invoiceFilter").on("changed.bs.select", function(e, clickedIndex, isSelected, previousValue){
			$("#invoiceFilterForm").submit();
		});

	});

</script>

<?php include 'application/views/inc/footer.php'; ?>
