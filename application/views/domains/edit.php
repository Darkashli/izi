<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Domeinnamen');
define('PAGE', 'domains');

include 'application/views/inc/header.php';
?>

<form method="post">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">dvr</i>
					</div>
					<h4 class="card-title">Domeinnaam bewerken</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6 form-group label-floating">
							<label class="control-label">Domeinnaam <small>*</small></label>
							<input value="<?= $domain->Name; ?>" type="text" class="form-control" name="name" required />
						</div>
						<div class="col-md-6 input-group">
							<div class="form-group label-floating">
								<label class="control-label">Registratiedatum <small>*</small></label>
								<input value="<?= date('d-m-Y', strtotime($domain->RegisterDate)) ?>" type="text" class="form-control date-picker" name="register_date" required />
							</div>
							<div class="input-group-append">
								<span class="input-group-text">
									<i class="material-icons">date_range</i>
								</span>
							</div>
						</div>
						<div class="col-md-6 form-group label-floating">
							<label class="control-label">Klant <small>*</small></label>
							<select class="form-control" name="customer" required>
								<option value=""></option>
								<?php foreach ($customers as $customer): ?>
									<option value="<?= $customer->Id ?>" <?= $customer->Id == $domain->Customer ? 'selected' : null ?>><?= $customer->Name ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-6 form-group label-floating">
							<label class="control-label">Reseller</label>
							<select class="form-control" name="reseller">
								<option value=""></option>
								<?php foreach ($customers as $customer): ?>
									<option value="<?= $customer->Id ?>" <?= $customer->Id == $domain->Reseller ? 'selected' : null ?>><?= $customer->Name ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-6 togglebutton">
							<label>
								<input type="checkbox" name="has_hosting" <?= $domain->HasHosting == 1 ? 'checked' : null ?>>
								Wel of geen hosting?
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-md-4">
			<button class="btn btn-block btn-success" type="submit">Opslaan</button>
		</div>
		<?php if (isset($domain->Id)) { ?>
			<div class="col-md-4">
				<button class="btn btn-block btn-danger" type="button" onclick="areyousure()">Verwijder</button>
			</div>
		<?php } ?>
	</div>
</form>

<?php include 'application/views/inc/footer.php'; ?>

<script type="text/javascript">

	$(document).ready(function () {

		$('.date-picker').datetimepicker({
			locale: 'nl',
			format: 'L',
			useCurrent: false,
			widgetPositioning: {
				vertical: 'bottom'
			},
			icons: datetimepickerIcons
		});
	
	});
	
	<?php if (isset($domain->Id)) { ?>
		function areyousure(){
			var linkUrl = "<?= base_url('domains/delete/'.$domain->Id) ?>";
			swal({
				title: 'Weet u zeker dat u deze domeinnaam wilt verwijderen?',
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
	
</script>
