<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Periodieke facturen');
define('CUSTOMER', $repeatingInvoice->CustomerId);
define('SUBTITEL', 'Periodieke facturen: ' . getCustomerName($repeatingInvoice->CustomerId) . ' (' . $repeatingInvoice->CustomerId . ')');
define('PAGE', 'customer');

include 'application/views/inc/header.php';

$i = 1;

?>

<form method="post" action="<?= base_url(); ?>customers/updateRepeatingInvoice/<?= $this->uri->segment(3); ?>">
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
						<div class="col-lg-3 col-md-6 input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-calendar"></i>
								</span>
							</div>
							<div class="form-group label-floating">
								<label class="control-label">Factuurdatum <small>*</small></label>
								<input class="form-control date-picker" type="text" name="invoicedate" value="<?= date('d-m-Y', $repeatingInvoice->InvoiceDate); ?>" required />
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
							<select class="form-control" name="paymentcondition">
								<?php foreach ($paymentConditions as $paymentCondition): ?>
									<option value="<?= $paymentCondition->Name ?>" <?= $paymentCondition->Name == $repeatingInvoice->PaymentCondition ? 'selected' : NULL ?>><?= $paymentCondition->Name ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="col-lg-3 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Termijn (dagen) <small>*</small></label>
							<input value="<?= $repeatingInvoice->TermOfPayment; ?>" name="termofpayment" class="form-control" type="number" required min="1"/>
						</div>

						<div class="col-lg-3 col-md-6 col-sm-12 form-group">
							<a href="<?= base_url(); ?>customers/createInvoiceRepeating/<?= $this->uri->segment(3); ?>" class="btn btn-success">Maak factuur</a>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 form-group label-floating">
							<label class="control-label">Factuur beschrijving <small>*</small></label>
							<input class="form-control" name="invoicedescription" value="<?= $repeatingInvoice->InvoiceDescription; ?>" required />
						</div>

					</div>

					<table class="table table-borderless" id="tableInvoice" width="100%">
						<thead>
							<tr>
								<td class="w7">Artikelnummer</td>
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
							<?php foreach (unserialize($repeatingInvoice->InvoiceRules) as $invoiceRule) { ?>
								<tr id="invoiceRow<?= $i; ?>">
									<td>
										<input name="articlenumber<?= $i; ?>" id="articlenumber" class="form-control" type="text" data-provide="typeahead" autocomplete="off" value="<?= $invoiceRule->ArticleNumber; ?>" onkeyup="searchArticlenumber(this, <?= $i ?>)" <?= ($i == 1) ? 'required' : NULL ?> />
										<ul class="list-group suggestions-listgroup clickable"></ul>
									</td>
									<td>
										<div id="isNotDomain<?= $i; ?>" class="<?= isset($invoiceRule->Domain) && $invoiceRule->Domain != 0 ? 'hidden' : null ?>">
											<input id="articledescription<?= $i; ?>" name="articledescription<?= $i; ?>" class="form-control" type="text" autocomplete="off" value="<?= $invoiceRule->ArticleDescription; ?>" onkeyup="searchDescription(this, <?= $i ?>)" <?= isset($invoiceRule->Domain) && $invoiceRule->Domain != 0 ? 'disabled' : null ?> />
											<ul class="list-group suggestions-listgroup clickable"></ul>
										</div>
										<div id="isDomain1" class="<?= !isset($invoiceRule->Domain) || $invoiceRule->Domain == 0 ? 'hidden' : null ?>">
											<select class="form-control" name="domain<?= $i; ?>" <?= !isset($invoiceRule->Domain) || $invoiceRule->Domain == 0 ? 'disabled' : null ?>>
												<option <?= !isset($invoiceRule->Domain) || $invoiceRule->Domain == 0 ? 'selected' : null ?>>- Selecteer een optie -</option>
												<?php foreach ($domains as $domain):
													if (getLatestInvoiceDate($domain->Id) !== false && (isset($invoiceRule->Domain) && $domain->Id != $invoiceRule->Domain)) {
														continue;
													}
												?>
													<option value="<?= $domain->Id ?>" <?= isset($invoiceRule->Domain) && $domain->Id == $invoiceRule->Domain ? 'selected' : null ?>><?= $domain->Name ?></option>
												<?php endforeach; ?>
											</select>
											<input type="hidden" name="articledescription<?= $i; ?>" id="articledescription_domain<?= $i; ?>" value="<?= $invoiceRule->ArticleDescription ?>" <?= !isset($invoiceRule->Domain) || $invoiceRule->Domain == 0 ? 'disabled' : null ?>>
										</div>
									</td>
									<td><input name="amount<?= $i; ?>" class="form-control" type="number" onchange="calculateRow(<?= $i; ?>)" step="any"  value="<?= $invoiceRule->Amount; ?>" /></td>
									<td><input name="salesprice<?= $i; ?>" class="form-control" type="number" onchange="calculateRow(<?= $i; ?>)" step="any" value="<?= number_format($invoiceRule->SalesPrice ? $invoiceRule->SalesPrice : 0, 2, '.', ''); ?>" /></td>
									<td><input name="discount<?= $i; ?>" class="form-control" type="number" onchange="calculateRow(<?= $i; ?>)"  value="<?= number_format($invoiceRule->Discount ? $invoiceRule->Discount : 0, 2, '.', ''); ?>" /></td>
									<td><?= form_dropdown('tax' . $i, unserialize(TAXDROPDOWN), $invoiceRule->Tax, CLASSDROPDOWN . 'onchange="calculateRow(' . $i . ')"'); ?></td>
									<td><input name="total<?= $i; ?>" class="form-control" type="number" value="<?= number_format($invoiceRule->Total ? $invoiceRule->Total : 0, 2, '.', ''); ?>" readonly /> <input name="number[]" type="hidden" readonly value="<?= $i; ?>" /></td>
									<td class="td-actions text-right">
										<button type="button" onclick="removeRow(<?= $i; ?>)" class="btn btn-danger btn-round btn-fab btn-fab-mini"><i class="material-icons">close</i></button>
									</td>
								</tr>
								<?php
								$i++;
							} ?>
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
		<div class="col-md-4">
			<a href="<?= base_url(); ?>customers/deleteRepeatingInvoice/<?= $repeatingInvoice->Id; ?>" class="btn btn-danger btn-block">Verwijderen</a>
		</div>
	</div>
