<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	define('TITEL', 'Producten');
	define('PAGE', 'product');
	define('SUBMENUPAGE', 'default');
	define('SUBMENU', $this->load->view('product/tab', array(), true));

	include 'application/views/inc/header.php';
?>

<?php if ($this->uri->segment(2) != 'create') { ?>
	<div class="row">
		<?= SUBMENU; ?>
	</div>
<?php } ?>
<form method="post" enctype='multipart/form-data'>
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">layers</i>
					</div>
					<h4 class="card-title">Product informatie</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-xl-9">
							<div class="row">
								<div class="col-md-4 col-sm-12 form-group label-floating">
									<label class="control-label">Artikelnummer <small>*</small></label>
									<input name="articlenumber" type="text" class="form-control" value="<?= $product->ArticleNumber; ?>" required />
								</div>
								<div class="col-md-8 col-sm-12 form-group label-floating">
									<label class="control-label">Product <small>*</small></label>
									<input id="description" name="description" type="text" class="form-control" value="<?= $product->Description ?>" required />
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label class="control-label">Leverancier</label>
									<select class="form-control selectpicker" name="supplier" data-style="btn btn-link" data-live-search="true" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
										<option value=""></option>
										<?php foreach ($suppliers as $supplier): ?>
											<option value="<?= $supplier->Id ?>" <?= $product->SupplierId == $supplier->Id ? 'selected' : NULL ?>><?= $supplier->Name ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-md-6 form-group label-floating">
									<label class="control-label">EAN code</label>
									<input type="text" name="ean_code" class="form-control" value="<?= $product->EanCode ?>">
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 col-sm-12 form-group label-floating">
									<label class="control-label">Inkoopprijs <small>*</small></label>
									<input name="purchaseprice" type="number" class="form-control"  value="<?= $product->PurchasePrice; ?>" required step="any" />
								</div>
								<div class="col-md-2 col-sm-12 form-group label-floating">
									<label class="control-label">VVP</label>
									<input name="vvp" type="number" class="form-control" value="<?= $product->Vvp; ?>" step="any" />
								</div>
								<div class="col-md-2 col-sm-12 form-group label-floating">
									<label class="control-label">Verkoopprijs <small>*</small></label>
									<input name="salesprice" type="number" class="form-control" value="<?= $product->SalesPrice; ?>" required step="any" />
								</div>
								<div class="col-md-2 col-sm-12 form-group">
									<label>BTW <small>*</small></label>
									<select name="btw" class="form-control" required>
										<option></option>
										<?php foreach ($btwDropdown as $btwKey => $btwValue) { ?>
											<option value="<?= $btwKey ?>" <?= $btwKey == $product->BTW ? 'selected' : null ?>><?= $btwValue ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-4 col-sm-12 form-group">
									<label class="control-label">Product groep <small>*</small></label>
									<select name="productgroup" class="form-control" required>
										<option></option>
										<?php foreach ($productgroupsDropdown as $productgroupDropdown) { ?>
											<option value="<?= $productgroupDropdown->Id ?>" <?= $productgroupDropdown->Id == $product->ProductGroup ? 'selected' : null ?>><?= $productgroupDropdown->Name . (!empty($productgroupDropdown->Description) ? ' - ' . $productgroupDropdown->Description : null) ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-12">
									<label>Uitgebreide omschrijving</label>
									<textarea id="long_description" name="long_description" class="editortools" value="<?= $product->LongDescription ?>" rows="5"><?= $product->LongDescription ?></textarea>
								</div>
							</div>
						</div>
						<div class="col-12 col-xl-3 mt-3 mt-xl-0">
							<div class="row">
								<div class="col-md-6 col-xl-12">
									<div class="row">
										<div class="col-12 togglebutton">
											<label>
												<input type="checkbox" name="active" <?= $product->Active == 0 ? null : 'checked' ?>>
												Product is actief
											</label>
										</div>
										<?php if (hasWebshop()): ?>
											<div class="col-12 togglebutton">
												<label>
													<input id="is_shop" type="checkbox" name="is_shop" <?= $product->isShop ? 'checked' : NULL ?> onchange="toggleShowProductInWebshop(), requireShopElements();" <?= $product->Type == 1 ? 'disabled' : NULL ?>>
													Product weergeven in webshop
												</label>
											</div>
										<?php endif; ?>
									</div>
								</div>
								<div class="col-md-6 col-xl-12">
									<div class="row">
										<div class="col-12 mt-4 mt-md-0 mt-xl-4 form-check form-check-radio">
											<label class="form-check-label">
												<input type="radio" name="product_kind" value="0" class="form-check-input mr-3" <?= $product->ProductKind == 0 ? 'checked' : null ?> onchange="toggleProductKind()">
												Standaardproduct
												<span class="circle">
													<span class="check"></span>
												</span>
											</label>
										</div>
										<div class="col-12 form-check form-check-radio">
											<label class="form-check-label">
												<input type="radio" name="product_kind" value="1" class="form-check-input mr-3" <?= $product->ProductKind == 1 ? 'checked' : null ?> onchange="toggleProductKind()">
												Voorraadproduct
												<span class="circle">
													<span class="check"></span>
												</span>
											</label>
										</div>
										<div class="col-12 form-check form-check-radio">
											<label class="form-check-label">
												<input type="radio" name="product_kind" value="2" class="form-check-input mr-3" <?= $product->ProductKind == 2 ? 'checked' : null ?> onchange="toggleProductKind()">
												Product is arbeid
												<span class="circle">
													<span class="check"></span>
												</span>
											</label>
										</div>
										<?php if (checkModule('ModuleRepeatingInvoice')): ?>
											<div class="col-12 form-check form-check-radio">
												<label class="form-check-label">
													<input type="radio" name="product_kind" value="3" class="form-check-input mr-3" <?= $product->ProductKind == 3? 'checked' : null ?> onchange="toggleProductKind()">
													Product is domeinnaam
													<span class="circle">
														<span class="check"></span>
													</span>
												</label>
											</div>
										<?php endif; ?>
									</div>
								</div>
								<div class="col-md-6 col-xl-12">
									<div class="row">

										<div class="col-12 mt-4 mt-md-0 mt-xl-4 form-check form-check-radio">
											<label class="form-check-label">
												<input id="input_product_type_sales" type="radio" name="type" value="0" class="form-check-input mr-3" <?= $product->Type == 0 ? 'checked' : null ?> onclick="toggleShowProductInWebshop()">
												Verkoopproduct
												<span class="circle">
													<span class="check"></span>
												</span>
											</label>
										</div>
										<div class="col-12 form-check form-check-radio">
											<label class="form-check-label">
												<input id="input_product_type_bought" type="radio" name="type" value="1" class="form-check-input mr-3" <?= $product->Type == 1 ? 'checked' : null ?> onclick="toggleShowProductInWebshop()">
												Inkoopproduct
												<span class="circle">
													<span class="check"></span>
												</span>
											</label>
										</div>
										<div class="col-12 form-check form-check-radio">
											<label class="form-check-label">
												<input id="input_product_type_both" type="radio" name="type" value="2" class="form-check-input mr-3" <?= $product->Type == 2 ? 'checked' : null ?> onclick="toggleShowProductInWebshop()">
												Beide
												<span class="circle">
													<span class="check"></span>
												</span>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card <?= $product->ProductKind != 1 ? 'hidden' : null ?>" id="stock">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">file_download</i>
					</div>
					<h4 class="card-title">Voorraadproduct</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-md-4 form-group label-floating">
							<label class="control-label">Magazijnlocatie</label>
							<input name="warehouselocation" class="form-control" value="<?= $product->WarehouseLocation; ?>" />
						</div>
						<div class="col-12 col-md-4 form-group">
							<label>Magazijn</label>
							<select name="warehouse" class="form-control">
								<option></option>
								<?php foreach ($warehousesDropdown as $warehouseKey => $warehouseValue) { ?>
									<option value="<?= $warehouseKey ?>" <?= $warehouseKey == $product->Warehouse ? 'selected' : null ?>><?= $warehouseValue ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-12 col-md-4 form-group label-floating">
							<label class="control-label">Bulklocatie</label>
							<input name="bulklocation" class="form-control" value="<?= $product->BulkLocation; ?>" />
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 input-group">
							<div class="form-group label-floating">
								<label class="control-label">Technische voorraad</label>
								<input type="number" name="stock" id="stock_input" class="form-control" value="<?= $product->Stock ?>" <?= $product->StockReadonly ? 'readonly' : null ?>/>
							</div>
							<div class="input-group-append">
								<span class="input-group-text">
									<i class="material-icons clickable" onclick="toggleStock()" id="stock_readonly"><?= $product->StockReadonly ? 'lock_outline' : 'lock_open' ?></i>
								</span>
								<input type="hidden" name="stock_readonly" id="stock_readonly_input" value="<?= $product->StockReadonly ?>">
							</div>
						</div>
						<?php if ($this->uri->segment(2) == 'edit') { ?>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">Beschikbare voorraad</label>
								<input type="number" value="<?= $product->Stock - $countBackOrder ?>" class="form-control" readonly>
							</div>
							<div class="col-md-4 form-group label-floating">
								<label class="control-label">In orders</label>
								<input type="number" value="<?= $countBackOrder ?>" id="inOrders" class="form-control" readonly>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card <?= $product->ProductKind != 2 ? 'hidden' : null ?>" id="labor">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">show_chart</i>
					</div>
					<h4 class="card-title">Product is arbeid</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6 form-group">
							<label>Soort product</label>
							<select name="natureofwork" class="form-control">
								<option></option>
								<?php foreach ($natureOfWorkDropdown as $natureOfWorkKey => $NatureOfWorkValue) { ?>
									<option value="<?= $natureOfWorkKey ?>" <?= $natureOfWorkKey == $product->NatureOfWork ? 'selected' : null ?>><?= $NatureOfWorkValue ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-6 form-group">
							<label>Medewerker</label>
							<select name="userid" class="form-control">
								<option></option>
								<?php foreach ($userDropdown[0] as $UserKey => $UserValue) { ?>
									<option value="<?= $UserKey ?>" <?= $UserKey == $product->UserId ? 'selected' : null ?> <?= in_array($UserKey, $userDropdown[1]) ? 'disabled' : null ?>><?= $UserValue ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if (hasWebshop()): ?>
			<div class="col-12 <?= $product->isShop ? NULL : 'hidden'; ?>" id="webshop">
				<div class="card">
					<div class="card-header card-header-icon card-header-primary">
						<div class="card-icon">
							<i class="material-icons">shopping_cart</i>
						</div>
						<h4 class="card-title">Product weergeven in webshop</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<div class="row">
									<div class="col-md-9 form-group label-floating">
										<label class="control-label">Korte omschrijving <small>*</small></label>
										<input id="woocommerce_description" name="woocommerce_description" type="text" class="form-control" value="<?= $product->Woocommerce_Description; ?>"/>
									</div>
									<div class="col-md-3 mt-3 form-check">
										<label class="form-check-label" id="sold_individually" data-toggle="tooltip" data-placement="bottom" title="Activeer dit als dit artikel maar één keer per bestelling gekocht mag worden">
											<input class="form-check-input" name="sold_individually" type="checkbox" <?= $product->SoldIndividually == 1 ? 'checked' : null ?> />
											Individueel verkocht
											<span class="form-check-sign">
												<span class="check"></span>
											</span>
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3 form-check">
										<label class="form-check-label">
											<input class="form-check-input" type="checkbox" name="woocommerce_in_sale" onclick="woocomerce_is_sale(this)" <?= $product->WoocommerceInSale == 1 ? 'checked' : NULL ?>>
											Actie
											<span class="form-check-sign">
												<span class="check"></span>
											</span>
										</label>
									</div>
									<div class="col-md-3 form-group label-floating">
										<label class="control-label">Actieprijs <small>(€)</small></label>
										<input id="sale_price" min="0" name="sale_price" type="number" class="form-control" value="<?= $product->SalePrice ?>" step="any" <?= $product->WoocommerceInSale == 0 ? 'disabled' : NULL ?> />
									</div>
									<div class="col-md-3 form-group">
										<label class="control-label">Actieperiode (van)</label>
										<input id="sale_price_dates_from" name="sale_price_dates_from" type="date" class="form-control date-picker" value="<?= $product->SalePriceDatesFrom != NULL ?  date("Y-m-d", $product->SalePriceDatesFrom) : NULL ?>" <?= $product->WoocommerceInSale == 0 ? 'disabled' : NULL ?> />
									</div>
									<div class="col-md-3 form-group">
										<label class="control-label">Actieperiode (tot)</label>
										<input id="sale_price_dates_to" name="sale_price_dates_to" type="date" class="form-control date-picker" value="<?= $product->SalePriceDatesTo != NULL ?  date("Y-m-d", $product->SalePriceDatesTo) : NULL ?>" <?= $product->WoocommerceInSale == 0 ? 'disabled' : NULL ?> />
									</div>
								</div>
								<div class="row">
									<div class="col-md-3 form-group label-floating">
										<label class="control-label">Gewicht (kg)</label>
										<input name="weight" type="number" class="form-control" value="<?= $product->Weight ?>" step="any"/>
									</div>
									<div class="col-md-3 form-group label-floating">
										<label class="control-label">Hoogte (cm)</label>
										<input name="height" type="number" class="form-control" value="<?= $product->Height ?>" step="any"/>
									</div>
									<div class="col-md-3 form-group label-floating">
										<label class="control-label">Lengte (cm)</label>
										<input name="length" type="number" class="form-control" value="<?= $product->Length ?>" step="any"/>
									</div>
									<div class="col-md-3 form-group label-floating">
										<label class="control-label">Breedte (cm)</label>
										<input name="width" type="number" class="form-control" value="<?= $product->Width ?>" step="any"/>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<h4>Upsells</h4>
								<div class="table-responsive">
									<table class="table table-borderless w-100" id="tableUpsells">
										<thead>
											<tr>
												<td class="w10">Atrikelnummer</td>
												<td class="w70">Artikelomschrijving</td>
												<td>&nbsp</td>
											</tr>
										</thead>
										<tbody>
											<?php $i_u = 1; ?>
											<?php $upsellsIds = unserialize($product->Upsells); ?>
											<?php if ($upsellsIds !== false): ?>
												<?php foreach ($upsellsIds as $upsellId):
													$upsell = getProductById($upsellId);
													?>
													<tr id="upsellRow<?= $i_u ?>">
														<td>
															<input name="u_articleid[]" id="u_articleid<?= $i_u ?>" type="hidden" value="<?= $upsell->Id ?>" />
															<input id="u_articlenumber<?= $i_u ?>" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumber(this, <?= $i_u ?>, 'u')" value="<?= $upsell->ArticleNumber ?>" required />
															<ul class="list-group suggestions-listgroup clickable"></ul>
														</td>
														<td>
															<input id="u_articledescription<?= $i_u ?>" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, <?= $i_u ?>, 'u')" value="<?= $upsell->Description ?>" required />
															<ul class="list-group suggestions-listgroup clickable"></ul>
														</td>
														<td class="td-actions text-right">
															<button type="button" onclick="removeRowU(<?= $i_u ?>)" class="btn btn-danger btn-round btn-fab btn-fab-mini">
																<i class="material-icons">close</i>
															</button>
														</td>
													</tr>
												<?php $i_u++; endforeach; ?>
											<?php endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td class="td-actions text-right">
													<button type="button" rel="tooltip" onclick="addRowU()" class="btn btn-success btn-round btn-fab btn-fab-mini">
														<i class="material-icons">add</i>
													</button>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
							<div class="col-lg-6">
								<h4>Crossells</h4>
								<div class="table-responsive">
									<table class="table table-borderless w-100" id="tableCrossells">
										<thead>
											<tr>
												<td class="w10">Atrikelnummer</td>
												<td class="w70">Artikelomschrijving</td>
												<td>&nbsp</td>
											</tr>
										</thead>
										<tbody>
											<?php $i_c = 1; ?>
											<?php $crossellsIds = unserialize($product->Crossells); ?>
											<?php if ($crossellsIds !== false): ?>
												<?php foreach ($crossellsIds as $crossellId):
													$crossell = getProductById($crossellId);
													?>
													<tr id="crossellRow<?= $i_c ?>">
														<td>
															<input name="c_articleid[]" id="c_articleid<?= $i_c ?>" type="hidden" value="<?= $crossell->Id ?>" />
															<input id="c_articlenumber<?= $i_c ?>" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumber(this, <?= $i_c ?>, 'c')" value="<?= $crossell->ArticleNumber ?>" required />
															<ul class="list-group suggestions-listgroup clickable"></ul>
														</td>
														<td>
															<input id="c_articledescription<?= $i_c ?>" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, <?= $i_c ?>, 'c')" value="<?= $crossell->Description ?>" required />
															<ul class="list-group suggestions-listgroup clickable"></ul>
														</td>
														<td class="td-actions text-right">
															<button type="button" onclick="removeRowC(<?= $i_c ?>)" class="btn btn-danger btn-round btn-fab btn-fab-mini">
																<i class="material-icons">close</i>
															</button>
														</td>
													</tr>
												<?php $i_c++; endforeach; ?>
											<?php endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td class="td-actions text-right">
													<button type="button" rel="tooltip" onclick="addRowC()" class="btn btn-success btn-round btn-fab btn-fab-mini">
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
			</div>
		<?php endif; ?>
	</div>
	<div class="row justify-content-center">
		<div class="col-md-4">
			<button type="submit" class="btn btn-success btn-block">Opslaan en sluiten</button>
		</div>
		<?php if ($product->shopId != 0): ?>
			<div class="col-md-4">
				<button type="button" class="btn btn-danger btn-block" onclick="areyousure()">Verwijder product uit de webshop</button>
			</div>
		<?php endif; ?>
	</div>
</form>

<script>
	var articles = {};
	
	$(document).ready(function () {

		$('#toggleStock').click(function () {
			$('#stock').attr('disabled', !$('#stock').attr('disabled'));
			$('#stockI').attr('disabled', !$('#stockI').attr('disabled'));
		});

		$('#natureOfWork').change(function () {
			if ($('#natureOfWork').val() == 0) {
				$('#userId').prop('disabled', true);
			} else {
				$('#userId').prop('disabled', false);
			}

		});

		$('#inOrders').closest(".form-group").removeClass("is-empty");

		requireShopElements();
		
		$('.date-picker').datetimepicker({
			locale: 'nl',
			format: 'L',
			icons: datetimepickerIcons
		});
		
		$("#sold_individually").tooltip();

	});

	function toggleProductKind(){
		var productKind = $('input[name="product_kind"]:checked').val();
		switch (productKind) {
			case '1':
				$("#stock").slideDown();
				$("#labor").slideUp();
				break;
			case '2':
				$("#labor").slideDown();
				$("#stock").slideUp();
				break;
			default:
				$("#labor").slideUp();
				$("#stock").slideUp();
		}
	}

	function toggleStock(){
		var stockReadonly = $("#stock_readonly_input").val();
		if (stockReadonly == 0) {
			$("#stock_input").prop("readonly", true);
			$("#stock_readonly").html("lock_outline");
			$("#stock_readonly_input").val(1);
		}
		else{
			$("#stock_input").prop("readonly", false);
			$("#stock_readonly").html("lock_open");
			$("#stock_readonly_input").val(0);
		}
	}

	function toggleManageStock(){
		$('ManageStock').on('change', function(){
		this.value = this.checked ? 1 : 0;
		// alert(this.value);
		}).change();
	}
	
	function toggleShowProductInWebshop() {
		var input_is_shop = $("input#is_shop");
		var input_product_type_sales = $("input#input_product_type_sales");
		var input_product_type_bought = $("input#input_product_type_bought");
		var input_product_type_both = $("input#input_product_type_both");
		
		if ($(input_is_shop).prop("checked") == true && ($(input_product_type_sales).prop("checked") == true || $(input_product_type_both).prop("checked") == true)) {
			$("#webshop").slideDown();
		}
		else{
			$("#webshop").slideUp();
			if ($(input_product_type_bought).prop("checked") == true) {
				$(input_is_shop).prop("checked", false);
				$(input_is_shop).prop("disabled", true);
			}
			else{
				$(input_is_shop).prop("disabled", false);
			}
		}
	}

	function requireShopElements(){
		var getCheckedShop = $("#is_shop");
		var Description = $("#woocommerce_description");
		if ($(getCheckedShop).prop("checked") == true){
			$(Description).prop("required", true);
		}
		else{
			$(Description).prop("required", false);
		}
	}
	
	<?php if (isset($product->Id)) { ?>
		function areyousure(){
			var linkUrl = "<?= base_url('product/deleteWoocommerceProduct/'.$product->Id) ?>";
			swal({
				title: 'Weet u zeker dat u dit product uit de webshop wilt verwijderen?',
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ja, verwijder dit!'
			}).then((result) => {
				if (result.value) {
					window.location.href = linkUrl;
				}
			});
		}
	<?php } ?>
	
	function woocomerce_is_sale(element){
		if ($(element).prop("checked") == true) {
			$("input#sale_price").prop("disabled", false);
			$("input#sale_price_dates_from").prop("disabled", false);
			$("input#sale_price_dates_to").prop("disabled", false);
		}
		else{
			$("input#sale_price").prop("disabled", true);
			$("input#sale_price_dates_from").prop("disabled", true);
			$("input#sale_price_dates_to").prop("disabled", true);
		}
	}
	
	function searchArticlenumber(element, row, sells){
		var value = $(element).val().trim();

		$.ajax({
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/ArticleNumber/2/all/all/1'
		}).done(function(msg){
			articles = JSON.parse(msg);
			var html = '';

			$.each(articles.products, function(index, value){
				html += '<li class="list-group-item list-group-item-action" onclick="clickSuggestionList('+row+', '+index+', \''+sells+'\')"><span class="font-weight-bold mr-1">'+value.ArticleNumber+'</span> | '+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</li>';
			});

			$(element).closest("td").find("ul.suggestions-listgroup").html(html);
		});
	}
	
	function searchDescription(element, row, sells){
		var value = $(element).val().trim();

		$.ajax({
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/Description/2/all/all/1'
		}).done(function(msg){
			articles = JSON.parse(msg);
			var html = '';

			$.each(articles.products, function(index, value){
				html += '<li class="list-group-item list-group-item-action" onclick="clickSuggestionList('+row+', '+index+', \''+sells+'\')">'+value.ArticleNumber+' | <span class="font-weight-bold ml-1">'+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</span></li>';
			});

			$(element).closest("td").find("ul.suggestions-listgroup").html(html);
		});
	}
	
	function clickSuggestionList(row, index, sells){
		$('#'+sells+'_articleid'+row).val(articles['products'][index]['Id']);
		$('#'+sells+'_articlenumber'+row).val(articles['products'][index]['ArticleNumber']);
		$('#'+sells+'_articledescription'+row).val(articles['products'][index]['Description']);
	}
	
</script>

<?php if (hasWebshop()): ?>
	<script type="text/javascript">
		
		var currentRowU = <?= $i_u ?>;
		var currentRowC = <?= $i_c ?>;
		
		function removeRowU(i) {
			$('#upsellRow' + i).remove();
		}
		
		function addRowU() {
			var myRow = '<tr id="upsellRow' + currentRowU + '">';
			myRow += '<td>';
			myRow += '<input name="u_articleid[]" id="u_articleid' + currentRowU + '" type="hidden" />';
			myRow += '<input id="u_articlenumber' + currentRowU + '" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumber(this, ' + currentRowU + ', \'u\')" value="" required />';
			myRow += '<ul class="list-group suggestions-listgroup clickable"></ul>';
			myRow += '</td>';
			myRow += '<td>';
			myRow += '<input id="u_articledescription' + currentRowU + '" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, ' + currentRowU + ', \'u\')" value="" required />';
			myRow += '<ul class="list-group suggestions-listgroup clickable"></ul>';
			myRow += '</td>';
			myRow += '<td class="td-actions text-right">';
			myRow += '<button type="button" onclick="removeRowU(' + currentRowU + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini">';
			myRow += '<i class="material-icons">close</i>';
			myRow += '</button>';
			myRow += '</td>';
			myRow += '</tr>';
			$("#tableUpsells tbody").append(myRow);
			currentRowU++;
		}
		
		function removeRowC(i) {
			$('#crossellRow' + i).remove();
		}
		
		function addRowC() {
			var myRow = '<tr id="crossellRow' + currentRowC + '">';
			myRow += '<td>';
			myRow += '<input name="c_articleid[]" id="c_articleid' + currentRowC + '" type="hidden" />';
			myRow += '<input id="c_articlenumber' + currentRowC + '" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumber(this, ' + currentRowC + ', \'c\')" value="" required />';
			myRow += '<ul class="list-group suggestions-listgroup clickable"></ul>';
			myRow += '</td>';
			myRow += '<td>';
			myRow += '<input id="c_articledescription' + currentRowC + '" class="form-control" type="text" autocomplete="off" onkeyup="searchDescription(this, ' + currentRowC + ', \'c\')" value="" required />';
			myRow += '<ul class="list-group suggestions-listgroup clickable"></ul>';
			myRow += '</td>';
			myRow += '<td class="td-actions text-right">';
			myRow += '<button type="button" onclick="removeRowC(' + currentRowC + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini">';
			myRow += '<i class="material-icons">close</i>';
			myRow += '</button>';
			myRow += '</td>';
			myRow += '</tr>';
			$("#tableCrossells tbody").append(myRow);
			currentRowC++;
		}
		
	</script>
<?php endif; ?>

<?php include 'application/views/inc/footer.php'; ?>
