<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Periodieke facturen');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Periodieke facturen: ' . getCustomerName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
define('PAGE', 'customer');

include 'application/views/inc/header.php';
?>
<form method="post" action="<?= base_url(); ?>customers/createRepeatingInvoice/<?= $this->uri->segment(3); ?>">
	<div class="row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">local_offer</i>
					</div>
					<h4 class="card-title">Facturen</h4>
				</div>
				<div class="card-body">

					<div class="row">
						<div class="col-lg-3 col-md-6 col-sm-12 input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-calendar"></i>
								</span>
							</div>
							<div class="form-group label-floating">
								<label class="control-label">Factuurdatum <small>*</small></label>
								<input class="form-control date-picker" type="text" name="invoicedate" value="<?= date('d-m-Y'); ?>" required />
							</div>
						</div>

						<div class="col-lg-3 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Periode</label>
							<?= $period; ?>
						</div>

						<div class="col-lg-3 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">T.A.V.</label>
							<?= $contact; ?>
						</div>

					</div>

					<div class="row">
						<div class="col-lg-3 col-md-6 col-sm-12 form-group">
							<label>Betalingsconditie</label>
							<select class="form-control"name="paymentcondition">
								<?php foreach ($paymentConditions as $paymentCondition): ?>
									<option value="<?= $paymentCondition->Name ?>"><?= $paymentCondition->Name ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="col-lg-3 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Termijn (dagen) <small>*</small></label>
							<input name="termofpayment" class="form-control" type="number" required min="1"/>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 form-group label-floating">
							<label class="control-label">Factuur beschrijving</label>
							<input class="form-control" name="invoicedescription" required />
						</div>

					</div>

					<table class="table table-borderless" id="tableInvoice" width="100%">
						<thead>
							<tr>
								<td class="w16">Artikelnummer</td>
								<td class="w30">Artikelomschrijving</td>
								<td>Aantal</td>
								<td id="priceT">Verkoopprijs</td>
								<td id="discountT">Korting (%)</td>
								<td class="w10">BTW</td>
								<td>Totaal</td>
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
									<div id="isNotDomain1">
										<input id="articledescription1" name="articledescription1" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, 1)" />
										<ul class="list-group suggestions-listgroup clickable"></ul>
									</div>
									<div id="isDomain1" class="hidden">
										<select class="form-control" name="domain1">
											<option>- Selecteer een optie -</option>
											<?php foreach ($domains as $domain):
												if (getLatestInvoiceDate($domain->Id) !== false) {
													continue;
												}
											?>
												<option value="<?= $domain->Id ?>"><?= $domain->Name ?></option>
											<?php endforeach; ?>
										</select>
										<input type="hidden" name="articledescription1" id="articledescription_domain1" disabled>
									</div>
								</td>
								<td><input name="amount1" class="form-control" type="number" value="0" onchange="calculateRow(1)" step="any"  /></td>
								<td><input name="salesprice1" class="form-control" type="number" value="0.00" onchange="calculateRow(1)" step="any" /></td>
								<td><input name="discount1" class="form-control" type="number" value="0" onchange="calculateRow(1)" /></td>
								<td><?= form_dropdown('tax1', unserialize(TAXDROPDOWN), '', CLASSDROPDOWN . 'onchange="calculateRow(1)"'); ?></td>
								<td><input name="total1" class="form-control" type="number" value="0.00" readonly /> <input name="number[]" type="hidden" readonly value="1" /></td>
								<td class="td-actions text-right">
									<button type="button" onclick="removeRow(1)" class="btn btn-danger btn-round btn-fab btn-fab-mini"><i class="material-icons">close</i></button>
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
								<td class="td-actions text-right">
									<button type="button" onclick="addRow()" class="btn btn-success btn-round btn-fab btn-fab-mini"><i class="material-icons">add</i></button>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-md-4">
			<button type="submit" class="btn btn-success btn-block">Opslaan en sluiten</button>
		</div>
	</div>
</form>

