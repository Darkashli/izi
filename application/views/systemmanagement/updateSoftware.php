<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Systeembeheer');
define('PAGE', 'systemmanagement');

include 'application/views/inc/header.php';
?>
<form method="post" action="<?= base_url(); ?>SystemManagement/<?= $this->uri->segment(2); ?>/<?= $this->uri->segment(3); ?>">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icns">extension</i>
					</div>
					<h4 class="card-title">Software</h4>
				</div>
				<div class="card-body">

					<div class="row">
						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Ontwikkelaar</label>
							<input type="text" name="developer" class="form-control" value="<?= $software->Developer; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Productnaam <small>*</small></label>
							<input type="text" name="productname" class="form-control" value="<?= $software->ProductName; ?>" required />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Soort software</label>
							<input type="text" name="kindsoftware" class="form-control" value="<?= $software->KindSoftware; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Licentiecode</label>
							<input type="text" name="licensenumber" class="form-control" value="<?= $software->LicenseNumber; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Leverancier</label>
							<input type="text" name="suppliername" class="form-control" value="<?= $software->SupplierName; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Telefoonnummer leverancier</label>
							<input type="text" name="supplierphonenumber" class="form-control" value="<?= $software->SupplierPhonenumber; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Website leverancier</label>
							<input type="text" name="supplierwebsite" class="form-control" value="<?= $software->SupplierWebsite; ?>" />
						</div>

						<div class="col-12">
							<label class="control-label">Opmerkingen</label>
							<textarea id="comments" name="comments" class="editortools" rows="10"><?= $software->Comments; ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<?php if ($this->uri->segment(2) == "updateSoftware") { ?>
			<div class="col-md-4">
				<a href="<?= base_url(); ?>SystemManagement/deleteSoftware/<?= $this->uri->segment(3); ?>" class="btn btn-danger btn-block" id="removeRuleButton">Verwijderen</a>
			</div>
		<?php } ?>
		<div class="col-md-4">
			<button type="submit" class="btn btn-success btn-block">Opslaan en sluiten</button>
		</div>
	</div>
</form>

<?php include 'application/views/inc/footer.php'; ?>

<script type="text/javascript">
	$(document).ready(function () {

		$('#removeRuleButton').on('click', function (e) {
			e.preventDefault(); // Prevent the href from redirecting directly
			var linkURL = $(this).attr("href");
			swal({
				title: 'Weet u het zeker?',
				text: 'Deze actie kan niet ongedaan worden gemaakt!',
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ja, verwijder dit!'
			}).then((result) => {
				if (result.value) {
					window.location.href = linkURL;
				}
			});
		});
	});


</script>
