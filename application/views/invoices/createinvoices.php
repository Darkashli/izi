<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Inkoopfactuur');
define('SUBTITEL', getSupplierName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
define('PAGE', 'invoiceS');

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
						<h4 class="card-title">Leveranciergegevens</h4>
					</div>
					<div class="card-body">
						<div class="notification-bar alert" style="display: none;"></div>
						<div class="row">
							<div class="col-12 form-group label-floating">
								<label class="control-label">Bedrijfsnaam</label>
								<input type="text" name="company_name" class="form-control" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Voornaam</label>
								<input type="text" name="front_name" class="form-control" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Tussenvoegsel</label>
								<input type="text" name="insertion" class="form-control" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Achternaam</label>
								<input type="text" name="last_name" class="form-control" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Straat / Postbus</label>
								<input type="text" name="address" class="form-control" />
							</div>
							<div class="col-md-2 form-group label-floating">
								<label class="control-label">Huisnummer</label>
								<input type="number" name="address_number" class="form-control" />
							</div>
							<div class="col-md-2 form-group label-floating">
								<label class="control-label">Toevoeging</label>
								<input type="text" name="address_addition" class="form-control" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-3 form-group label-floating">
								<label class="control-label">Postcode</label>
								<input type="text" name="zip_code" class="form-control" />
							</div>
							<div class="col-md-5 form-group label-floating">
								<label class="control-label">Woonplaats</label>
								<input type="text" name="city" class="form-control" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Land</label>
								<input type="text" name="country" class="form-control" />
							</div>
						</div>
						<div class="row">
							<div class="col-12 form-group label-floating">
								<label class="control-label">E-mailadres</label>
								<input type="text" name="mail_address" class="form-control" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-12 form-group">
								<label class="control-label">Betalingsconditie</label>
								<select class="form-control" name="paymentcondition">
									<?php foreach ($paymentConditions as $paymentCondition): ?>
										<option value="<?= $paymentCondition->Name ?>"><?= $paymentCondition->Name ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6 col-sm-12 form-group label-floating">
								<label class="control-label">Betaaltermijn (dagen) <small>*</small></label>
								<input name="term_of_payment" class="form-control" type="number" required/>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<div class="col-12">
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
								<input class="form-control date-picker" type="text" name="invoicedate" value="<?= date('d-m-Y'); ?>" />
							</div>
						</div>

						<div class="col-lg col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Factuurnummer <small>*</small></label>
							<input name="invoicenumber" type="text" class="form-control" required />
						</div>

						<div class="col-lg col-md-6 input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="material-icons">date_range</i>
								</span>
							</div>
							<div class="form-group label-floating">
								<label class="control-label">Periode van</label>
								<input class="form-control date-picker" type="text" name="period_date_from" />
							</div>
						</div>

						<div class="col-lg col-md-6 input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="material-icons">date_range</i>
								</span>
							</div>
							<div class="form-group label-floating">
								<label class="control-label">Periode tot</label>
								<input class="form-control date-picker" type="text" name="period_date_to" />
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
					<h4 class="card-title">Factuurregels</h4>
				</div>
				<div class="card-body">
					<div class="row mt-4">
						<div class="col-md-12 col-xs-12 col-lg-12">
							<table class="table table-borderless" id="tableInvoice">
								<thead>
									<tr>
										<td class="w16">Artikelnummer</td>
										<td class="w10">EAN code</td>
										<td class="w33">Artikelomschrijving</td>
										<td class="w7">Aantal</td>
										<td class="w7">BTW bedrag</td>
										<td class="w10" id="priceT">Inkoopprijs</td>
										<td class="w7" id="discountT">Korting (%)</td>
										<td>BTW</td>
										<td class="w10">Totaal</td>
										<td>&nbsp;</td>

									</tr>
								</thead>
								<tbody>
									<tr id="invoiceRow1">
										<td>
											<input name="articlenumber1" id="articlenumber" class="form-control" type="text" data-provide="typeahead" autocomplete="off" onkeyup="searchArticlenumber(this, 1)" required />
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
										<td><input name="taxamount1" class="form-control" type="number" value="0" onchange="calculateRow(1)" step="any"  /></td>
										<td><input name="purchaseprice1" class="form-control" type="number" value="0.00" onchange="calculateRow(1)" step="any" /></td>
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
							<textarea name="description" class="editortools" rows="10"></textarea>
						</div>
					</div>
				</div>
			</div>

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
	<div class="row justify-content-center">
		<div class="col-md-4">
			<button class="btn btn-block btn-success" type="submit">Opslaan en sluiten</button>
		</div>
	</div>
</form>

<script type="text/javascript">
	var calculatePurchase = 1;
	var currentRow = 2;
	var articles = {};
	var supplierId = <?= $this->uri->segment(3) ?? 0 ?>;

	$(document).ready(function () {

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

	function stopRKey(evt) {
		var evt = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type == "text")) {
			return false;
		}
	}

	document.onkeypress = stopRKey;

	function calculateRow(i) {
		var total = 0;
		var purchaseprice = 0;

		if (document.getElementsByName('taxamount' + i)[0].value > 0) {
			purchaseprice = document.getElementsByName('taxamount' + i)[0].value / document.getElementsByName('tax' + i)[0].value * 100;
			document.getElementsByName('purchaseprice' + i)[0].value = purchaseprice.toFixed(2);
		}
		if (document.getElementsByName('amount' + i)[0].value > 0 || document.getElementsByName('purchaseprice' + i)[0].value > 0) {
			total = parseFloat(document.getElementsByName('amount' + i)[0].value * document.getElementsByName('purchaseprice' + i)[0].value) * (1 - (document.getElementsByName('discount' + i)[0].value / 100));
			document.getElementsByName('total' + i)[0].value = total.toFixed(2);
		}
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
		myRow += '<td><div class="form-group"><input name="taxamount' + currentRow + '" class="form-control" type="number" value="0" onchange="calculateRow(' + currentRow + ')" step="any" /></div></td>';
		myRow += '<td><div class="form-group"><input name="purchaseprice' + currentRow + '" class="form-control" type="number" value="0.00" onchange="calculateRow(' + currentRow + ')" step="any" /></div></td>';
		myRow += '<td><div class="form-group"><input name="discount' + currentRow + '" class="form-control" type="number" value="0" onchange="calculateRow(' + currentRow + ')" /></div></td>';
		myRow += '<td><div class="form-group"><select name="tax' + currentRow + '" class="form-control" onchange="calculateRow(' + currentRow + ')" >';
		myRow += '<option value="21">21%</option>';
		myRow += '<option value="9">9%</option>';
		myRow += '<option value="0">0%</option>';
		myRow += '</select></div></td>';
		myRow += '<td><div class="form-group"><input name="total' + currentRow + '" class="form-control" type="number" value="0.00" readonly /> <input id="number" name="number[]" type="hidden" readonly value="' + currentRow + '" /></div></td>';
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
	
	function searchArticlenumber(element, row){
		var value = $(element).val().trim();

		$.ajax({
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/ArticleNumber/1/all/'+supplierId
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
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/EanCode/1/all/'+supplierId
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
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/Description/1/all/'+supplierId
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
		calculateRow(row);
	}

</script>

<?php include 'application/views/inc/footer.php'; ?>
