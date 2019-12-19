<table class="table table-borderless" id="tableRepeating">
	<thead>
		<tr>
			<td class="w16">Artikelnummer</td>
			<td class="w10">EAN code</td>
			<td class="w33">Artikelomschrijving</td>
			<td class="w7">Aantal</td>
			<td class="w10" id="priceT">Verkoopprijs</td>
			<td id="discountT">Korting (%)</td>
			<td>BTW</td>
			<td class="w10">Totaal</td>
			<td>&nbsp;</td>
		</tr>
	</thead>
	<tbody>
		<?php if (empty($quotationRulesR)){ ?>
			<tr id="repeatingRow1">
				<td>
					<input name="repeating_articlenumber1" id="repeating_articlenumber" class="form-control" type="text" autocomplete="off" onkeyup="REPsearchArticlenumber(this, 1)"/>
					<ul class="list-group suggestions-listgroup clickable"></ul>
				</td>
				<td>
					<input name="repeating_ean_code1" id="ean_code" class="form-control" type="text" autocomplete="off" onkeyup="REPsearchEanCode(this, 1)" />
					<ul class="list-group suggestions-listgroup clickable"></ul>
				</td>
				<td>
					<input name="repeating_articledescription1" class="form-control" type="text" autocomplete="off" onkeyup="REPsearchDescription(this, 1)" />
					<ul class="list-group suggestions-listgroup clickable"></ul>
				</td>
				<td>
					<input name="repeating_amount1" class="form-control" type="number" value="0" onchange="REPcalculateRow(1)" step="any"  />
				</td>
				<td>
					<input name="repeating_salesprice1" class="form-control" type="number" value="0.00" onchange="REPcalculateRow(1)" step="any" />
				</td>
				<td>
					<input name="repeating_discount1" class="form-control" type="number" value="0" onchange="REPcalculateRow(1)" />
				</td>
				<td>
					<div class="form-group">
						<select name="repeating_tax1" class="form-control" onchange="REPcalculateRow(1)">
							<option value="21">21%</option>
							<option value="9">9%</option>
							<option value="0">0%</option>
						</select>
					</div>
				</td>
				<td>
					<input name="repeating_total1" class="form-control" type="number" value="0.00" readonly />
					<input id="repeating_number" name="repeating_number[]" type="hidden" readonly value="1" />
				</td>
				<td class="td-actions text-center">
					<button type="button" rel="tooltip" onclick="REPremoveRow(1)" class="btn btn-danger btn-round btn-fab btn-fab-mini">
						<i class="material-icons">close</i>
					</button>
				</td>
			</tr>
		<?php $i = 2; }else{ $i = 1;
			foreach ($quotationRulesR as $quotationRuleR) { ?>
				<tr id="repeatingRow<?= $i ?>">
					<td>
						<input name="repeating_articlenumber<?= $i ?>" id="repeating_articlenumber" class="form-control" type="text" value="<?= $quotationRuleR->ArticleC ?>" autocomplete="off" onkeyup="REPsearchArticlenumber(this, <?= $i ?>)"/>
						<ul class="list-group suggestions-listgroup clickable"></ul>
					</td>
					<td>
						<input name="repeating_ean_code<?= $i ?>" id="ean_code" class="form-control" type="text" value="<?= $quotationRuleR->EanCode ?>" autocomplete="off" onkeyup="REPsearchEanCode(this, <?= $i ?>)" />
						<ul class="list-group suggestions-listgroup clickable"></ul>
					</td>
					<td>
						<input name="repeating_articledescription<?= $i ?>" class="form-control" type="text" value="<?= $quotationRuleR->ArticleDescription ?>" autocomplete="off" onkeyup="REPsearchDescription(this, <?= $i ?>)" />
						<ul class="list-group suggestions-listgroup clickable"></ul>
					</td>
					<td>
						<input name="repeating_amount<?= $i ?>" class="form-control" type="number" value="<?= $quotationRuleR->Amount ?>" onchange="REPcalculateRow(<?= $i ?>)" step="any"  />
					</td>
					<td>
						<input name="repeating_salesprice<?= $i ?>" class="form-control" type="number" value="<?= $quotationRuleR->SalesPrice ?>" onchange="REPcalculateRow(<?= $i ?>)" step="any" />
					</td>
					<td>
						<input name="repeating_discount<?= $i; ?>" class="form-control" type="number" value="<?= $quotationRuleR->SalesPrice ?>" onchange="REPcalculateRow(<?= $i; ?>)" />
					</td>
					<td>
						<div class="form-group">
							<select name="repeating_tax<?= $i ?>" class="form-control" onchange="REPcalculateRow(<?= $i ?>)">
								<option value="21" <?= $quotationRuleR->Tax == 21 ? 'selected' : null ?>>21%</option>
								<option value="9" <?= $quotationRuleR->Tax == 9 ? 'selected' : null ?>>9%</option>
								<option value="0" <?= $quotationRuleR->Tax == 0 ? 'selected' : null ?>>0%</option>
							</select>
						</div>
					</td>
					<td>
						<input name="repeating_total<?= $i ?>" class="form-control" type="number" value="<?= $quotationRuleR->Amount * $quotationRuleR->SalesPrice ?>" readonly />
						<input id="repeating_number" name="repeating_number[]" type="hidden" readonly value="<?= $i ?>" />
					</td>
					<td class="td-actions text-center">
						<button type="button" rel="tooltip" onclick="REPremoveRow(<?= $i ?>)" class="btn btn-danger btn-round btn-fab btn-fab-mini">
							<i class="material-icons">close</i>
						</button>
						<input type="hidden" name="repeating_ruleNumber<?= $i ?>" value="<?= $quotationRuleR->Id ?>">
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
				<button type="button" rel="tooltip" onclick="REPaddRow()" class="btn btn-success btn-round btn-fab btn-fab-mini">
					<i class="material-icons">add</i>
				</button>
			</td>
		</tr>
	</tfoot>
</table>
<label for="recurring_description">Opmerking</label>
<textarea name="recurring_description" id="recurring_description" class="editortools" rows="10"><?= $quotation->RecurringDescription ?></textarea>
<div class="form-group label-floating">
	<label class="control-label">Periode</label>
	<?= $period; ?>
</div>

<script type="text/javascript">
	var REPcurrentRow = <?= $i ?>;
	var articles = {};
	
	$(document).keydown(function(e){
		if (e.which == 107) { // "+" key.
			if ($(".table input").is(':focus')) {
				e.preventDefault();
				$('.suggestions-listgroup').html('');
				REPaddRow();
				$('input[name="repeating_articlenumber'+(REPcurrentRow - 1)+'"]').focus();
			}
		}
 	});

	function REPstopRKey(evt){
		var evt = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type == "text")) {
			return false;
		}
	}

	document.onkeypress = REPstopRKey;
	
	function REPcalculateRow(i){
		total = 0;
			if (document.getElementsByName('repeating_amount' + i)[0].value > 0) {
				total = (document.getElementsByName('repeating_amount' + i)[0].value * document.getElementsByName('repeating_salesprice' + i)[0].value) * (1 - (document.getElementsByName('repeating_discount' + i)[0].value / 100));
			}
			if (document.getElementsByName('repeating_salesprice' + i)[0].value > 0) {
				total = parseFloat((document.getElementsByName('repeating_amount' + i)[0].value * document.getElementsByName('repeating_salesprice' + i)[0].value) * (1 - (document.getElementsByName('repeating_discount' + i)[0].value / 100)));
			}
		document.getElementsByName('repeating_total' + i)[0].value = total.toFixed(2);

	}
	
	function REPremoveRow(i){
		$('#repeatingRow' + i).remove();
	}
	
	function REPaddRow(){

		var next = REPcurrentRow + 1;
		var myRow = '<tr id="repeatingRow' + REPcurrentRow + '">';
		myRow += '<td><div class="form-group"><input name="repeating_articlenumber' + REPcurrentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="REPsearchArticlenumber(this, ' + REPcurrentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td><div class="form-group"><input name="repeating_ean_code' + REPcurrentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="REPsearchEanCode(this, ' + REPcurrentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td><div class="form-group"><input name="repeating_articledescription' + REPcurrentRow + '" class="form-control" type="text" autocomplete="off" onkeyup="REPsearchDescription(this, ' + REPcurrentRow + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td><div class="form-group"><input name="repeating_amount' + REPcurrentRow + '" class="form-control" type="number" value="0" onchange="REPcalculateRow(' + REPcurrentRow + ')" step="any" /></div></td>';
		myRow += '<td><div class="form-group"><input name="repeating_salesprice' + REPcurrentRow + '" class="form-control" type="number" value="0.00" onchange="REPcalculateRow(' + REPcurrentRow + ')" step="any" /></div></td>';
		myRow += '<td><div class="form-group"><input name="repeating_discount' + REPcurrentRow + '" class="form-control" type="number" value="0.00" onchange="REPcalculateRow(' + REPcurrentRow + ')" /></div></td>';
		myRow += '<td><div class="form-group"><select name="repeating_tax' + REPcurrentRow + '" class="form-control" onchange="REPcalculateRow(' + REPcurrentRow + ')" ></div>';
		myRow += '<option value="21">21%</option>';
		myRow += '<option value="9">9%</option>';
		myRow += '<option value="0">0%</option>';
		myRow += '</select></div></td>';
		myRow += '<td><div class="form-group"><input name="repeating_total' + REPcurrentRow + '" class="form-control" type="number" value="0.00" readonly /> <input id="number" name="repeating_number[]" type="hidden" readonly value="' + REPcurrentRow + '" /></td>';
		myRow += '<td class="td-actions text-center"><button type="button" onclick="REPremoveRow(' + REPcurrentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i><div class="ripple-container"></div></button></td>';
		myRow += '</tr>';
		$("#tableRepeating tbody").append(myRow);
		REPcurrentRow++;
	}

	function REPsearchArticlenumber(element, row){
		var value = $(element).val().trim();

		$.ajax({
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/ArticleNumber/0'
		}).done(function(msg){
			articles = JSON.parse(msg);
			var html = '';

			$.each(articles.products, function(index, value){
				html += '<li class="list-group-item list-group-item-action" onclick="REPclickSuggestionList('+row+', '+index+')"><span class="font-weight-bold mr-1">'+value.ArticleNumber+'</span> | '+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</li>';
			});

			$(element).closest("td").find("ul.suggestions-listgroup").html(html);
		});
	}

	function REPsearchEanCode(element, row){
		var value = $(element).val().trim();

		$.ajax({
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/EanCode/0'
		}).done(function(msg){
			articles = JSON.parse(msg);
			var html = '';

			$.each(articles.products, function(index, value){
				html += '<li class="list-group-item list-group-item-action" onclick="REPclickSuggestionList('+row+', '+index+')"><span class="font-weight-bold mr-1">'+value.EanCode+'</span> | '+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</li>';
			});

			$(element).closest("td").find("ul.suggestions-listgroup").html(html);
		});
	}
	
	function REPsearchDescription(element, row){
		var value = $(element).val().trim();

		$.ajax({
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/Description/0'
		}).done(function(msg){
			articles = JSON.parse(msg);
			var html = '';

			$.each(articles.products, function(index, value){
				html += '<li class="list-group-item list-group-item-action" onclick="REPclickSuggestionList('+row+', '+index+')">'+value.ArticleNumber+' | <span class="font-weight-bold ml-1">'+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</span></li>';
			});

			$(element).closest("td").find("ul.suggestions-listgroup").html(html);
		});
	}
	
	function REPclickSuggestionList(row, index){
		$('[name="repeating_articlenumber'+row+'"]').val(articles['products'][index]['ArticleNumber']);
		$('[name="repeating_ean_code'+row+'"]').val(articles['products'][index]['EanCode']);
		$('[name="repeating_articledescription'+row+'"]').val(articles['products'][index]['Description']);
		$('[name="repeating_salesprice'+row+'"]').val(articles['products'][index]['SalesPrice']);
		$('[name="repeating_tax'+row+'"]').val(articles['products'][index]['BTW']);
		REPcalculateRow(row);
		
		// Get priceagreements.
		$.ajax({
			url: '<?= site_url() ?>/customers/searchPriceagreement/'+customerId+'/'+articles['products'][index]['ArticleNumber']
		}).done(function(msg){
			dataP = JSON.parse(msg);
			if (dataP['error']) {
				console.log(dataP['error']);
			}
			else if (dataP['priceagreement'] != null) {
				$('[name="repeating_salesprice'+row+'"]').val(dataP['priceagreement']['Price']);
				$('[name="repeating_discount'+row+'"]').val(dataP['priceagreement']['Discount']);
				REPcalculateRow(row);
			}
		});
	}
	
</script>
