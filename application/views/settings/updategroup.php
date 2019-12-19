<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Productgroepen');
define('PAGE', 'settings');

include 'application/views/inc/header.php';
?>

<form method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">layers</i>
					</div>
					<h4 class="card-title">Productgroepen</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<?php if (hasWebshop()): ?>
							<div class="col-12 col-xl-9">
						<?php else: ?>
							<div class="col-12">
						<?php endif; ?>
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<div class="col-12 form-group label-floating">
											<label class="control-label">Naam <small>*</small></label>
											<input class="form-control" name="name" value="<?= $productgroup->Name; ?>" required />
										</div>
										<div class="col-12 form-group label-floating">
											<label class="control-label">Omschrijving</label>
											<input class="form-control" name="description" value="<?= $productgroup->Description; ?>" />
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="row">
										<div class="col-12">
											<label class="control-label" for="image">Afbeelding</label>
											<br>
											<?php if ($productgroup->Image != NULL): ?>
												<img src="<?= base_url("uploads/$business->DirectoryPrefix/productgroups/$productgroup->Id/$productgroup->Image") ?>" class="img-thumbnail" id="productimage">
											<?php endif; ?>
											<div class="dragdrop">
												<input type="file" name="image" id="image">
											</div>
										</div>
										<div class="col-12 form-group label-floating">
											<label class="control-label" for="parent_productgroup">Hoofdproductgroep</label>
											<select class="form-control" name="parent_productgroup" id="parent_productgroup">
												<option></option>
												<?php foreach ($parentProductgroups as $parentProductgroup):
													if ($parentProductgroup->Id == $productgroup->Id) {
														continue;
													}
													?>
													<option value="<?= $parentProductgroup->Id ?>" <?= $parentProductgroup->Id == $productgroup->ParentId ? 'selected' : null ?>><?= $parentProductgroup->Name ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php if (hasWebshop()): ?>
							<div class="col-12 col-xl-3 mt-3 mt-xl-0">
								<div class="row">
									<div class="col-12 togglebutton">
										<input type="hidden" name="is_shop" value="0">
										<label>
											<input type="checkbox" name="is_shop" value="1" <?= $productgroup->IsShop == 1 ? 'checked' : null ?>>
											Productgroep weergeven in webshop
										</label>
									</div>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row justify-content-center">
		<div class="col-md-4">
			<button type="submit" class="btn btn-block btn-success">Opslaan en sluiten</button>
		</div>
	</div>

</form>

<?php include 'application/views/inc/footer.php'; ?>
