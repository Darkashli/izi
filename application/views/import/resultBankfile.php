<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Bank import');
define('PAGE', 'import');

include 'application/views/inc/header.php';
?>

<div class="row">

	<div class="col-12 col-md-6">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<img src="<?= base_url('assets/images/icons/ing-logo.svg') ?>" alt="ING logo">
				</div>
				<h4 class="card-title">Import bankafschrift ING</h4>
			</div>
			<div class="card-body">
				<form method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-12 dragdrop">
							<input type="file" name="bankfile" accept=".XML, .xml"required />
						</div>
					</div>
					<button type="submit" class="btn btn-block btn-success">Verwerk bestand</button>
				</form>
			</div>
		</div>
	</div>

	<div class="col-12">
		<form method="post">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">playlist_add_check</i>
					</div>
					<h4 class="card-title">Resultaten import bankafschrift</h4>
				</div>
				<div class="card-body">
					
					<div id="carousel" class="carousel slide" data-interval="false" data-wrap="false">

						<div class="carousel-inner">

							<?php
							$i = 0;
							$ii = 0;
							foreach ($importResults as $importResult) {
								$totalPredicted = 0;
								$hasRemark = false;
								$PredictedId = null;
								if (!empty($importResult->PredictedInvoices)) {
									if (isset($importResult->PredictedCustomer->CustomerId)){
										$PredictedId = $importResult->PredictedCustomer->CustomerId;
									} elseif (isset($importResult->PredictedCustomer->SupplierId)){
										$PredictedId = $importResult->PredictedCustomer->SupplierId;
									}
									if (!empty($importResult->PredictedInvoices)) {
										foreach ($importResult->PredictedInvoices as $PredictedInvoice) {
											if ($PredictedInvoice->ImportPaymentRemark) {
												$hasRemark = true;
											}
											$totalPredicted += $PredictedInvoice->TotalIn;
										}
									}
								} ?>

								<div class="carousel-item <?= ($i == 0) ? 'active' : null ?>">
									<div id="importRule_<?= $i ?>" class="row" style="width: 100%; <?= ($hasRemark == true) ? 'background-color: #f3f3f3;' : null; ?>">
										<div class="col-12">
											<div class="float-right">(Resultaat <?= $i + 1 ?> van <?= count($importResults) ?>)</div>
										</div>
										<div class="col-md-4">
											Naam uit import:<br>
											<b><?= $importResult->ImportUser->Name; ?></b><br>
											<br>
											Voorgestelde door izi account:<br>
											<div class="form-check form-check-radio form-check-inline">
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="customer_or_supplier[<?= $i ?>]" value="customer" onchange="switch_predicted('<?= $i ?>')" <?= (($importResult->PredictedCustomer !== null) && (isset($importResult->PredictedCustomer->CustomerId))) ? 'checked' : null ?>>													Klant
													<span class="circle">
														<span class="check"></span>
													</span>
												</label>
											</div>
											<div class="form-check form-check-radio form-check-inline">
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="customer_or_supplier[<?= $i ?>]" value="supplier" onchange="switch_predicted('<?= $i ?>')" <?= (($importResult->PredictedCustomer !== null) && (isset($importResult->PredictedCustomer->SupplierId))) ? 'checked' : null ?>>													Leverancier
													<span class="circle">
														<span class="check"></span>
													</span>
												</label>
											</div>
											<div class="form-group <?= (($importResult->PredictedCustomer !== null) && (isset($importResult->PredictedCustomer->CustomerId))) ? null : 'hidden' ?>" id="predictedCustomer<?= $i ?>">
												<label class="control-label">Klant</label>
												<select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
													<option value=""></option>
													<?php foreach ($customers as $customer): ?>
														<option value="<?= $customer->Id ?>" <?= ($importResult->PredictedCustomer !== null && isset($importResult->PredictedCustomer->CustomerId) && $importResult->PredictedCustomer->CustomerId == $customer->Id) ? 'selected' : NULL ?>><?= $customer->Name ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="form-group <?= (($importResult->PredictedCustomer !== null) && (isset($importResult->PredictedCustomer->SupplierId))) ? null : 'hidden' ?>" id="predictedSupplier<?= $i ?>">
												<label class="control-label">Leverancier</label>
												<select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
													<option value=""></option>
													<?php foreach ($suppliers as $supplier): ?>
														<option value="<?= $supplier->Id ?>" <?= ($importResult->PredictedCustomer !== null && isset($importResult->PredictedCustomer->SupplierId) && $importResult->PredictedCustomer->SupplierId == $supplier->Id) ? 'selected' : NULL ?>><?= $supplier->Name ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											Betaaldatum:<br>
											<b><?= date('d-m-Y', $importResult->PaymentDate); ?></b><br>
											<input type="hidden" id="paymentDate_<?= $i; ?>" name="paymentDate[<?= $i; ?>]" value="<?= $importResult->PaymentDate; ?>" />
											<br>
											Totaalbedrag uit import:<br>
											<b>&euro; <?= number_format(floatval($importResult->ImportPayment->TotalIn), 2, ',', '.'); ?></b><br>
											<br>
											Totaalbedrag voorgesteld uit izi account:<br>
											<b>&euro; <?= number_format($totalPredicted, 2, ',', '.') ?></b><br>
											<br>
											Verschil<br>
											<b>&euro; <?= number_format(floatval($importResult->ImportPayment->TotalIn) - $totalPredicted, 2, ',', '.') ?></b> (teveel betaald)
										</div>
										<div class="col-md-4">
											Betalingskenmerk uit import:<br>
											<b><?= $importResult->Batch ? 'Batch' : $importResult->ImportPaymentRemark; ?></b>
										</div>
										<div class="col-12">
											Voorgestelde factu(u)r(en) uit izi account:
											<a class="btn btn-round btn-success  btn-fab btn-fab-mini float-right" href="javascript:;" onclick="addInvoice('<?= $i ?>')">
												<i class="material-icons">add</i>
											</a>
											<table id="invoicesTable" class="normal-table-text w-100">
												<?php if (!empty($importResult->PredictedInvoices)) {
													foreach ($importResult->PredictedInvoices as $PredictedInvoice) {
														if ($hasRemark == true && $PredictedInvoice->ImportPaymentRemark == null) {
															continue;
														}
													?>
														<tr id="ruleInvoiceNumber_<?= $ii ?>">
															<td>
																<div class="form-check">
																	<label class="form-check-label">
																		<input class="form-check-input" type="checkbox" name="invoiceChecked[<?= $ii; ?>]"  value="1" />
																		<span class="form-check-sign">
																			<span class="check"></span>
																		</span>
																	</label>
																</div>
																<input type="hidden" name="id[<?= $ii; ?>]" value="<?= $PredictedInvoice->Id ?>" />
																<input type="hidden" name="ruleNumbers[<?= $ii; ?>]" value="<?= $i ?>" />
																<input type="hidden" id="invoiceNumbers_<?= $ii ?>" name="invoiceNumbers[<?= $ii; ?>]" value="<?= $PredictedInvoice->InvoiceNumber; ?>" />
																<input type="hidden" name="invoiceChecked[<?= $ii; ?>]" class="form-check-input" value="0" />
															</td>
															<td>
																<b><?= $PredictedInvoice->InvoiceNumber; ?></b>
															</td>
															<td>&euro; <?= number_format($PredictedInvoice->TotalIn, 2, ',', '.'); ?></td>
															<td>(<?= $PredictedInvoice->PaymentDate ? '<b>reeds betaald</b> op '.date('d-m-Y', $PredictedInvoice->PaymentDate) : 'nog niet betaald' ?>)</td>
															<td class="text-right">
																<a class="btn btn-round btn-success btn-fab btn-fab-mini" href="javascript:;" onclick="changeInvoice(<?= $i.', '.$ii; ?>)">
																	<i class="material-icons">create</i>
																</a>
															</td>
														</tr>
													<?php $ii++; } ?>
												<?php }else{ ?>
													<tr id="no_invoices">
														<td>Er zijn geen facturen gevonden.</td>
													</tr>
												<?php } ?>
											</table>
										</div>

										<div class="col-12">
											<div class="float-left">
												<button type="button" class="btn btn-default" data-target="#carousel" data-slide="prev" <?= ($i == 0) ? 'disabled' : null ?>>Vorige</button>
											</div>
											<div class="float-right">
												<?php if ($i == (count($importResults)) - 1) { ?>
													<button type="submit" class="btn btn-success">Bevestig</button>
												<?php }else{ ?>
													<button type="button" class="btn btn-info" data-target="#carousel" data-slide="next">Volgende</button>
												<?php } ?>
											</div>
										</div>

									</div>
								</div>
							<?php $i++; } ?>
							<input type="hidden" name="confirmBank">
						</div>
					</div>

				</div>
			</div>

		</form>
	</div>

