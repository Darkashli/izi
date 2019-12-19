<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Offertes');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Offertes: ' . getCustomerName(CUSTOMER) . ' (' . CUSTOMER . ')');

define('PAGE', 'customer');
define('SUBMENUPAGE', 'quotations');
define('SUBMENU', $this->load->view('customers/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success" href="<?= base_url(); ?>quotation/create/<?= $this->uri->segment(3); ?>">Offerte toevoegen</a>
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">description</i>
				</div>
				<div class="card-title">
					<h4 class="float-left">Offertes</h4>
					<div class="float-right">
						<form method="get">
							<select class="selectpicker" name="quotationfilter" id="quotationfilter" data-style="btn btn-info btn-round">
								<option value="0" <?= !empty($_GET['quotationfilter']) && $_GET['quotationfilter'] == 0 ? 'selected' : null ?>>Geopend</option>
								<option value="1" <?= !empty($_GET['quotationfilter']) && $_GET['quotationfilter'] == 1 ? 'selected' : null ?>><?= $latestQuotationStatus->Value ?></option>
							</select>
						</form>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="quotationsTable" width="100%">
						<thead>
							<tr>
								<td>Offertenummer</td>
								<td>Betreft</td>
								<td>Bedrag éénmalig</td>
								<td>Bedrag terugkeerend</td>
								<td>Status</td>
								<td>Acties</td>
								<td>&nbsp;</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($quotations as $quotation) { ?>
								<tr>
									<td data-href="<?= base_url("quotation/detail/$quotation->Id") ?>"><?= $quotation->QuotationNumber; ?></td>
									<td data-href="<?= base_url("quotation/detail/$quotation->Id") ?>"><?= $quotation->Subject; ?></td>
									<td data-href="<?= base_url("quotation/detail/$quotation->Id") ?>"><?= preg_replace('/\./', ',', getCosts($quotation)['costs']) ?></td>
									<td data-href="<?= base_url("quotation/detail/$quotation->Id") ?>"><?= preg_replace('/\./', ',', getCosts($quotation)['recurring']) ?></td>
									<td data-href="<?= base_url("quotation/detail/$quotation->Id") ?>"><?= getQuotationStatus($quotation->Status, $quotation->BusinessId) ?></td>
									<td class="td-actions text-right">
										<a href="<?= base_url("quotation/update/$quotation->Id") ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Aanpassen"><i class="material-icons">edit</i></a>
										<a href="<?= base_url("quotation/downloadQuotation/$quotation->Id") ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Download offerte"><i class="material-icons">file_download</i></a>
										<a href="<?= base_url("quotation/convertToInvoice/$quotation->Id") ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Zet om naar factuur"><i class="material-icons">file_upload</i></a>
										<a href="<?= base_url("quotation/convertToOrder/$quotation->Id") ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Zet om naar verkooporder"><i class="material-icons">call_made</i></a>
									</td>
									<td data-href="<?= base_url("quotation/detail/$quotation->Id") ?>"><?= $quotation->Status != 'created' ? '<i class="material-icons text-success">check_circle</i>' : null ?></td>
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
	
	var table = $('#quotationsTable').DataTable({
		"language": {
			"url": "/assets/language/Dutch.json"
		},
		"columnDefs": [
			{
				"orderable": false,
				"targets": [5]
			}
		],
		"order": [[0, "desc"]]
	});
	
});

$('#quotationfilter').change(function () {
	$(this).closest("form").submit();
});

</script>

<?php include 'application/views/inc/footer.php'; ?>
