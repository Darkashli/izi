<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Omzetten naar verkooporder');
define('PAGE', 'quotation');

include 'application/views/inc/header.php';
?>

<form method="post">

	<div class="row">
		<?php if ($quotation->CustomerId == 0) { ?>
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
						<div class="row">
							<div class="col-12 form-group label-floating">
								<label class="control-label">Klantnaam</label>
								<input type="text" name="company_name" class="form-control" value="<?= $quotation->CustomerName ?>" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Voornaam</label>
								<input type="text" name="front_name" class="form-control" value="<?= $quotation->ContactFirstName ?>" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Tussenvoegsel</label>
								<input type="text" name="insertion" class="form-control" value="<?= $quotation->ContactInsertion ?>">
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Achternaam</label>
								<input type="text" name="last_name" class="form-control" value="<?= $quotation->ContactLastName ?>" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Straat / Postbus</label>
								<input type="text" name="address" class="form-control" value="<?= $quotation->CustomerStreet ?>" />
							</div>
							<div class="col-md-2 form-group label-floating">
								<label class="control-label">Huisnummer</label>
								<input type="number" name="address_number" class="form-control" value="<?= $quotation->CustomerHouseNumber ?>" />
							</div>
							<div class="col-md-2 form-group label-floating">
								<label class="control-label">Toevoeging</label>
								<input type="text" name="address_addition" class="form-control" value="<?= $quotation->CustomerHouseNumberAddition ?>" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-3 form-group label-floating">
								<label class="control-label">Postcode</label>
								<input type="text" name="zip_code" class="form-control" value="<?= $quotation->CustomerZipCode ?>" />
							</div>
							<div class="col-md-5 form-group label-floating">
								<label class="control-label">Woonplaats</label>
								<input type="text" name="city" class="form-control" value="<?= $quotation->CustomerCity ?>" />
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Land</label>
								<input type="text" name="country" class="form-control" value="<?= $quotation->CustomerCountry ?>" />
							</div>
						</div>
						<div class="row">
							<div class="col-12 form-group label-floating">
								<label class="control-label">E-mailadres</label>
								<input type="text" name="mail_address" class="form-control" value="<?= $quotation->CustomerMailaddress ?>" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 form-group">
								<label>Betalingsconditie <small>*</small></label>
								<select class="form-control" name="paymentcondition">
									<?php foreach ($paymentConditions as $paymentCondition): ?>
										<option value="<?= $paymentCondition->Name ?>" <?= $paymentCondition->Id == $quotation->PaymentConditionId ? 'selected' : NULL ?>><?= $paymentCondition->Name ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6 col-sm-12 form-group label-floating">
								<label class="control-label">Termijn (dagen) <small>*</small></label>
								<input name="termofpayment" class="form-control" type="number" value="<?= $quotation->PaymentTerm ?>" required/>
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
								<input class="form-control date-picker" type="text" name="orderdate" value="<?= date('d-m-Y'); ?>" required />
							</div>
						</div>
						<div class="col-lg-3 col-md-4 form-group">
							<label>Verkoopkanaal</label>
							<select class="form-control"name="seller" id="seller" onchange="setDefaultTransporter()">
								<option></option>
								<?php foreach ($sellers as $seller) { ?>
									<option value="<?= $seller->Seller_id ?>" data-transporter="<?= $seller->Default_transport ?>" data-onlyoption="<?= $seller->Only_option ?>"><?= $seller->Name ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="col-lg-3 col-md-4 form-group">
							<label>Vervoerder</label>
							<select class="form-control" name="transport" id="transport">
								<option></option>
								<?php foreach ($transporters as $transporter) { ?>
									<option value="<?= $transporter->Transporter_id ?>"><?= $transporter->Name ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="col-lg-2 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Referentie</label>
							<input name="reference" class="form-control" type="text" autocomplete="off" />
						</div>
						
						<div class="col-lg-2 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Aantal colli</label>
							<input name="colli" class="form-control" type="text" autocomplete="off" />
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
									foreach ($quotationRules as $quotationRule) {
										?>
										<tr id="orderRow<?= $i; ?>">
											<td class="align-top">
												<input name="articlenumber<?= $i; ?>" id="articlenumber" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumber(this, <?= $i ?>)" value="<?= $quotationRule->ArticleC; ?>" required />
												<ul class="list-group suggestions-listgroup clickable"></ul>
											</td>
											<td class="align-top">
												<input name="ean_code<?= $i ?>" id="ean_code" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCode(this, <?= $i ?>)" value="<?= $quotationRule->EanCode; ?>" />
												<ul class="list-group suggestions-listgroup clickable"></ul>
											</td>
											<td class="align-top">
												<input name="articledescription<?= $i; ?>" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, <?= $i ?>)" value="<?= $quotationRule->Description; ?>" />
												<ul class="list-group suggestions-listgroup clickable"></ul>
												<?php if ($quotationRule->MetaData != null):
													$metaDatas = unserialize($quotationRule->MetaData);
													$countMetaData = ( count($metaDatas) -1 );
													foreach ($metaDatas as $key => $metaData): ?>
														<?= $metaData['value'] . ( $key < $countMetaData ? ',' : null ) ?>
													<?php endforeach; ?>
													<input type="hidden" name="meta_data<?= $i ?>" value="<?= html_escape($quotationRule->MetaData) ?>">
												<?php endif; ?>
											</td>
											<td class="align-top"><input name="amount<?= $i; ?>" class="form-control" type="number" value="<?= $quotationRule->Amount; ?>" onchange="calculateRow(<?= $i; ?>)" step="any"  /></td>
											<td class="align-top"><input name="salesprice<?= $i; ?>" class="form-control" type="number" value="<?= $quotationRule->Price; ?>" onchange="calculateRow(<?= $i; ?>)" step="any" /></td>
											<td class="align-top"><input name="discount<?= $i; ?>" class="form-control" type="number" value="<?= $quotationRule->Discount; ?>" onchange="calculateRow(<?= $i; ?>)" /></td>
											<td class="align-top">
												<div class="form-group">
													<select name="tax<?= $i; ?>" class="form-control" onchange="calculateRow(<?= $i; ?>)">
														<option <?= $quotationRule->Tax == 21 ? 'selected' : ''; ?> value="21">21%</option>
														<option <?= $quotationRule->Tax == 9 ? 'selected' : ''; ?> value="9">9%</option>
														<option <?= $quotationRule->Tax == 0 ? 'selected' : ''; ?> value="0">0%</option>
													</select>
												</div>
											</td>
											<td class="align-top"><input name="total<?= $i; ?>" class="form-control" type="number" value="0" readonly /> <input id="number" name="number[]" type="hidden" readonly value="<?= $i; ?>" /></td>
											<td class="align-middle td-actions text-right">
												<button type="button" onclick="removeRow(<?= $i ?>)" class="btn btn-danger btn-round btn-fab btn-fab-mini">
													<i class="material-icons">close</i>
												</button>
											</td>
										</tr>
										<?php
										$i++;
									}
									?>
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
						<i class="material-icons">chat</i>
					</div>
					<h4 class="card-title">Opmerking</h4>
				</div>
				<div class="card-body">
					<div class="row mt-2">
						<div class="col-12">
							<textarea name="note" class="editortools" rows="10"><?= $quotation->ProductDescription ?></textarea>
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
	<div class="row justify-content-center">
		<div class="col-md-4">
			<button class="btn btn-block btn-success" type="submit">Opslaan</button>
		</div>
	</div>

</form>

<script type="text/javascript">
	var calculatePurchase = 0;
	var currentRow = <?= $i ?>;
	var articles = {};
	var customerId = <?= $quotation->CustomerId != 0 ? $quotation->CustomerId : 'null' ?>;

	$(document).ready(function () {
		
		$('.date-picker').datetimepicker({
			locale: 'nl-NL',
			format: 'L',
			icons: datetimepickerIcons
		});
		
		for (var i = 1; i < currentRow; i++) {
			calculateRow(i);
		}
		
		calculateSubTotal();
		
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
		$('#orderRow' + i).remove();
	}

	function addRow() {
		var myRow = '<tr id="orderRow' + currentRow + '">';
		myRow += '<td class="align-top"><div class="form-group"><input name="articlenumber' + currentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumber(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="ean_code' + currentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCode(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="articledescription' + currentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="amount' + currentRow + '" class="form-control" type="number" value="0" onchange="calculateRow(' + currentRow + ')" step="any" /></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="salesprice' + currentRow + '" class="form-control" type="number" value="0.00" onchange="calculateRow(' + currentRow + ')" step="any" /></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="discount' + currentRow + '" class="form-control" type="number" value="0" onchange="calculateRow(' + currentRow + ')" /></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><select name="tax' + currentRow + '" class="form-control" onchange="calculateRow(' + currentRow + ')" ></div>';
		myRow += '<option value="21">21%</option>';
		myRow += '<option value="9">9%</option>';
		myRow += '<option value="0">0%</option>';
		myRow += '</select></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="total' + currentRow + '" class="form-control" type="number" value="0.00" readonly /> <input id="number" name="number[]" type="hidden" readonly value="' + currentRow + '" /></td>';
		myRow += '<td class=" align-middle td-actions text-right"><button type="button" onclick="removeRow(' + currentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i><div class="ripple-container"></div></button></td>';
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
		$('[name="articlenumber'+row+'"]').val(articles['products'][index]['ArticleNumber']);
		$('[name="ean_code'+row+'"]').val(articles['products'][index]['EanCode']);
		$('[name="articledescription'+row+'"]').val(articles['products'][index]['Description']);
		$('[name="salesprice'+row+'"]').val(articles['products'][index]['SalesPrice']);
		$('[name="tax'+row+'"]').val(articles['products'][index]['BTW']);
		calculateRow(row);
		
		// Get priceagreements.
		$.ajax({
			url: '<?= site_url() ?>/customers/searchPriceagreement/'+customerId+'/'+articles['products'][index]['ArticleNumber']
		}).done(function(msg){
			dataP = JSON.parse(msg);
			if (dataP['error']) {
				console.log(dataP['error']);
			}
			else if (dataP['priceagreement'] != null) {
				$('[name="salesprice'+row+'"]').val(dataP['priceagreement']['Price']);
				$('[name="discount'+row+'"]').val(dataP['priceagreement']['Discount']);
				calculateRow(row);
			}
		});
	}

</script>

<?php include 'application/views/inc/footer.php'; ?>
