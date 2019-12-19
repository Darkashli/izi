<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Klanten');
if ($this->uri->segment(2) != 'create') {
	define('CUSTOMER', $this->uri->segment(3));
	define('SUBTITEL', getCustomerName(CUSTOMER) . ' (' . CUSTOMER . ')');
}
define('PAGE', 'customer');

define('SUBMENUPAGE', 'SalesOrders');
define('SUBMENU', $this->load->view('customers/tab', array(), true));

include 'application/views/inc/header.php';
?>

<?php if ($this->uri->segment(2) != 'create') { ?>
	<div class="row">
		<?= SUBMENU; ?>
		<div class="col-auto">
			<a class="btn btn-success" href="<?= base_url(); ?>SalesOrders/createorder/<?= $this->uri->segment(3); ?>">Order toevoegen</a>
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
				<form method="post" action="<?= base_url('SalesOrders/convertToInvoice') ?>" id="order-form">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="SalesOrderTable" width="100%">
							<thead>
								<tr>
									<td>
										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" class="form-check-input check" id="checkAll">
											</label>
											<span class="form-check-sign">
												<span class="check"></span>
											</span>
										</div>
									</td>
									<td>Ordernummer</td>
									<td>Orderdatum</td>
									<td>Verkoopkanaal</td>
									<td>Referentie</td>
									<td class="text-right">Actie</td>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($orders as $order) { ?>
								<tr>
									<td>
										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" class="form-check-input check" name="orders[]" value="<?= $order->Id ?>" <?= ($this->input->server('REQUEST_METHOD') == 'POST' && $_POST['invoiceFilter'] == 'closed') ? 'disabled' : null ?> />
												<span class="form-check-sign">
													<span class="check"></span>
												</span>
											</label>
										</div>
									</td>
									<td><?= $order->OrderNumber ?></td>
									<td><?= date('d-m-Y', strtotime($order->OrderDate)); ?></td>
									<td><?= getSellersName($order->Seller_id); ?></td>
									<td><?= $order->Reference ?></td>
									<td class="td-actions text-right">
										<a href="<?= base_url('SalesOrders/editorder/'.$order->Id) ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Aanpassen">
											<i class="material-icons">edit</i>
										</a>
										
										<a href="<?= base_url('SalesOrders/salesorderpdf/'.$order->Id) ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Download als pakbon">
											<i class="material-icons">picture_as_pdf</i>
										</a>

										<?php if ($this->input->server('REQUEST_METHOD') != 'POST' || ($this->input->server('REQUEST_METHOD') == 'POST' && $_POST['invoiceFilter'] != 'closed')) { ?>
											<button type="button" onclick="promoteWarning(<?= $order->Id ?>)" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Zet om naar factuur">
												<i class="material-icons">file_upload</i>
											</button>
										<?php } ?>

										<a href="<?= base_url('SalesOrders/salesordercsv/'.$order->Id) ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Exporteer als CSV">
											<i class="material-icons">import_export</i>
										</a>

									</td>
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
<div class="row justify-content-center">
	<div class="col-md-4">
		<button class="btn btn-success btn-block" type="button" onclick="promoteWarning()" <?= ($this->input->server('REQUEST_METHOD') == 'POST' && $_POST['invoiceFilter'] == 'closed') ? 'disabled' : null ?>>Omzetten naar factuur</button>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$.fn.dataTable.moment('DD-MM-YYYY');

		var table = $('#SalesOrderTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [[1, "desc"]],
			"columnDefs": [
				{
					"orderable": false,
					"targets": [0, 5]
				}
			]
		});

		$("#invoiceFilter").on("changed.bs.select", function(e, clickedIndex, isSelected, previousValue){
			$("#invoiceFilterForm").submit();
		});

		$("#checkAll").click(function () {
			$(".check:not(:disabled)").prop('checked', $(this).prop('checked'));
		});

	});
	
	function promoteWarning(salesOrderId = 0) {
		swal({
			title: 'Waarschuwing!',
			text: 'U staat op het punt om één of meerdere verkooporders om te zetten naar een verkoopfactuur. Dit proces kan niet ongedaan gemaakt worden. Weet u zeker dat u door wilt gaan?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#4caf50',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ja',
			cancelButtonText: 'Nee',
			reverseButtons: true
		}).then((result) => {
			if (result.value) {
				if (salesOrderId != 0) {
					location.href = '<?= base_url('SalesOrders/convertToInvoice') ?>/' + salesOrderId;
				}
				else{
					$("#order-form").submit();
				}
			}
		})
	}

</script>

<?php include 'application/views/inc/footer.php'; ?>
