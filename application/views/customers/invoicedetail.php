<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Facturen');
define('PAGE', 'customer');

include 'application/views/inc/header.php';
?>

<form method="post">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">format_list_numbered</i>
					</div>
					<h4 class="card-title">Factuurdetails</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-3">
							<?= $customerData->Name; ?> <br />
							<?= $customerData->StreetName; ?> <?= $customerData->StreetNumber; ?><?= $customerData->StreetAddition; ?><br />
							<?= $customerData->ZipCode; ?> <?= $customerData->City; ?><br />
						</div>
						<div class="col-md-4">
							<div class="row">
								<div class="col-6">Factuurnummer:</div>
								<div class="col-6"><?= $invoice->InvoiceNumber ?></div>
								<div class="col-6">Factuurdatum:</div>
								<div class="col-6"><?= date('d-m-Y', $invoice->InvoiceDate) ?></div>
								<div class="col-6">Vervaldatum:</div>
								<div class="col-6"><?= date('d-m-Y', $invoice->ExpirationDate) ?></div>
								<div class="col-12">&nbsp;</div>
								<div class="col-6">Totaalbedrag excl. BTW:</div>
								<div class="col-6"><?= $invoice->TotalEx ?></div>
								<div class="col-6">Totaalbedrag incl. BTW:</div>
								<div class="col-6"><b><?= $invoice->TotalIn ?></b></div>
								<div class="col-6">Openstaand bedrag:</div>
								<div class="col-6"><b><?= $invoice->TotalIn - $totalInvoicePaymentAmounts->Amount ?></b></div>
							</div>
						</div>
						<div class="col-md-5">
							<div class="row">
								<div class="col-xl-6">
									<a id="checkEmailSent" href="<?= base_url(); ?>InvoicePdf/invoiceMail/<?= $invoice->Id; ?>" class="btn btn-primary btn-block btn-sm <?= $invoice->CustomerId == 0 && $invoice->MailAddress == null ? 'disabled' : null ?>">Verstuur factuur per mail</a>
									<a href="<?= base_url(); ?>InvoicePdf/invoiceCopyMail/<?= $invoice->Id; ?>" class="btn btn-primary btn-block btn-sm <?= ($invoice->CustomerId == 0 && $invoice->MailAddress == null) || !$invoice->SentPerMail ? 'disabled' : null ?>">Verstuur kopie per mail</a>
								</div>
								<div class="col-xl-6">
									<a href="<?= base_url(); ?>InvoicePdf/invoicePDF/<?= $invoice->Id; ?>" class="btn btn-primary btn-block btn-sm">Download factuur</a>
									<a href="<?= ($invoice->WorkOrder) ? base_url() . 'InvoicePdf/workPDF/' . $invoice->Id : '#'; ?>" class="btn btn-primary btn-block btn-sm <?= ($invoice->WorkOrder) ? '' : 'disabled'; ?> w100 margin-bottom-10">Download werkbon</a>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="table-responsive mt-4">
								<table class="table table-striped" width="100%">
									<thead>
										<tr>
											<td>Aantal</td>
											<td>Omschrijving</td>
											<td>Prijs p/s</td>
											<td>Totaal</td>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($invoiceRules as $invoiceRule): ?>
											<tr>
												<td><?= $invoiceRule->Amount; ?></td>
												<td><?= $invoiceRule->Description; ?></td>
												<td><?= $invoiceRule->Price; ?></td>
												<td><?= $invoiceRule->Total; ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- <form method="post" class="w-100"> -->
						<div class="row">
							<div class="col-sm-4 input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="material-icons">euro_symbol</i>
									</span>
								</div>
								<div class="form-group label-floating">
									<label class="control-label">(deel)bedrag</label>
									<input class="form-control" type="number" step="any" name="amount" value="<?= $invoice->TotalIn - $totalInvoicePaymentAmounts->Amount ?>" />
								</div>
							</div>
							<div class="col-sm-4 input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="material-icons">date_range</i>
									</span>
								</div>
								<div class="form-group label-floating">
									<label class="control-label">Betaaldatum</label>
									<input class="form-control date-picker" type="text" name="paymentdate" value="<?= date('d-m-Y') ?>" />
								</div>
							</div>
							<!-- <div class="col-md-4 col-lg-4 col-sm-4">
								<label class="w-100">&nbsp;</label>
								<button type="submit" class="btn btn-success ml-auto">Opslaan</button>
							</div> -->
						</div>
					<!-- </form> -->
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">euro_symbol</i>
					</div>
					<h4 class="card-title">Betalingen</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="table-responsive mt-4">
							<table class="table table-striped table-sm" width="100%">
								<thead>
									<tr>
										<td>Betaald op</td>
										<td>Bedrag</td>
										<td>&nbsp;</td>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($invoicePayments)) { ?>
										<?php foreach ($invoicePayments as $invoicePayment){ ?>
											<tr>
												<td><?= date('d-m-Y', $invoicePayment->Date); ?></td>
												<td><?= $invoicePayment->Amount; ?></td>
												<td class="text-right"><button type="button" class="btn btn-danger btn-round btn-fab btn-fab-mini" onclick="areYouSure(<?= $invoicePayment->Id ?>)"><i class="material-icons">delete</i></button></td>
											</tr>
										<?php } ?>
									<?php }else{ ?>
										<tr>
											<td colspan="3">Geen betalingen om te weergeven</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-header-icon">
						<i class="material-icons">alarm</i>
					</div>
					<h4 class="card-title">Verstuurde herinneringen</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="table-responsive mt-4">
							<table class="table table-striped table-sm" width="100%">
								<thead>
									<tr>
										<td>Verstuurd op</td>
										<td>Soort</td>
									</tr>
								</thead>
								<tbody>
									<?php
										foreach ($invoiceReminders as $invoiceReminder)
										{ ?>
											<tr>
												<td><?= $invoiceReminder->ReminderDate ?></td>
												<?php if ($invoiceReminder->ReminderType == 0)
												{ ?>
													<td>Herinnering</td>
										<?php 	} ?>
												<?php if ($invoiceReminder->ReminderType == 1)
												{ ?>
													<td>Aanmaning</td>
										<?php 	} ?>
											</tr>
								<?php	} ?>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-md-4">
			<button type="submit" class="btn btn-success ml-auto btn-block">Opslaan</button>
		</div>
		<div class="col-md-4">
			<a class="btn btn-success ml-auto btn-block" href="/customers/invoices/<?= $invoice->CustomerId ?>">Terug</a>
		</div>
	</div>
</form>

<script type="text/javascript">

	function areYouSure(invoicePaymentId){
		var linkURL = '<?= base_url('customers/deleteInvoicePayment') ?>/' + invoicePaymentId;
		swal({
			title: 'Weet u het zeker?',
			text: 'Deze actie kan niet ongedaan worden gemaakt!',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ja, verwijder dit!'
		}).then(function (result) {
			if (result.value) {
				window.location.href = linkURL;
			}
		}).catch(swal.noop)
	}
	
	$(document).ready(function () {
		
		$('.date-picker').datetimepicker({
			locale: 'nl',
			format: 'L',
			useCurrent: false,
			icons: datetimepickerIcons
		});
		
	});

	$('#checkEmailSent').click( function() {
		event.preventDefault();
		var defaultUrl = $(this).attr('href');
		var status = '<?= $invoice->SentPerMail ?>';
		
		if (status == 1)
		{
			swal({
				title: 'Weet u het zeker?',
				text: 'U heeft deze factuur reeds verzonden per mail. Weet u zeker dat u deze factuur nogmaals wilt versturen?',
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ja, zend nogmaals!'
			}).then(function (result) {
				if (result.value) {
					window.location.href = defaultUrl;
				}
			}).catch(swal.noop)
		}
		else {
			window.location.href = defaultUrl;
		}
	})
</script>

<?php include 'application/views/inc/footer.php'; ?>
