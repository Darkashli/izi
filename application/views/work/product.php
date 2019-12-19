<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Geleverde producten');
define('CUSTOMER', $customer->Id);
define('SUBTITEL', 'Ticket: ' . getCustomerName($customer->Id) . ' (' . $customer->Id . ')');
define('PAGE', 'work');

define('SUBMENUPAGE', 'product');
define('SUBMENU', $this->load->view('work/tab', array(), true));

$row = 1;

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success float-right" href="<?= base_url(); ?>customers/work/<?= $customer->Id; ?>">Alle tickets</a>
	</div>
</div>

<form method="post">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">local_offer</i>
					</div>
					<h4 class="card-title">Geleverde producten</h4>
				</div>
				<div class="card-body">
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
								<?php if ($ticketProducts != null): ?>
									<?php foreach ($ticketProducts as $ticketProduct): ?>
										<tr id="invoiceRow<?= $row; ?>">
											<td>
												<input name="articlenumber<?= $row; ?>" id="articlenumber" class="form-control" type="text" data-provide="typeahead" autocomplete="off" value="<?= $ticketProduct->ArticleC; ?>" onkeyup="searchArticlenumber(this, <?= $row; ?>)" <?= ($row == 1) ? 'required' : NULL ?> />
												<ul class="list-group suggestions-listgroup clickable"></ul>
											</td>
											<td>
												<input name="ean_code<?= $row ?>" id="ean_code" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCode(this, <?= $row ?>)" value="<?= $ticketProduct->EanCode; ?>" />
												<ul class="list-group suggestions-listgroup clickable"></ul>
											</td>
											<td>
												<input name="articledescription<?= $row; ?>" class="form-control" type="text" autocomplete="off" value="<?= $ticketProduct->Description; ?>" onkeyup="searchDescription(this, <?= $row; ?>)" />
												<ul class="list-group suggestions-listgroup clickable"></ul>
											</td>
											<td><input name="amount<?= $row; ?>" class="form-control" type="number" onchange="calculateRow(<?= $row; ?>)" step="any" value="<?= $ticketProduct->Amount; ?>" /></td>
											<td><input name="salesprice<?= $row; ?>" class="form-control" type="number" onchange="calculateRow(<?= $row; ?>)" step="any" value="<?= $ticketProduct->Price; ?>" /></td>
											<td><input name="discount<?= $row; ?>" class="form-control" type="number" onchange="calculateRow(<?= $row; ?>)" value="<?= $ticketProduct->Discount; ?>"/></td>
											<td>
												<div class="form-group">
													<select name="tax<?= $row; ?>" class="form-control" onchange="calculateRow(<?= $row; ?>)">
														<option value="21">21%</option>
														<option value="9">9%</option>
														<option value="0">0%</option>
													</select>
												</div>
											</td>
											<td><input name="total<?= $row; ?>" class="form-control" type="number" value="<?= $ticketProduct->Total; ?>" readonly /> <input name="number[]" type="hidden" readonly value="<?= $row; ?>" /></td>
											<td class="td-actions text-center">
												<?php if ($row == 1) { ?>
													<button type="button" onclick="addRow()" class="btn btn-success btn-round btn-fab btn-fab-mini"><i class="material-icons">add</i></button>
												<?php }else{ ?>
													<button type="button" onclick="removeRow(<?= $row; ?>)" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i></button>
												<?php } ?>
											</td>
										</tr>
										<?php
										$row++;
									endforeach;
									?>
								<?php else: ?>
									<tr>
										<td>
											<input name="articlenumber<?= $row; ?>" id="articlenumber" class="form-control" type="text" data-provide="typeahead" autocomplete="off" onkeyup="searchArticlenumber(this, <?= $row; ?>)" required />
											<ul class="list-group suggestions-listgroup clickable"></ul>
										</td>
										<td>
											<input name="ean_code<?= $row ?>" id="ean_code" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCode(this, <?= $row ?>)" />
											<ul class="list-group suggestions-listgroup clickable"></ul>
										</td>
										<td>
											<input name="articledescription<?= $row; ?>" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, <?= $row; ?>)" />
											<ul class="list-group suggestions-listgroup clickable"></ul>
										</td>
										<td><input name="amount<?= $row; ?>" class="form-control" type="number" value="0" onchange="calculateRow(<?= $row; ?>)" step="any"  /></td>
										<td><input name="salesprice<?= $row; ?>" class="form-control" type="number" value="0.00" onchange="calculateRow(<?= $row; ?>)" step="any" /></td>
										<td><input name="discount<?= $row; ?>" class="form-control" type="number" value="0" onchange="calculateRow(<?= $row; ?>)" /></td>
										<td>
											<div class="form-group">
												<select name="tax<?= $row; ?>" class="form-control" onchange="calculateRow(<?= $row; ?>)">
													<option value="21">21%</option>
													<option value="9">9%</option>
													<option value="0">0%</option>
												</select>
											</div>
										</td>
										<td><input name="total<?= $row; ?>" class="form-control" type="number" value="0.00" readonly /> <input name="number[]" type="hidden" readonly value="1" /></td>
										<td class="td-actions text-center">
											<button type="button" onclick="addRow()" class="btn btn-success btn-round btn-fab btn-fab-mini"><i class="material-icons">add</i></button>
										</td>
									</tr>
									<?php $row++; ?>
								<?php endif; ?>
							</tbody>
						</table>
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

	var calculatePurchase = 0;
	var currentRow = <?= $row ?>;
	var articles = {};
	
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
			document.getElementById('discountT').innerHTML = "Korting (%)";
			document.getElementById('priceT').innerHTML = "Verkoopprijs";
			calculatePurchase = 0;
		} else {
			document.getElementById('discountT').innerHTML = "Marge (%)";
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
	}

	function removeRow(i) {
		$('#invoiceRow' + i).remove();
	}

	function addRow(i) {
		var myRow = '<tr id="invoiceRow' + currentRow + '">';
		myRow += '<td><div class="form-group"><input name="articlenumber' + currentRow + '" class="form-control" type="text" onkeyup="searchArticlenumber(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td><div class="form-group"><input name="ean_code' + currentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCode(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td><div class="form-group"><input name="articledescription' + currentRow + '" class="form-control" type="text" onkeyup="searchDescription(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td><div class="form-group"><input name="amount' + currentRow + '" class="form-control" type="number" value="0" onchange="calculateRow(' + currentRow + ')" step="any" /></div></td>';
		myRow += '<td><div class="form-group"><input name="salesprice' + currentRow + '" class="form-control" type="number" value="0.00" onchange="calculateRow(' + currentRow + ')" step="any" /></div></td>';
		myRow += '<td><div class="form-group"><input name="discount' + currentRow + '" class="form-control" type="number" value="0" onchange="calculateRow(' + currentRow + ')" /></div></td>';
		myRow += '<td><div class="form-group"><select name="tax' + currentRow + '" class="form-control" onchange="calculateRow(' + currentRow + ')" >';
		myRow += '<option value="21">21%</option>';
		myRow += '<option value="9">9%</option>';
		myRow += '<option value="0">0%</option>';
		myRow += '</select></div></td>';
		myRow += '<td><div class="form-group"><input name="total' + currentRow + '" class="form-control" type="number" value="0.00" readonly /> <input name="number[]" type="hidden" readonly value="' + currentRow + '" /></td>';
		myRow += '<td class="td-actions text-center"><button type="button" onclick="removeRow(' + currentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i></button></td>';
		myRow += '</tr>';
		$("#tableInvoice tr:last").after(myRow);
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
}

</script>

<?php include 'application/views/inc/footer.php'; ?>