</form>

<script type="text/javascript">
	var calculatePurchase = 0;
	var currentRow = <?= $i ?>;
	var articles = {};

	$(document).ready(function () {
		
		$('.date-picker').datetimepicker({
			locale: 'nl',
			format: 'L',
			useCurrent: false,
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

	function removeRow(i) {
		$('#invoiceRow' + i).remove();
	}

	function addRow() {
		var myRow = '<tr id="invoiceRow' + currentRow + '">';
		myRow += '<td><div class="form-group"><input name="articlenumber' + currentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumber(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td><div id="isNotDomain'+currentRow+'"><div class="form-group"><input name="articledescription' + currentRow + '" id="articledescription'+currentRow+'" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, ' + currentRow + ')" /></div><ul class="list-group suggestions-listgroup clickable"></ul></div><div id="isDomain'+currentRow+'" class="hidden"><div class="form-group"><select class="form-control" name="domain'+currentRow+'"><option>- Selecteer een optie -</option>';
		<?php foreach ($domains as $domain):
			if (getLatestInvoiceDate($domain->Id) !== false && (isset($invoiceRule->Domain) && $domain->Id != $invoiceRule->Domain)) {
				continue;
			}
		?>
			myRow += '<option value="<?= $domain->Id ?>"><?= $domain->Name ?></option>';
		<?php endforeach; ?>
		myRow += '</select></div><input type="hidden" name="articledescription'+currentRow+'" id="articledescription_domain'+currentRow+'" disabled></div></td>';
		myRow += '<td><div class="form-group"><input name="amount' + currentRow + '" class="form-control" type="number" value="0" onchange="calculateRow(' + currentRow + ')" step="any" /></td>';
		myRow += '<td><div class="form-group"><input name="salesprice' + currentRow + '" class="form-control" type="number" value="0.00" onchange="calculateRow(' + currentRow + ')" step="any" /></div></td>';
		myRow += '<td><div class="form-group"><input name="discount' + currentRow + '" class="form-control" type="number" value="0.00" onchange="calculateRow(' + currentRow + ')" /></div></td>';
		myRow += '<td><div class="form-group"><select name="tax' + currentRow + '" class="form-control"onchange="calculateRow(' + currentRow + ')" >';
		myRow += '<option value="21">21%</option>';
		myRow += '<option value="9">9%</option>';
		myRow += '<option value="0">0%</option>';
		myRow += '</select></div></td>';
		myRow += '<td><div class="form-group"><input name="total' + currentRow + '" class="form-control" type="number" value="0.00" readonly /> <input name="number[]" type="hidden" readonly value="' + currentRow + '" /></div></td>';
		myRow += '<td class="td-actions text-center"><button type="button" onclick="removeRow(' + currentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i></button></td>';
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
		setEanCode(row, articles['products'][index]['EanCode']);
		setArticledescription(row, articles['products'][index]['Description']);
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
		if (product['ProductKind'] == 3) { // Product is domain
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
