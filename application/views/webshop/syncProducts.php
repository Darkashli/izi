<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Webshop');
define('PAGE', 'webshop');

include 'application/views/inc/header.php';
?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">layers</i>
				</div>
				<h4 class="card-title">Gesynchroniseerde producten</h4>
			</div>
			<div class="card-body">
				<div class="alert alert-success">
					De volgende producten zijn geüpdatet in de webshop
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<i class="material-icons">close</i>
					</button>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover w-100">
						<thead>
							<tr>
								<td>Productnummer</td>
								<td>EAN code</td>
								<td>Product</td>
								<td>Verkoopprijs</td>
								<td>VVP</td>
								<td>Productgroup</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($syncedProducts as $product): ?>
								<tr data-href="<?= base_url('product/edit/'.$product->Id) ?>">
									<td><?= html_escape($product->ArticleNumber); ?></td>
									<td><?= $product->EanCode; ?></td>
									<td><?= html_escape($product->Description); ?></td>
									<td><?= $product->SalesPrice; ?></td>
									<td><?= $product->Vvp; ?></td>
									<td><?= getProductGroupName($product->ProductGroup); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php if (!empty($woocommerceProducts)): ?>
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">layers</i>
					</div>
					<h4 class="card-title">Producten aanwezig in Woocommerce</h4>
				</div>
				<div class="card-body">
					<div class="alert alert-warning">
						De volgende producten zijn aanwezig in Woocommerce maar bestaan niet in izi account. Je kunt deze negeren of verwijderen zodat je deze later handmatig toe kunt voegen.
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<i class="material-icons">close</i>
						</button>
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-hover w-100">
							<thead>
								<tr>
									<td>Artikelnummer</td>
									<td>Product</td>
									<td>Verkoopprijs</td>
									<td class="text-right">Actie</td>
								</tr>
							</thead>
							<body>
								<?php $i = 0; ?>
								<?php foreach ($woocommerceProducts as $wProduct): ?>
									<tr id="productRow<?= $i ?>" data-productid="<?= $wProduct->id ?>">
										<td data-href="<?= $wProduct->permalink ?>" data-target="blank"><?= $wProduct->sku ?></td>
										<td data-href="<?= $wProduct->permalink ?>" data-target="blank"><?= $wProduct->name ?></td>
										<td data-href="<?= $wProduct->permalink ?>" data-target="blank"><?= $wProduct->regular_price ?></td>
										<td class="td-actions text-right">
											<!-- <button type="button" class="btn btn-success btn-round btn-fab btn-fab-mini" onclick="addProduct(<?= $i ?>)" title="Toevoegen aan izi account">
												<i class="material-icons">check</i>
											</button> -->
											<button type="button" class="btn btn-danger btn-round btn-fab btn-fab-mini" onclick="deleteProduct(<?= $i ?>)" title="Verwijder uit webshop">
												<i class="material-icons">close</i>
											</button>
											<button type="button" class="btn btn-default btn-round btn-fab btn-fab-mini" onclick="cancelProduct(<?= $i ?>)" title="Negeren">
												<i class="material-icons">block</i>
											</button>
										</td>
									</tr>
									<?php $i++; ?>
								<?php endforeach; ?>
							</body>
						</table>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>

<script type="text/javascript">

function cancelProduct(row) {
	$("#productRow"+row).remove();
}

function deleteProduct(row) {
	var wProductId = $("#productRow"+row).data("productid");
	
	swal({
		title: 'Weet u zeker dat u dit product uit de webshop wilt verwijderen?',
		text: 'Deze actie kan niet ongedaan gemaakt worden.',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ja, verwijder dit!'
	}).then((result) => {
		if (result.value) {
			
			$.ajax({
				url: "<?= base_url() ?>webshop/ajax_removeProduct/"+wProductId,
			}).done(function(msg){
				var json = JSON.parse(msg);
				createNotification(json.msgtype, json.msg);
				if (json.msgtype == 'success') {
					$("#productRow"+row).remove();
				}
			});
			
		}
	});
}

</script>

<?php include 'application/views/inc/footer.php'; ?>
