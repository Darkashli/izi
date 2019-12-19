<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Inkoopfactuur');
define('PAGE', 'invoiceS');
if ($supplier != null) {
	define('SUBTITEL', getSupplierName($supplier->Id) . ' (' . $supplier->Id . ')');
}

include 'application/views/inc/header.php';
?>

<form method="post">
	<div class="row">

		<?php if ($supplier == null) { ?>
			<div class="col-12">
				<div class="card">
					<div class="card-header card-header-icon card-header-primary">
						<div class="card-icon">
							<i class="material-icons">subject</i>
						</div>
					</div>
					<div class="card-body">
						<h4 class="card-title">Leveranciergegevens</h4>
						<div class="notification-bar alert" style="display: none;"></div>
						<div class="row">
							<div class="col-12 form-group label-floating">
								<label class="control-label">Bedrijfsnaam</label>
								<input type="text" name="company_name" class="form-control" value="<?= $invoice->CompanyName ?>" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Voornaam</label>
								<input type="text" name="front_name" class="form-control" value="<?= $invoice->FrontName ?>" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Tussenvoegsel</label>
								<input type="text" name="insertion" class="form-control" value="<?= $invoice->Insertion ?>" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Achternaam</label>
								<input type="text" name="last_name" class="form-control" value="<?= $invoice->LastName ?>" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Straat / Postbus</label>
								<input type="text" name="address" class="form-control" value="<?= $invoice->Address ?>" />
							</div>
							<div class="col-md-2 form-group label-floating">
								<label class="control-label">Huisnummer</label>
								<input type="number" name="address_number" class="form-control" value="<?= $invoice->AddressNumber ?>" />
							</div>
							<div class="col-md-2 form-group label-floating">
								<label class="control-label">Toevoeging</label>
								<input type="text" name="address_addition" class="form-control" value="<?= $invoice->AddressAddition ?>" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-3 form-group label-floating">
								<label class="control-label">Postcode</label>
								<input type="text" name="zip_code" class="form-control" value="<?= $invoice->ZipCode ?>" />
							</div>
							<div class="col-md-5 form-group label-floating">
								<label class="control-label">Woonplaats</label>
								<input type="text" name="city" class="form-control" value="<?= $invoice->City ?>" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Land</label>
								<input type="text" name="country" class="form-control" value="<?= $invoice->Country ?>" />
							</div>
						</div>
						<div class="row">
							<div class="col-12 form-group label-floating">
								<label class="control-label">E-mailadres</label>
								<input type="text" name="mail_address" class="form-control" value="<?= $invoice->MailAddress ?>" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-12 form-group">
								<label>Betalingsconditie</label>
								<select class="form-control" name="paymentcondition">
									<?php foreach ($paymentConditions as $paymentCondition): ?>
										<option value="<?= $paymentCondition->Name ?>" <?= $paymentCondition->Name == $invoice->PaymentCondition ? 'selected' : NULL ?>><?= $paymentCondition->Name ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6 col-sm-12 form-group label-floating">
								<label class="control-label">Betaaltermijn (dagen) <small>*</small></label>
								<input name="term_of_payment" class="form-control" type="number" value="<?= $invoice->TermOfPayment ?>" required/>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">content_paste</i>
					</div>
					<h4 class="card-title">Factuurgegevens</h4>
				</div>
				<div class="card-body">
					<div class="row mt-2">
						
						<div class="col-lg col-md-6 input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="material-icons">date_range</i>
								</span>
							</div>
							<div class="form-group label-floating">
								<label class="control-label">Factuurdatum <small>*</small></label>
								<input class="form-control date-picker" type="text" name="invoicedate" value="<?= date('d-m-Y', $invoice->InvoiceDate); ?>" required />
							</div>
						</div>

						<div class="col-lg col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Factuurnummer <small>*</small></label>
							<input name="invoicenumber" type="text" class="form-control" value="<?= $invoice->InvoiceNumber ?>" required />
						</div>

						<div class="col-lg col-md-6 input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="material-icons">date_range</i>
								</span>
							</div>
							<div class="form-group label-floating">
								<label class="control-label">periode van</label>
								<input class="form-control date-picker" type="text" name="period_date_from" value="<?= $invoice->PeriodDateFrom ? date('d-m-Y', $invoice->PeriodDateFrom) : null; ?>" />
							</div>
						</div>

						<div class="col-lg col-md-6 input-goup">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="material-icons">date_range</i>
								</span>
								<div class="form-group label-floating">
									<label class="control-label">periode tot</label>
									<input class="form-control date-picker" type="text" name="period_date_to" value="<?= $invoice->PeriodDateTo ? date('d-m-Y', $invoice->PeriodDateTo) : null; ?>" />
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">subject</i>
					</div>
					<h4 class="card-title">Factuurgegevens</h4>
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
									foreach ($invoiceRules as $invoiceRule) {
										?>
										<tr id="invoiceRow<?= $i ?>">
											<td>
												<input name="articlenumber<?= $i; ?>" id="articlenumber" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumber(this, <?= $i ?>)" value="<?= $invoiceRule->ArticleC; ?>" required />
												<ul class="list-group suggestions-listgroup clickable"></ul>
											</td>
											<td>
												<input name="ean_code<?= $i ?>" id="ean_code" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCode(this, <?= $i ?>)" />
												<ul class="list-group suggestions-listgroup clickable"></ul>
											</td>
											<td>
												<input name="articledescription<?= $i; ?>" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, <?= $i ?>)" value="<?= $invoiceRule->Description; ?>" />
												<ul class="list-group suggestions-listgroup clickable"></ul>
											</td>
											<td><input name="amount<?= $i; ?>" class="form-control" type="number" value="<?= $invoiceRule->Amount; ?>" onchange="calculateRow(<?= $i; ?>)" step="any"  /></td>
											<td><input name="purchaseprice<?= $i; ?>" class="form-control" type="number" value="<?= $invoiceRule->Price; ?>" onchange="calculateRow(<?= $i; ?>)" step="any" /></td>
											<td><input name="discount<?= $i; ?>" class="form-control" type="number" value="<?= $invoiceRule->Discount; ?>" onchange="calculateRow(<?= $i; ?>)" /></td>
											<td>
												<div class="form-group">
													<select name="tax<?= $i; ?>" class="form-contorl" onchange="calculateRow(<?= $i; ?>)">
														<option <?= $invoiceRule->Tax == 21 ? 'selected' : ''; ?> value="21">21%</option>
														<option <?= $invoiceRule->Tax == 9 ? 'selected' : ''; ?> value="9">9%</option>
														<option <?= $invoiceRule->Tax == 0 ? 'selected' : ''; ?> value="0">0%</option>
													</select>
												</div>
											</td>
											<td><input name="total<?= $i; ?>" class="form-control" type="number" value="<?= $invoiceRule->Total; ?>" readonly /> <input id="number" name="number[]" type="hidden" readonly value="<?= $i; ?>" /></td>
											<td class="td-actions text-right">
												<button type="button" onclick="removeRow(<?= $i ?>)" class="btn btn-danger btn-round btn-fab btn-fab-mini">
													<i class="material-icons">close</i>
												</button>
												<input type="hidden" name="ruleNumber<?= $i; ?>" value="<?= $invoiceRule->Id; ?>" />
											</td>
										</tr>
										<?php
										$i++;
									}
									?>
								</tbody>
								<tfoot>
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
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">chat</i>
					</div>
					<h4 class="card-title">Opmerking</h4>
				</div>
				<div class="card-body">
					<div class="row mt-2">
						<div class="col-12">
							<textarea name="description" class="editortools" rows="10"><?= $invoice->Description ?></textarea>
						</div>
					</div>
				</div>
			</div>

			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">euro_symbol</i>
					</div>
					<h4 class="card-title">Factuurgegevens</h4>
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

	<div class="row justify-content-center">
		<div class="col-md-4">
			<button class="btn btn-block btn-success" type="submit">Opslaan</button>
		</div>
		<div class="col-md-4">
			<button class="btn btn-block btn-danger" type="button" onclick="deleteInvoice()">Verwijderen</button>
		</div>
	</div>

