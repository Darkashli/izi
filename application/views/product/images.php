<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Productafbeeldingen');
define('PAGE', 'product');

define('SUBMENUPAGE', 'images');
define('SUBMENU', $this->load->view('product/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
</div>

<form id="productImageForm" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">image</i>
					</div>
					<h4 class="card-title"><?= TITEL ?></h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-xl-3 order-xl-last text-center">
							<div class="row">
								<div class="col-12 dragdrop">
									<input type="file" name="image" accept="image/*">
								</div>
							</div>
							<button type="submit" class="btn btn-success" name="action" value="add">Opslaan</button>
						</div>
						<div class="col-xl-9 order-xl-first">
							<div class="row justify-content-center align-items-end">
								<?php foreach ($productImages as $productImage): ?>
									<div class="col-6 col-md-4 col-xl-3">
										<div class="row">
											<div class="col-12 text-center">
												<img class="img-thumbnail" src="<?= base_url("uploads/$business->DirectoryPrefix/products/$productImage->ProductId/$productImage->FileName") ?>">
											</div>
											<div class="col-12 mb-3 text-center">
												<button type="button" class="btn btn-round btn-fab btn-fab-mini btn-danger" onclick="removeImage(<?= $productImage->Id ?>)" title="verwijderen">
													<i class="material-icons">close</i>
												</button>
												<button type="button" class="btn btn-round btn-fab btn-fab-mini btn-primary" onclick="setAsMain(<?= $productImage->Id ?>)" title="Instellen als hoofdafbeelding">
													<i class="material-icons">home</i>
												</button>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">

function removeImage(imageId){
	var productImageForm = $("#productImageForm");
	swal({
		title: 'Weet u zeker dat u deze afbeelding wilt verwijderen?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ja, verwijder dit!'
	}).then((result) => {
		if (result.value) {
			var html = '<input type="hidden" name="action" value="remove">';
			html += '<input type="hidden" name="imageId" value="'+imageId+'">';
			$(productImageForm).append(html);
			$(productImageForm).submit();
		}
	});
}

function setAsMain(imageId) {
	var html = '<input type="hidden" name="action" value="set_as_main">';
	html += '<input type="hidden" name="imageId" value="'+imageId+'">';
	$(productImageForm).append(html);
	$(productImageForm).submit();
}

</script>

<?php include 'application/views/inc/footer.php'; ?>