<script type="text/javascript">
	var calculatePurchase = 0;
	var currentRow = 2;
	var articles = {};

	$(document).ready(function () {
		
		$('.date-picker').datetimepicker({
			locale: 'nl',
			format: 'L',
			useCurrent: false,
			icons: datetimepickerIcons
		});
		
	});
	
	function calculateRow(i) {
		total = 0;
		
		
		if (document.getElementsByName('amount' + i)[0].value > 0) {
			total = (document.getElementsByName('amount' + i)[0].value * document.getElementsByName('salesprice' + i)[0].value) * (1 - (document.getElementsByName('discount' + i)[0].value / 100));
		}
		if (document.getElementsByName('salesprice' + i)[0].value > 0) {
			total = parseFloat((document.getElementsByName('amount' + i)[0].value * document.getElementsByName('salesprice' + i)[0].value) * (1 - (document.getElementsByName('discount' + i)[0].value / 100)));
		}

		document.getElementsByName('total' + i)[0].value = total.toFixed(2);
	}
	
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

	function removeRow(i) {
		$('#invoiceRow' + i).remove();
	}

	function addRow() {
		var myRow = '<tr id="invoiceRow' + currentRow + '">';
		myRow += '<td><div class="form-group"><input name="articlenumber' + currentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumber(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td><div id="isNotDomain'+currentRow+'"><div class="form-group"><input name="articledescription' + currentRow + '" id="articledescription'+currentRow+'" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, ' + currentRow + ')" /></div><ul class="list-group suggestions-listgroup clickable"></ul></div><div id="isDomain'+currentRow+'" class="hidden"><div class="form-group"><select class="form-control" name="domain'+currentRow+'"><option>- Selecteer een optie -</option>';
		<?php foreach ($domains as $domain):
			if (getLatestInvoiceDate($domain->Id) !== false) {
				continue;
			}
		?>
			myRow += '<option value="<?= $domain->Id ?>"><?= $domain->Name ?></option>';
		<?php endforeach; ?>
		myRow += '</select></div><input type="hidden" name="articledescription'+currentRow+'" id="articledescription_domain'+currentRow+'" disabled></div></td>';
		myRow += '<td><div class="form-group"><input name="amount' + currentRow + '" class="form-control" type="number" value="0" onchange="calculateRow(' + currentRow + ')" step="any" /></div></td>';
		myRow += '<td><div class="form-group"><input name="salesprice' + currentRow + '" class="form-control" type="number" value="0.00" onchange="calculateRow(' + currentRow + ')" step="any" /></div></td>';
		myRow += '<td><div class="form-group"><input name="discount' + currentRow + '" class="form-control" type="number" value="0" onchange="calculateRow(' + currentRow + ')" /></div></td>';
		myRow += '<td><div class="form-group"><select name="tax' + currentRow + '" class="form-control" onchange="calculateRow(' + currentRow + ')" >';
		myRow += '<option value="21">21%</option>';
		myRow += '<option value="9">9%</option>';
		myRow += '<option value="0">0%</option>';
		myRow += '</select></div></td>';
		myRow += '<td><div class="form-group"><input name="total' + currentRow + '" class="form-control" type="number" value="0.00" readonly /> <input name="number[]" type="hidden" readonly value="' + currentRow + '" /></div></td>';
		myRow += '<td class="td-actions text-right"><button type="button" onclick="removeRow(' + currentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i></button></td>';
		myRow += '</tr>';
		$("#tableInvoice tbody").append(myRow);
		currentRow++;
	}
	
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
		setArticledescription(row, articles['products'][index]['Description']);
		setEanCode(row, articles['products'][index]['EanCode']);
		setSalesprice(row, articles['products'][index]['SalesPrice']);
		setBtw(row, articles['products'][index]['BTW']);
		calculateRow(row);
		detectDomain(row, articles['products'][index]);
	}

	function setArticlenumber(row, articlenumber) {
		$('input[name="articlenumber'+row+'"]').val(articlenumber);
	}

	function setEanCode(row, eanCode) {
		// $('input[name="ean_code'+row+'"]').val(eanCode);
	}

	function setArticledescription(row, articledescription) {
		$('input[name="articledescription'+row+'"]').val(articledescription);
	}

	function setSalesprice(row, salesprice) {
		$('input[name="salesprice'+row+'"]').val(salesprice);
	}

	function setBtw(row, btw) {
		$('input[name="tax'+row+'"]').val(btw);
		calculateRow(row);
	}

	function detectDomain(row, product) {
		if (product['ProductKind'] == 3) {
			$("#isDomain"+row).show();
			$("#isNotDomain"+row).hide();
			$('[name="domain'+row+'"]').prop("disabled", false);
			$("#articledescription1"+row).prop("disabled", true);
			$("#articledescription_domain"+row).prop("disabled", false);
		}
		else {
			$("#isDomain"+row).hide();
			$("#isNotDomain"+row).show();
			$('[name="domain'+row+'"] option:not([value])').prop("selected", true);
			$('[name="domain'+row+'"]').prop("disabled", true);
			$("#articledescription1"+row).prop("disabled", false);
			$("#articledescription_domain"+row).prop("disabled", true);
		}
	}
	
</script>

<?php include 'application/views/inc/footer.php'; ?>