</div>

<script type="text/javascript" src="<?= base_url('assets/js/arrive.min.js') ?>"></script>

<script type="text/javascript">

	var ii = <?= $ii + 1 ?>;
	
	function switch_predicted(ruleNum) {
		var val = $('[name="customer_or_supplier['+ruleNum+']"]:checked').val();
		if (val == 'customer') {
			$("#predictedCustomer"+ruleNum).slideDown();
			$("#predictedSupplier"+ruleNum).slideUp();
		}
		else if (val == 'supplier') {
			$("#predictedSupplier"+ruleNum).slideDown();
			$("#predictedCustomer"+ruleNum).slideUp();
		}
	}

	function changeInvoice(ruleNumber, ruleInvoiceNumber) {
		var customer_or_supplier = $('[name="customer_or_supplier['+ruleNumber+']"]:checked').val();
		var id = '';
		var predictedName = '';
		
		console.log('ruleNumber: ' + ruleNumber);
		console.log('ruleInvoiceNumber: ' + ruleInvoiceNumber);
		
		if (customer_or_supplier == 'customer') {
			id = $("#predictedCustomer"+ruleNumber+" select").val();
			predictedName = $("#predictedCustomer"+ruleNumber+" select option:selected").html();
		}
		else if (customer_or_supplier == 'supplier') {
			id = $("#predictedSupplier"+ruleNumber+" select").val();
			predictedName = $("#predictedSupplier"+ruleNumber+" select option:selected").html();
		}
		
		console.log('id: '+id);
		console.log('predictedName: '+predictedName);
		
		if (id == '') {
			alert('Selecteer eerst een klant/leverancier!');
			return false;
		}

		if (customer_or_supplier == "customer") {
			var url = '<?= base_url(); ?>invoices/search/bank/invoice/' + ruleNumber + '/' + ruleInvoiceNumber + '/' + id;
			$("#customerModal .modal-title").html('<i class="material-icons">layers</i> Verkoopfacturen voor '+predictedName);
		}
		else if (customer_or_supplier == "supplier"){
			var url = '<?= base_url(); ?>invoices/search/bank/invoices/' + ruleNumber + '/' + ruleInvoiceNumber + '/' + id;
			$("#customerModal .modal-title").html('<i class="material-icons">layers</i> Inkoopfacturen voor '+predictedName);
		}

		console.log('Url: '+url);

		$('#modal-body').load(url, function () {
			$('#customerModal').modal();
		});
	}

	function addInvoice(ruleNumber) {
		var customer_or_supplier = $('[name="customer_or_supplier['+ruleNumber+']"]:checked').val();
		var id = '';
		var predictedName = '';
		
		console.log('ruleNumber: ' + ruleNumber);
		console.log('customer_or_supplier: ' + customer_or_supplier);
		
		if (customer_or_supplier == 'customer') {
			id = $("#predictedCustomer"+ruleNumber+" select").val();
			predictedName = $("#predictedCustomer"+ruleNumber+" select option:selected").html();
		}
		else if (customer_or_supplier == 'supplier') {
			id = $("#predictedSupplier"+ruleNumber+" select").val();
			predictedName = $("#predictedSupplier"+ruleNumber+" select option:selected").html();
		}
		console.log('id: '+id);
		console.log('predictedName: '+predictedName);
		
		if (id == '') {
			alert('Selecteer eerst een klant/leverancier!');
		}
		else{
			if (customer_or_supplier == "customer") {
				var url = '<?= base_url(); ?>invoices/search/bank/invoice/' + ruleNumber + '/addNew/' + id;
				$("#customerModal .modal-title").html('<i class="material-icons">layers</i> Verkoopfacturen voor '+predictedName);
			}
			else if (customer_or_supplier == "supplier"){
				var url = '<?= base_url(); ?>invoices/search/bank/invoices/' + ruleNumber + '/addNew/' + id;
				$("#customerModal .modal-title").html('<i class="material-icons">layers</i> Inkoopfacturen voor '+predictedName);
			}

			console.log('url: '+url);

			$('#modal-body').load(url, function () {
				$('#customerModal').modal();
			});
		}
	}

	function setPredictedInvoice(ruleNumber, ruleInvoiceNumber, invoiceNumber, invoiceId, invoiceIdTotalIn, invoicePaymentDate) {
		console.log('ruleNumber: '+ruleNumber);
		console.log('ruleInvoiceNumber: '+ruleInvoiceNumber);
		console.log('invoiceNumber: '+invoiceNumber);
		console.log('invoiceId: '+invoiceId);
		console.log('invoiceIdTotalIn: '+invoiceIdTotalIn);
		console.log('invoicePaymentDate: '+invoicePaymentDate);

		$('#customerModal').modal('hide');

		var add_new = false;
		if (ruleInvoiceNumber == 'addNew') {
			add_new = true;
			ruleInvoiceNumber = ii;
		}

		if (add_new == true) {
			var html = '<tr id="ruleInvoiceNumber_'+ruleInvoiceNumber+'"><td>';
		}
		else{
			var html = '<td>';
		}
		html += '<div class="form-check">';
		html += '<label class="form-check-label">';
		html += '<input class="form-check-input" type="checkbox" name="invoiceChecked['+ruleInvoiceNumber+']" value="1" />';
		html += '<span class="form-check-sign">';
		html += '<span class="check"></span>';
		html += '</span>';
		html += '</label>';
		html += '</div>';
		html += '<input type="hidden" name="id['+ruleInvoiceNumber+']" value="'+invoiceId+'" />';
		html += '<input type="hidden" name="ruleNumbers['+ruleInvoiceNumber+']" value="'+ruleNumber+'" />';
		html += '<input type="hidden" id="invoiceNumbers_'+ruleInvoiceNumber+'" name="invoiceNumbers['+ruleInvoiceNumber+']" value="'+invoiceNumber+'" />';
		html += '<input type="hidden" name="invoiceChecked['+ruleInvoiceNumber+']" class="form-check-input" value="0" />';
		html += '</td>';
		html += '<td><b>'+invoiceNumber+'</b></td>';
		html += '<td>'+invoiceIdTotalIn+'</td>';
		html += '<td>'+invoicePaymentDate+'</td>';
		html += '<td class="text-right"><a class="btn btn-round btn-success btn-fab btn-fab-mini" href="javascript:;" onclick="changeInvoice('+ruleNumber+', '+ruleInvoiceNumber+')"><i class="material-icons">create</i></a>';
		if (add_new == true) {
			html += '</tr></td>';
		}
		else{
			html += '</td>';
		}

		$("#importRule_"+ruleNumber+" #no_invoices").remove();

		if (add_new == true) {
			$("#importRule_"+ruleNumber+" #invoicesTable").append(html);
		}
		else{
			$("#ruleInvoiceNumber_"+ruleInvoiceNumber).html(html);
		}

		ii++;
	}

</script>

<?php include 'application/views/inc/footer.php'; ?>

<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="myModalLabel">
					<i class="material-icons">layers</i> Klanten
				</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<i class="material-icons">close</i>
				</button>
			</div>
			<div id="modal-body">

			</div>

		</div>
	</div>
</div>
