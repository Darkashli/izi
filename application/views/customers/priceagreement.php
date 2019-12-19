<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Prijsafspraken');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Prijsafspraken: ' . getCustomerName(CUSTOMER) . ' (' . CUSTOMER . ')');
define('PAGE', 'customer');
define('SUBMENUPAGE', 'priceagreement');
define('SUBMENU', $this->load->view('customers/tab', array(), true));

$row = 1;
include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
</div>

<form method="post">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">local_offer</i>
					</div>
					<h4 class="card-title">Prijsafspraken</h4>
				</div>
				<div class="card-body">
					<div class="col-md-12 col-xs-12 col-lg-12">
						<table class="table table-borderless" id="priceAgreementTable">
							<thead>
								<tr>
									<td class="w16">Artikelnummer</td>
									<td class="w10">EAN code</td>
									<td class="w40">Artikelomschrijving</td>
									<td id="priceT">Verkoopprijs</td>
									<td id="discountT">Korting (%)</td>
									<td>&nbsp;</td>
								</tr>
							</thead>
							<tbody>
								<?php if ($priceAgreements != null): ?>
									<?php foreach ($priceAgreements as $product): ?>
										<tr id="invoiceRow<?= $row; ?>">
											<td>
												<input name="articlenumber<?= $row; ?>" id="articlenumber" class="form-control" type="text" data-provide="typeahead" autocomplete="off" value="<?= $product->ArticleNumber; ?>" onkeyup="searchArticlenumber(this, <?= $row; ?>)" required />
												<ul class="list-group suggestions-listgroup clickable"></ul>
											</td>
											<td>
												<input name="ean_code1" id="ean_code" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCode(this, 1)" />
												<ul class="list-group suggestions-listgroup clickable"></ul>
											</td>
											<td>
												<input name="articledescription<?= $row; ?>" class="form-control border-0" type="text" autocomplete="off" onkeyup="searchDescription(this, <?= $row; ?>)" value="<?= $product->Description; ?>" />
												<ul class="list-group suggestions-listgroup clickable"></ul>
											</td>
											<td><input name="salesprice<?= $row; ?>" class="form-control border-0" type="number"  step="any" value="<?= $product->Price; ?>" /></td>
											<td><input name="discount<?= $row; ?>" class="form-control border-0" type="number" value="<?= $product->Discount; ?>"/> <input name="number[]" type="hidden" readonly value="<?= $row; ?>" /></td>
											<td class="td-actions text-right">
												<button type="button" onclick="removeRow(<?= $row; ?>)" class="btn btn-danger btn-round btn-fab btn-fab-mini"><i class="material-icons">close</i></button>
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
											<input name="ean_code<?= $row; ?>" id="ean_code" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCode(this, <?= $row; ?>)" />
											<ul class="list-group suggestions-listgroup clickable"></ul>
										</td>
										<td>
											<input name="articledescription<?= $row; ?>" class="form-control border-0" type="text" autocomplete="off" onkeyup="searchDescription(this, <?= $row; ?>)" />
											<ul class="list-group suggestions-listgroup clickable"></ul>
										</td>
										<td><input name="salesprice<?= $row; ?>" class="form-control border-0" type="number" value="0.00" step="any" /></td>
										<td><input name="discount<?= $row; ?>" class="form-control border-0" type="number" value="0"  /> <input name="number[]" type="hidden" readonly value="<?= $row; ?>" /></td>
										<td class="td-actions text-right">
											<button type="button" onclick="removeRow(1)" class="btn btn-danger btn-round btn-fab btn-fab-mini"><i class="material-icons">close</i></button>
										</td>
									</tr>
									<?php $row++; ?>
								<?php endif; ?>
							</tbody>
							<tfoot>
								<tr>
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

	function stopRKey(evt) {
		var evt = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type == "text")) {
			return false;
		}
	}

	document.onkeypress = stopRKey;

	function removeRow(i) {
		$('#invoiceRow' + i).remove();
	}

	function addRow(i) {
		var myRow = '<tr id="invoiceRow' + currentRow + '">';
		myRow += '<td><div class="form-group"><input name="articlenumber' + currentRow + '" id="articlenumber" class="form-control" type="text" data-provide="typeahead" autocomplete="off" onkeyup="searchArticlenumber(this, ' + currentRow + ')" required /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td><div class="form-group"><input name="ean_code' + currentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCode(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td class="border-0"><div class="form-group"><input name="articledescription' + currentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td class="border-0"><div class="form-group"><input name="salesprice' + currentRow + '" class="form-control" type="number" value="0.00" step="any" /></div></td>';
		myRow += '<td class="border-0"><div class="form-group"><input name="discount' + currentRow + '" class="form-control" type="number" value="0" /></div></td>';
		myRow += '<input name="number[]" type="hidden" readonly value="' + currentRow + '" /></td>';
		myRow += '<td class="td-actions text-right"><button type="button" onclick="removeRow(' + currentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i></button></td>';
		myRow += '</tr>';
		$("#priceAgreementTable tbody").append(myRow);
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
		// document.getElementsByName('tax' + row)[0].value = btw;
	}

</script>

<?php include 'application/views/inc/footer.php'; ?>
