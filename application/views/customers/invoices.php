<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Facturen');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Facturen: ' . getCustomerName(CUSTOMER) . ' (' . CUSTOMER . ')');

define('PAGE', 'customer');
define('SUBMENUPAGE', 'invoices');
define('SUBMENU', $this->load->view('customers/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success" href="<?= base_url(); ?>invoices/createinvoice/<?= $this->uri->segment(3); ?>">Factuur toevoegen</a>
	</div>
</div>
<form method="post" id="invoiceForm">
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
									<td>
										<div class="form-check">
											<label class="form-check-label">
												<input class="form-check-input" type="checkbox" class="check" id="checkAll">
												<span class="form-check-sign">
													<span class="check"></span>
												</span>
											</label>
										</div>
									</td>
									<td>Factuurnummer</td>
									<td>Factuurdatum</td>
									<td>Totaal incl. BTW</td>
									<td>Betaald</td>
									<td>Vervaldatum</td>
									<td>Status</td>
									<td>StatusId</td>
									<td>Herinneringen</td>
									<td>Actie</td>
									<td>&nbsp;</td>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($invoices as $invoice)
								{

									$paid = '0';

									$payments = getInvoiceP($invoice->Id, $invoice->BusinessId);

									foreach ($payments as $payment)
									{
										$paid += $payment->Amount;
									}
								?>
									<tr>
										<td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="checkbox" class="form-check-input check" name="invoice[]" value="<?= $invoice->Id; ?>" <?= ($invoice->PaymentDate) ? 'disabled' : ''; ?>/>
													<span class="form-check-sign">
														<span class="check"></span>
													</span>
												</label>
											</div>
										</td>
										<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= $invoice->InvoiceNumber; ?></td>
										<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>" data-order="<?= date('YmdHis', $invoice->InvoiceDate) ?>"><?= date('d-m-Y', $invoice->InvoiceDate); ?></td>
										<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>">€ <?= number_format($invoice->TotalIn, 2, ',', '') ?></td>
										<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>">€ <?= number_format($paid, 2, ',', '') ?></td>
										<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= date('d-m-Y', $invoice->ExpirationDate); ?></td>
										<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= ($invoice->PaymentDate) ? 'Betaald op ' . date('d-m-Y', $invoice->PaymentDate) : round((strtotime(date('d-m-Y')) - strtotime(date('d-m-Y', $invoice->ExpirationDate))) / 60 / 60 / 24) . ' dagen vervallen'; ?></td>
										<td><?= ($invoice->PaymentDate) ? '1' : '5' ?></td>
										<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><span class="badge badge-default"><?= countInvoiceReminders($invoice->Id, $invoice->BusinessId) ?></span></td>
										<td class="td-actions text-center"><a href="<?= base_url(); ?>invoices/editInvoice/<?= $invoice->Id; ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Aanpassen" <?= ($invoice->PaymentDate) ? 'disabled' : ''; ?>><i class="material-icons">edit</i></a></td>
										<td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= $invoice->SentPerMail ? '<i class="material-icons text-success">check_circle</i>' : null ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-md-4">
			<button class="btn btn-success btn-block" type="button" onclick="sendReminder()">Verstuur herinnering</button>
		</div>
		<div class="col-md-4">
			<button id="dunningSubmit" class="btn btn-success btn-block" type="button" onclick="sendDunning()">Verstuur aanmaning</button>
		</div>
	</div>
</form>

<script type="text/javascript">
	var invoiceTable;
	var invoiceForm = $("#invoiceForm");
	var zeroReminders = true;
	var oneReminder = false;
	var moreThanTwoReminders = false;

	$.fn.dataTable.ext.search.push(
			function (settings, data, dataIndex) {
				var msdsSearch = parseInt($("#tableFilter").val(), 10);
				var msdsValue = parseFloat(data[7]) || 0; // use data for the age column

				if (msdsSearch == msdsValue)
				{
					return true;
				}

				return false;
			}
	);
	
	$(document).ready(function () {
		$.fn.dataTable.moment('DD-MM-YYYY');

		invoiceTable = $('#invoiceTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [
				[2, "desc"],
				[1, "desc"]
			],
			columnDefs: [
				{
					"targets": [7],
					"visible": false
				},
				{
					"orderable": false,
					"targets": [0, 9]
				}
			]
		});

		$('#tableFilter').change(function () {
			invoiceTable.draw();
		});

		$("#checkAll").click(function () {
			$(".check:not(:disabled)").prop('checked', $(this).prop('checked'));
		});
		
		invoiceTable.on('draw', function(){
			loopRows();
		});
		
		$('#invoiceTable input[type="checkbox"]').click(function(){
			loopRows();
		});

	});
	
	function loopRows() {
		zeroReminders = true;
		oneReminder = false;
		moreThanTwoReminders = false;
		
		$("table#invoiceTable tbody tr").each(function(){
			var checkbox = $(this).find('input[name="invoice[]"]:not("disabled"):checked');
			if (checkbox.length > 0) {
				var cols = $(this).find('td');
				var reminders = $(cols[7]).find('span.badge').html();
				if (reminders > 0) {
					zeroReminders = false;
					if (reminders == 1) {
						oneReminder = true;
						// return false;
					}
					else if (reminders >= 2) {
						moreThanTwoReminders = true;
						// return false;
					}
				}
			}
		});
		
		if (zeroReminders == true) {
			$("#dunningSubmit").prop("disabled", true);
		}
		else {
			$("#dunningSubmit").prop("disabled", false);
		}
	}
	
	function sendReminder() {
		if (moreThanTwoReminders == true) {
			swal({
				title: 'Weet u zeker dat u deze herinnering wilt versturen?',
				text: 'Voor een of meerdere facturen zijn al 2 of meer herinnering verstuurd. Wellicht wilt u een aanmaning versturen?',
				type: 'warning',
				showCancelButton: true,
				cancelButtonColor: '#d33',
				cancelButtonText: 'Annuleren',
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Herinneringen versturen'
			}).then(function(result) {
				if (result.value) {
					$(invoiceForm).append('<input type="hidden" name="action" value="reminder">');
					$(invoiceForm).submit();
				}
			}).catch(swal.noop);
		}
		else {
			$(invoiceForm).append('<input type="hidden" name="action" value="reminder">');
			$(invoiceForm).submit();
		}
	}
	
	function sendDunning() {
		if (oneReminder == true) {
			swal({
				title: 'Weet u zeker dat u deze aanmaningen wilt versturen?',
				text: 'Voor een of meerdere facturen is nog maar 1 herinnering verstuurd. Wellicht wilt u eerst nog een herinnering versturen?',
				type: 'warning',
				showCancelButton: true,
				cancelButtonColor: '#d33',
				cancelButtonText: 'Annuleren',
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Aanmaningen versturen'
			}).then(function(result) {
				if (result.value) {
					$(invoiceForm).append('<input type="hidden" name="action" value="dunning">');
					$(invoiceForm).submit();
				}
			}).catch(swal.noop);
		}
		else {
			$(invoiceForm).append('<input type="hidden" name="action" value="dunning">');
			$(invoiceForm).submit();
		}
	}

</script>

<?php include 'application/views/inc/footer.php'; ?>