</form>

<script type="text/javascript">
	var calculatePurchase = 1;
	var currentRow = <?= $i ?>;
	var articles = {};
	var supplierId = <?= $supplier != null ? $supplier->Id : 0 ?>;

	$(document).ready(function () {
		
		calculateSubTotal();
		
		$('.date-picker').datetimepicker({
			locale: 'nl-NL',
			format: 'L',
			icons: datetimepickerIcons
		});
		
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

	function changePurchase() {
		if (calculatePurchase == 1) {
			document.getElementById('priceT').innerHTML = "Verkoopprijs";
			calculatePurchase = 0;
		} else {
			document.getElementById('priceT').innerHTML = "Inkoopprijs";
			calculatePurchase = 1;
		}
	}

	function stopRKey(evt) {
		var evt = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type == "text")) {
			return false;
		}
	}

	document.onkeypress = stopRKey;

	function calculateRow(i) {
		total = 0;
		if (calculatePurchase == 1) {
			if (document.getElementsByName('amount' + i)[0].value > 0) {
				total = (document.getElementsByName('amount' + i)[0].value * (document.getElementsByName('purchaseprice' + i)[0].value / (1 - (document.getElementsByName('discount' + i)[0].value) / 100)));
			}
			if (document.getElementsByName('purchaseprice' + i)[0].value > 0) {
				total = (document.getElementsByName('amount' + i)[0].value * (document.getElementsByName('purchaseprice' + i)[0].value / (1 - (document.getElementsByName('discount' + i)[0].value) / 100)));
			}
		} else {

			if (document.getElementsByName('amount' + i)[0].value > 0) {
				total = (document.getElementsByName('amount' + i)[0].value * document.getElementsByName('purchaseprice' + i)[0].value) * (1 - (document.getElementsByName('discount' + i)[0].value / 100));
			}
			if (document.getElementsByName('purchaseprice' + i)[0].value > 0) {
				total = parseFloat((document.getElementsByName('amount' + i)[0].value * document.getElementsByName('purchaseprice' + i)[0].value) * (1 - (document.getElementsByName('discount' + i)[0].value / 100)));
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
		myRow += '<td><div class="form-group"><input name="purchaseprice' + currentRow + '" class="form-control" type="number" value="0.00" onchange="calculateRow(' + currentRow + ')" step="any" /></div></td>';
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

	function deleteInvoice(){
		var linkURL = '<?= base_url('Invoices/DeleteInvoiceS/'.$invoice->Id) ?>';
		swal({
			title: 'Weet u het zeker?',
			text: 'Deze actie kan niet ongedaan worden gemaakt!',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ja, verwijder dit!'
		}).then((result) => {
			if (result.value) {
				window.location.href = linkURL;
			}
		});
	}
	
	function searchArticlenumber(element, row){
		var value = $(element).val().trim();
		var url = '<?= base_url('product/searchProductsP') ?>';

		$.ajax({
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/ArticleNumber/1/all/supplierId'
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
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/EanCode/1/all/supplierId'
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
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/Description/1/all/supplierId'
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
		setPurchaseprice(row, articles['products'][index]['PurchasePrice']);
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

	function setPurchaseprice(row, purchaseprice) {
		document.getElementsByName('purchaseprice' + row)[0].value = purchaseprice;
	}

	function setBtw(row, btw) {
		document.getElementsByName('tax' + row)[0].value = btw;
		// $('#invoiceModal').modal('hide');
		calculateRow(row);
	}

</script>

<?php include 'application/views/inc/footer.php'; ?>
