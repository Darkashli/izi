<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Resultaat import orderlijst');
define('PAGE', 'import');

include 'application/views/inc/header.php';
?>

<form method="post" id="orderForm">
	<input type="hidden" name="csvRow" value="<?= $ruleNumber; ?>" />

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">subject</i>
					</div>
					<h4 class="card-title">Klantgegevens</h4>
				</div>
				<div class="card-body">
					<div class="notification-bar alert" style="display: none;"></div>
					<div class="togglebutton">
						<label>
							<input type="checkbox" name="anonymous_customer" onchange="toggleAnonymousCustomer(this)" <?= $anonymousCustomer == true ? 'checked' : null ?>>
							Eenmalige klant
						</label>
					</div>
					<div id="existing-customers" class="<?= $anonymousCustomer == true ? 'hidden' : null ?>">
						<div class="row">
							<div class="col-12 form-group">
								<label>Klant <small>*</small></label>
								<select class="form-control" name="customer" <?= $anonymousCustomer == false ? 'required' : null ?>>
									<option></option>
									<?php foreach ($customers as $customer): ?>
										<option value="<?= $customer->Id ?>"><?= $customer->Name ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
					<div id="anonymous-customers" class="<?= $anonymousCustomer == false ? 'hidden' : null ?>">
						<div class="row">
							<div class="col-12 form-group label-floating">
								<label class="control-label">Klantnaam</label>
								<input type="text" name="company_name" class="form-control" value="<?= $salesOrder->CompanyName ?>" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Voornaam</label>
								<input type="text" name="front_name" class="form-control" value="<?= $salesOrder->FrontName ?>" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Tussenvoegsel</label>
								<input type="text" name="insertion" class="form-control" value="<?= $salesOrder->Insertion ?>" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Achternaam</label>
								<input type="text" name="last_name" class="form-control" value="<?= $salesOrder->LastName ?>" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Straat / Postbus</label>
								<input type="text" name="address" class="form-control" value="<?= $salesOrder->Address ?>" />
							</div>
							<div class="col-md-2 form-group label-floating">
								<label class="control-label">Huisnummer</label>
								<input type="number" name="address_number" class="form-control" value="<?= $salesOrder->AddressNumber ?>" />
							</div>
							<div class="col-md-2 form-group label-floating">
								<label class="control-label">Toevoeging</label>
								<input type="text" name="address_addition" class="form-control" value="<?= $salesOrder->AddressAddition ?>" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-3 form-group label-floating">
								<label class="control-label">Postcode</label>
								<input type="text" name="zip_code" class="form-control" value="<?= $salesOrder->ZipCode ?>" />
							</div>
							<div class="col-md-5 form-group label-floating">
								<label class="control-label">Woonplaats</label>
								<input type="text" name="city" class="form-control" value="<?= $salesOrder->City ?>" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Land</label>
								<input type="text" name="country" class="form-control" value="<?= $salesOrder->Country ?>" />
							</div>
						</div>
						<div class="row">
							<div class="col-12 form-group label-floating">
								<label class="control-label">E-mailadres</label>
								<input type="text" name="mail_address" class="form-control" value="<?= $salesOrder->MailAddress ?>" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-12 form-group">
								<label>Betalingsconditie <small>*</small></label>
								<select class="form-control" name="paymentcondition" <?= $anonymousCustomer == true ? 'required' : null ?>>
									<?php foreach ($paymentConditions as $paymentCondition): ?>
										<option value="<?= $paymentCondition->Name ?>" <?= $paymentCondition->Name == $salesOrder->PaymentCondition ? 'selected' : NULL ?>><?= $paymentCondition->Name ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6 col-sm-12 form-group label-floating">
								<label class="control-label">Termijn (dagen) <small>*</small></label>
								<input name="termofpayment" class="form-control" type="number" value="<?= $salesOrder->TermOfPayment ?>" <?= $anonymousCustomer == true ? 'required' : null ?>/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">content_paste</i>
					</div>
					<h4 class="card-title">Ordergegevens</h4>
				</div>
				<div class="card-body">
					<div class="row mt-2">
						<div class="col-md-4 col-lg-2 input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="material-icons">date_range</i>
								</span>
							</div>
							<div class="form-group label-floating">
								<label class="control-label">Orderdatum <small>*</small></label>
								<input class="form-control date-picker" type="text" name="orderdate" value="<?= date('d-m-Y', strtotime($salesOrder->OrderDate)); ?>" required />
							</div>
						</div>
						<div class="col-lg-3 col-md-4 col-sm-12 form-group">
							<label>Verkoopkanaal</label>
							<select class="form-control" name="seller" id="seller" onchange="setDefaultTransporter()">
								<option></option>
								<?php foreach ($sellers as $seller) { ?>
									<option value="<?= $seller->Seller_id ?>" data-transporter="<?= $seller->Default_transport ?>" data-onlyoption="<?= $seller->Only_option ?>" <?= $seller->Seller_id == $salesOrder->Seller_id ? 'selected' : null ?> ><?= $seller->Name ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="col-lg-3 col-md-4 col-sm-12 form-group">
							<label>Vervoerder</label>
							<select class="form-control" name="transport" id="transport" value="Default_transport">
								<option></option>
								<?php foreach ($transporters as $transporter) { ?>
									<option value="<?= $transporter->Transporter_id ?>" <?= $transporter->Transporter_id == $salesOrder->Transport_id ? 'selected' : null ?>><?= $transporter->Name ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="col-lg-2 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Referentie</label>
							<input name="reference" value="<?= $salesOrder->Reference; ?>" class="form-control" type="text" autocomplete="off" />
						</div>

						<div class="col-lg-2 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Aantal colli</label>
							<input name="colli" value="<?= $salesOrder->Colli; ?>" class="form-control" type="text" autocomplete="off" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">subject</i>
					</div>
					<h4 class="card-title">Orderregels</h4>
				</div>
				<div class="card-body">
					<div class="row mt-2">
						<div class="col-12">
							<table class="table table-borderless" id="tableInvoice">
								<thead>
									<tr>
										<td class="w16">Artikelnummer</td>
										<td class="w10">EAN code</td>
										<td class="w33">Artikelomschrijving</td>
										<td class="w7">Aantal</td>
										<td class="w10" id="priceT">Verkoopprijs</td>
										<td class="w7" id="discountT">Korting (%)</td>
										<td>BTW</td>
										<td class="w10">Totaal</td>
										<td>&nbsp;</td>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;
									if ($salesOrderRules != NULL) {
										foreach ($salesOrderRules as $salesOrderRule) {
											?>
											<tr id="invoiceRow<?= $i ?>">
												<td>
													<input name="articlenumber<?= $i; ?>" id="articlenumber" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumber(this, <?= $i ?>)" value="<?= $salesOrderRule->ArticleC; ?>" required />
													<ul class="list-group suggestions-listgroup clickable"></ul>
												</td>
												<td>
													<input name="ean_code<?= $i ?>" id="ean_code" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCode(this, <?= $i ?>)" value="<?= $salesOrderRule->EanCode; ?>" />
													<ul class="list-group suggestions-listgroup clickable"></ul>
												</td>
												<td>
													<input name="articledescription<?= $i; ?>" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, <?= $i ?>" value="<?= $salesOrderRule->Description; ?>" />
													<ul class="list-group suggestions-listgroup clickable"></ul>
												</td>
												<td><input name="amount<?= $i; ?>" class="form-control" type="number" value="<?= $salesOrderRule->Amount; ?>" onchange="calculateRow(<?= $i; ?>)" step="any"  /></td>
												<td><input name="salesprice<?= $i; ?>" class="form-control" type="number" value="<?= $salesOrderRule->Price; ?>" onchange="calculateRow(<?= $i; ?>)" step="any" /></td>
												<td><input name="discount<?= $i; ?>" class="form-control" type="number" value="<?= $salesOrderRule->Discount; ?>" onchange="calculateRow(<?= $i; ?>)" /></td>
												<td><?= form_dropdown('tax' . $i, unserialize(TAXDROPDOWN), $salesOrderRule->Tax, CLASSDROPDOWN . 'onchange="calculateRow(' . $i . ')"'); ?></td>
												<td><input name="total<?= $i; ?>" class="form-control" type="number" value="<?= $salesOrderRule->Total; ?>" readonly /> <input id="number" name="number[]" type="hidden" readonly value="<?= $i; ?>" /></td>
												<td class="td-actions text-right">
													<button type="button" onclick="removeRow(<?= $i ?>)" class="btn btn-danger btn-round btn-fab btn-fab-mini">
														<i class="material-icons">close</i>
													</button>
												</td>
											</tr>
											<?php
											$i++;
										}
									}
									else { ?>
										<tr id="invoiceRow1">
											<td>
												<input name="articlenumber1" id="articlenumber" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumber(this, 1)" required />
												<ul class="list-group suggestions-listgroup clickable"></ul>
											</td>
											<td>
												<input name="ean_code1" id="ean_code" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCode(this, 1)" />
												<ul class="list-group suggestions-listgroup clickable"></ul>
											</td>
											<td>
												<input name="articledescription1" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, 1)" />
												<ul class="list-group suggestions-listgroup clickable"></ul>
											</td>
											<td><input name="amount1" class="form-control" type="number" value="0" onchange="calculateRow(1)" step="any"  /></td>
											<td><input name="salesprice1" class="form-control" type="number" value="0.00" onchange="calculateRow(1)" step="any" /></td>
											<td><input name="discount1" class="form-control" type="number" value="0" onchange="calculateRow(1)" /></td>
											<td>
												<div class="form-group">
													<select name="tax1" class="form-control" onchange="calculateRow(1)">
														<option value="21">21%</option>
														<option value="9">9%</option>
														<option value="0">0%</option>
													</select>
												</div>
											</td>
											<td><input name="total1" class="form-control" type="number" value="0.00" readonly /> <input id="number" name="number[]" type="hidden" readonly value="1" /></td>
											<td class="td-actions text-right">
												<button type="button" rel="tooltip" onclick="removeRow(1)" class="btn btn-danger btn-round btn-fab btn-fab-mini">
													<i class="material-icons">close</i>
												</button>
											</td>
										</tr>
										<?php $i++; ?>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td class="td-actions text-right">
											<button type="button" rel="tooltip" onclick="addRow()" class="btn btn-success btn-round btn-fab btn-fab-mini">
												<i class="material-icons">add</i>
											</button>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">euro_symbol</i>
					</div>
					<h4 class="card-title">Totaal</h4>
				</div>
				<div class="card-body">
					<div class="row mt-2">
						<div class="col-lg-3 col-md-6">
							<div class="form-group label-floating">
								<label>Totaal excl. BTW</label>
								<div class="pt-2" id="subtotal">0.00</div>
							</div>
						</div>

						<div class="col-lg-3 col-md-6">
							<div class="form-group label-floating">
								<label>Totaal 9% BTW</label>
								<div class="pt-2" id="subtax6">0.00</div>
							</div>
						</div>

						<div class="col-lg-3 col-md-6">
							<div class="form-group label-floating">
								<label>Totaal 21% BTW</label>
								<div class="pt-2" id="subtax21">0.00</div>
							</div>
						</div>

						<div class="col-lg-3 col-md-6">
							<div class="form-group label-floating">
								<label>Totaal incl. BTW</label>
								<div class="pt-2" id="subtotalIn">0.00</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

	</div>
</form>

<div class="row justify-content-center">
	<div class="col-md-4 flex-md-last">
		<button class="btn btn-block btn-success" type="submit" form="orderForm" name="action" value="save">Accepteren en volgende</button>
		<button class="btn btn-block btn-success" type="submit" form="orderForm" name="action" value="save_and_print">Accepteren, printen en volgende</button>
	</div>
	<div class="col-md-4 flex-md-first">
		<form method="post">
			<input type="hidden" name="csvRow" value="<?= $ruleNumber; ?>" />
			<button class="btn btn-block btn-danger" type="submit" name="action" value="discard">Verwijderen en volgende</button>
		</form>
		<form method="post">
			<button class="btn btn-block btn-danger" type="submit" name="action" value="abort">Stoppen</button>
		</form>
	</div>
</div>

<script type="text/javascript">
	var calculatePurchase = 0;
	var currentRow = <?= $i ?>;
	var articles = {};

	$(document).ready(function () {
		
		calculateSubTotal();
		
		<?php if ($this->input->server('REQUEST_METHOD') == 'POST' && $_POST['action'] == 'save_and_print') { ?>
			location.href = '<?= base_url("SalesOrders/salesOrderPDF/$salesOrderId") ?>';
		<?php } ?>
		
		$('.date-picker').datetimepicker({
			locale: 'nl-NL',
			format: 'L',
			icons: datetimepickerIcons
		});
		
		<?php if ($salesOrderRules == NULL) { ?>
			swal({
				title: 'Product niet gevonden',
				text: 'Er is geen product gevonden voor deze order. Selecteer handmatig een product.',
				type: 'warning',
				confirmButtonText: 'Ok'
			});
		<?php } ?>
		
	});
	
	$(document).keydown(function(e){
		if (e.which == 107) { // "+" key.
			if ($(".table input").is(':focus')) {
				e.preventDefault();
				$('.suggestions-listgroup').html('');
				addRow();
				$('input[name="articlenumber'+(currentRow - 1)+'"]').focus();
			}
		}
 	});

	function calculateRow(i) {
		total = 0;
		if (calculatePurchase == 1) {
			if (document.getElementsByName('amount' + i)[0].value > 0) {
				total = (document.getElementsByName('amount' + i)[0].value * (document.getElementsByName('salesprice' + i)[0].value / (1 - (document.getElementsByName('discount' + i)[0].value) / 100)));
			}
			if (document.getElementsByName('salesprice' + i)[0].value > 0) {
				total = (document.getElementsByName('amount' + i)[0].value * (document.getElementsByName('salesprice' + i)[0].value / (1 - (document.getElementsByName('discount' + i)[0].value) / 100)));
			}
		} else {

			if (document.getElementsByName('amount' + i)[0].value > 0) {
				total = (document.getElementsByName('amount' + i)[0].value * document.getElementsByName('salesprice' + i)[0].value) * (1 - (document.getElementsByName('discount' + i)[0].value / 100));
			}
			if (document.getElementsByName('salesprice' + i)[0].value > 0) {
				total = parseFloat((document.getElementsByName('amount' + i)[0].value * document.getElementsByName('salesprice' + i)[0].value) * (1 - (document.getElementsByName('discount' + i)[0].value / 100)));
			}
		}
		document.getElementsByName('total' + i)[0].value = total.toFixed(2);

		calculateSubTotal();
	}

	function removeRow(i) {
		$('#invoiceRow' + i).remove();
	}

	function addRow() {
		var myRow = '<tr id="invoiceRow' + currentRow + '">';
		myRow += '<td><div class="form-group"><input name="articlenumber' + currentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumber(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td><div class="form-group"><input name="ean_code' + currentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCode(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td><div class="form-group"><input name="articledescription' + currentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td><div class="form-group"><input name="amount' + currentRow + '" class="form-control" type="number" value="0" onchange="calculateRow(' + currentRow + ')" step="any" /></div></td>';
		myRow += '<td><div class="form-group"><input name="salesprice' + currentRow + '" class="form-control" type="number" value="0.00" onchange="calculateRow(' + currentRow + ')" step="any" /></div></td>';
		myRow += '<td><div class="form-group"><input name="discount' + currentRow + '" class="form-control" type="number" value="0" onchange="calculateRow(' + currentRow + ')" /></div></td>';
		myRow += '<td><div class="form-group"><select name="tax' + currentRow + '" class="form-control" onchange="calculateRow(' + currentRow + ')" ></div>';
		myRow += '<option value="21">21%</option>';
		myRow += '<option value="9">9%</option>';
		myRow += '<option value="0">0%</option>';
		myRow += '</select></div></td>';
		myRow += '<td><div class="form-group"><input name="total' + currentRow + '" class="form-control" type="number" value="0.00" readonly /> <input id="number" name="number[]" type="hidden" readonly value="' + currentRow + '" /></td>';
		myRow += '<td class="td-actions text-right"><button type="button" onclick="removeRow(' + currentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i><div class="ripple-container"></div></button></td>';
		myRow += '</tr>';
		$("#tableInvoice tbody").append(myRow);
		currentRow++;
	}

	function calculateSubTotal() {
		subtotal = 0;
		subtotalIn = 0;
		tax6 = 0;
		tax21 = 0;

		$('#tableInvoice tbody').find('tr').each(function (i, el) {
			var rowNumber = $(el).find("#number")[0].value;
			var total = parseFloat(document.getElementsByName('total' + rowNumber)[0].value);
			var tax = document.getElementsByName('tax' + rowNumber)[0].value;
			var subTax = 0;

			switch (tax) {
				case '9':
					subTax = total * (9 / 100);
					tax6 += subTax;
					break;
				case '21':
					subTax = total * (21 / 100);
					tax21 += subTax;
					break;
				default:
					break;
			}

			subtotal += total;
			subtotalIn += subTax;
			subtotalIn += total;

		});

		document.getElementById("subtotal").innerHTML = subtotal.toFixed(2);
		document.getElementById("subtotalIn").innerHTML = subtotalIn.toFixed(2);
		document.getElementById("subtax6").innerHTML = tax6.toFixed(2);
		document.getElementById("subtax21").innerHTML = tax21.toFixed(2);
	}

	function setDefaultTransporter(){
		var defaultTransporter = $("#seller").find(":selected").attr("data-transporter");
		$("#transport").find('[value="'+defaultTransporter+'"]').prop("selected", true);
		$("#transport").closest(".form-group").removeClass("is-empty");

		var onlyOption = $("#seller").find(":selected").attr("data-onlyoption");
		if (onlyOption == 1) {
			$("#transport").attr("style", "pointer-events: none;");
		}
		else{
			$("#transport").attr("style", "pointer-events: unset;");
		}
	};
	
	function searchArticlenumber(element, row){
		var value = $(element).val().trim();

		$.ajax({
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/ArticleNumber/0'
		}).done(function(msg){
			articles = JSON.parse(msg);
			var html = '';

			$.each(articles.products, function(index, value){
				html += '<li class="list-group-item list-group-item-action" onclick="clickSuggestionList('+row+', '+index+')"><span class="font-weight-bold mr-1">'+value.ArticleNumber+'</span> | '+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</li>';
			});

			$(element).closest("td").find("ul.suggestions-listgroup").html(html);
		});
	}

	function searchEanCode(element, row){
		var value = $(element).val().trim();

		$.ajax({
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/EanCode/0'
		}).done(function(msg){
			articles = JSON.parse(msg);
			var html = '';

			$.each(articles.products, function(index, value){
				html += '<li class="list-group-item list-group-item-action" onclick="clickSuggestionList('+row+', '+index+')"><span class="font-weight-bold mr-1">'+value.EanCode+'</span> | '+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</li>';
			});

			$(element).closest("td").find("ul.suggestions-listgroup").html(html);
		});
	}

	function searchDescription(element, row){
		var value = $(element).val().trim();

		$.ajax({
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/Description/0'
		}).done(function(msg){
			articles = JSON.parse(msg);
			var html = '';

			$.each(articles.products, function(index, value){
				html += '<li class="list-group-item list-group-item-action" onclick="clickSuggestionList('+row+', '+index+')">'+value.ArticleNumber+' | <span class="font-weight-bold ml-1">'+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</span></li>';
			});

			$(element).closest("td").find("ul.suggestions-listgroup").html(html);
		});
	}

	function clickSuggestionList(row, index){
		setArticlenumber(row, articles['products'][index]['ArticleNumber']);
		setEanCode(row, articles['products'][index]['EanCode']);
		setArticledescription(row, articles['products'][index]['Description']);
		setSalesprice(row, articles['products'][index]['SalesPrice']);
		setBtw(row, articles['products'][index]['BTW']);
		calculateRow(row);
	}

	function setArticlenumber(row, articlenumber) {
		document.getElementsByName('articlenumber' + row)[0].value = articlenumber;
	}

	function setEanCode(row, eanCode) {
		document.getElementsByName('ean_code' + row)[0].value = eanCode;
	}

	function setArticledescription(row, articledescription) {
		document.getElementsByName('articledescription' + row)[0].value = articledescription;
	}

	function setSalesprice(row, salesprice) {
		document.getElementsByName('salesprice' + row)[0].value = salesprice;
	}

	function setBtw(row, btw) {
		document.getElementsByName('tax' + row)[0].value = btw;
		// $('#invoiceModal').modal('hide');
		calculateRow(row);
	}
	
	function toggleAnonymousCustomer(element) {
		if ($(element).is(":checked")) {
			$('[name="customer"]').prop("required", false);
			$('[name="paymentcondition"]').prop("required", true);
			$('[name="termofpayment"]').prop("required", true);
			$("#anonymous-customers").slideDown();
			$("#existing-customers").slideUp();
		}
		else{
			$('[name="customer"]').prop("required", true);
			$('[name="paymentcondition"]').prop("required", false);
			$('[name="termofpayment"]').prop("required", true);
			$("#anonymous-customers").slideUp();
			$("#existing-customers").slideDown();
		}
	}

</script>

<?php include 'application/views/inc/footer.php'; ?>
