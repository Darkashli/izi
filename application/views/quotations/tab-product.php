<div class="float-right togglebutton">
	<label>
		<input type="checkbox" name="comparison" <?= $quotation->IsComparison == 0 ? null : 'checked' ?>>
		Offerte is vergelijking
	</label>
</div>
<table class="table table-borderless" id="tableProducts">
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
		<?php if (empty($quotationRulesP)){ ?>
			<tr id="productRow1">
				<td class="align-top">
					<input name="product_articlenumber1" id="product_articlenumber" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumber(this, 1)"/>
					<ul class="list-group suggestions-listgroup clickable"></ul>
				</td>
				<td class="align-top">
					<input name="product_ean_code1" id="ean_code" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCode(this, 1)" />
					<ul class="list-group suggestions-listgroup clickable"></ul>
				</td>
				<td class="align-top">
					<input name="product_articledescription1" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, 1)" />
					<ul class="list-group suggestions-listgroup clickable"></ul>
				</td>
				<td class="align-top">
					<input name="product_amount1" class="form-control" type="number" value="0" onchange="calculateRow(1)" step="any"  />
				</td>
				<td class="align-top">
					<input name="product_salesprice1" class="form-control" type="number" value="0.00" onchange="calculateRow(1)" step="any" />
				</td>
				<td class="align-top">
					<input name="product_discount1" class="form-control" type="number" value="0.00" onchange="calculateRow(1)" />
				</td>
				<td class="align-top">
					<div class="form-group">
						<select name="product_tax1" class="form-control" onchange="calculateRow(1)">
							<option value="21">21%</option>
							<option value="9">9%</option>
							<option value="0">0%</option>
						</select>
					</div>
				</td>
				<td class="align-top">
					<input name="product_total1" class="form-control" type="number" value="0.00" readonly />
					<input id="product_number" name="product_number[]" type="hidden" readonly value="1" />
				</td>
				<td class="td-actions text-center">
					<button type="button" rel="tooltip" onclick="removeRow(1)" class="btn btn-danger btn-round btn-fab btn-fab-mini">
						<i class="material-icons">close</i>
					</button>
				</td>
			</tr>
		<?php $i = 2; }else{ $i = 1;
			foreach ($quotationRulesP as $quotationRuleP){ ?>
				<tr id="productRow<?= $i ?>">
					<td class="align-top">
						<input name="product_articlenumber<?= $i ?>" id="product_articlenumber" class="form-control" type="text" value="<?= $quotationRuleP->ArticleC ?>" autocomplete="off" onkeyup="searchArticlenumber(this, <?= $i ?>)"/>
						<ul class="list-group suggestions-listgroup clickable"></ul>
					</td>
					<td class="align-top">
						<input name="product_ean_code<?= $i ?>" id="ean_code" class="form-control" type="text" value="<?= $quotationRuleP->EanCode ?>" autocomplete="off" onkeyup="searchEanCode(this, <?= $i ?>)" />
						<ul class="list-group suggestions-listgroup clickable"></ul>
					</td>
					<td class="align-top">
						<input name="product_articledescription<?= $i ?>" class="form-control" type="text" autocomplete="off" value="<?= $quotationRuleP->ArticleDescription ?>" onkeyup="searchDescription(this, <?= $i ?>)" />
						<ul class="list-group suggestions-listgroup clickable"></ul>
						<?php if ($quotationRuleP->MetaData != null):
							$metaDatas = unserialize($quotationRuleP->MetaData);
							$countMetaData = ( count($metaDatas) -1 );
							foreach ($metaDatas as $key => $metaData): ?>
								<?= $metaData['value'] . ( $key < $countMetaData ? ',' : null ) ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</td>
					<td class="align-top">
						<input name="product_amount<?= $i ?>" class="form-control" type="number" value="<?= $quotationRuleP->Amount ?>" onchange="calculateRow(<?= $i ?>)" step="any"  />
					</td>
					<td class="align-top">
						<input name="product_salesprice<?= $i ?>" class="form-control" type="number" value="<?= $quotationRuleP->SalesPrice ?>" onchange="calculateRow(<?= $i ?>)" step="any" />
					</td>
					<td class="align-top">
						<input name="product_discount<?= $i; ?>" class="form-control" type="number" value="<?= $quotationRuleP->Discount; ?>" onchange="calculateRow(<?= $i; ?>)" />
					</td>
					<td class="align-top">
						<div class="form-group">
							<select name="product_tax<?= $i ?>" class="form-control" onchange="calculateRow(<?= $i ?>)">
								<option value="21" <?= $quotationRuleP->Tax == 21 ? 'selected' : null ?>>21%</option>
								<option value="9" <?= $quotationRuleP->Tax == 9 ? 'selected' : null ?>>9%</option>
								<option value="0" <?= $quotationRuleP->Tax == 0 ? 'selected' : null ?>>0%</option>
							</select>
						</div>
					</td>
					<td class="align-top">
						<input name="product_total<?= $i ?>" class="form-control" type="number" value="<?= $quotationRuleP->Amount * $quotationRuleP->SalesPrice ?>" readonly />
						<input id="product_number" name="product_number[]" type="hidden" readonly value="<?= $i ?>" />
					</td>
					<td class="td-actions text-center">
						<button type="button" rel="tooltip" onclick="removeRow(<?= $i ?>)" class="btn btn-danger btn-round btn-fab btn-fab-mini">
							<i class="material-icons">close</i>
						</button>
						<input type="hidden" name="product_ruleNumber<?= $i ?>" value="<?= $quotationRuleP->Id ?>">
					</td>
				</tr>
			<?php $i++; } ?>
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
<label for="product_description">Opmerking</label>
<textarea name="product_description" id="product_description" class="editortools" rows="10"><?= $quotation->ProductDescription ?></textarea>

<script type="text/javascript">
	var currentRow = <?= $i ?>;
	var articles = {};
	
	$(document).keydown(function(e){
		if (e.which == 107) { // "+" key.
			if ($(".table input").is(':focus')) {
				e.preventDefault();
				$('.suggestions-listgroup').html('');
				addRow();
				$('input[name="product_articlenumber'+(currentRow - 1)+'"]').focus();
			}
		}
 	});

	function stopRKey(evt){
		var evt = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type == "text")) {
			return false;
		}
	}

	document.onkeypress = stopRKey;
	
	function calculateRow(i){
		total = 0;
			if (document.getElementsByName('product_amount' + i)[0].value > 0) {
				total = (document.getElementsByName('product_amount' + i)[0].value * (document.getElementsByName('product_salesprice' + i)[0].value) * (1 - (document.getElementsByName('product_discount' + i)[0].value) / 100));
			}
			if (document.getElementsByName('product_salesprice' + i)[0].value > 0) {
				total = parseFloat((document.getElementsByName('product_amount' + i)[0].value * document.getElementsByName('product_salesprice' + i)[0].value) * (1 - (document.getElementsByName('product_discount' + i)[0].value / 100)));
			}
		document.getElementsByName('product_total' + i)[0].value = total.toFixed(2);

	}
	
	function removeRow(i){
		$('#productRow' + i).remove();
	}
	
	function addRow(){

		var next = currentRow + 1;
		var myRow = '<tr id="productRow' + currentRow + '">';
		myRow += '<td class="align-top"><div class="form-group"><input name="product_articlenumber' + currentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumber(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="product_ean_code' + currentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCode(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="product_articledescription' + currentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, ' + currentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="product_amount' + currentRow + '" class="form-control" type="number" value="0" onchange="calculateRow(' + currentRow + ')" step="any" /></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="product_salesprice' + currentRow + '" class="form-control" type="number" value="0.00" onchange="calculateRow(' + currentRow + ')" step="any" /></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="product_discount' + currentRow + '" class="form-control" type="number" value="0" onchange="calculateRow(' + currentRow + ')" /></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><select name="product_tax' + currentRow + '" class="form-control" onchange="calculateRow(' + currentRow + ')" ></div>';
		myRow += '<option value="21">21%</option>';
		myRow += '<option value="9">9%</option>';
		myRow += '<option value="0">0%</option>';
		myRow += '</select></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="product_total' + currentRow + '" class="form-control" type="number" value="0.00" readonly /> <input id="number" name="product_number[]" type="hidden" readonly value="' + currentRow + '" /></td>';
		myRow += '<td class="td-actions text-center"><button type="button" onclick="removeRow(' + currentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i><div class="ripple-container"></div></button></td>';
		myRow += '</tr>';
		$("#tableProducts tbody").append(myRow);
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
		$('[name="product_articlenumber'+row+'"]').val(articles['products'][index]['ArticleNumber']);
		$('[name="product_ean_code'+row+'"]').val(articles['products'][index]['EanCode']);
		$('[name="product_articledescription'+row+'"]').val(articles['products'][index]['Description']);
		$('[name="product_salesprice'+row+'"]').val(articles['products'][index]['SalesPrice']);
		$('[name="product_tax'+row+'"]').val(articles['products'][index]['BTW']);
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
				$('[name="product_salesprice'+row+'"]').val(dataP['priceagreement']['Price']);
				$('[name="product_discount'+row+'"]').val(dataP['priceagreement']['Discount']);
				calculateRow(row);
			}
		});
	}
	
</script>
