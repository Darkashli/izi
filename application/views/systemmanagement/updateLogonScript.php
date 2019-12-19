<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Systeembeheer');
define('PAGE', 'systemmanagement');

include 'application/views/inc/header.php';
?>

<form method="post" action="<?= base_url(); ?>SystemManagement/<?= $this->uri->segment(2); ?>/<?= $this->uri->segment(3); ?>">
	<div class="row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">description</i>
					</div>
					<h4 class="card-title">Login script</h4>
				</div>
				<div class="card-body">

					<div class="row">

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Bestandsnaam</label>
							<input type="text" name="filename" class="form-control" value="<?= $loginscript->FileName; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Netwerknaam</label>
							<input type="text" name="networkname" class="form-control" value="<?= $loginscript->NetworkName; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Locatie server</label>
							<input type="text" name="locationserver" class="form-control" value="<?= $loginscript->LocationServer; ?>" />
						</div>

						<div class="col-lg-12 col-xl-12 col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Script</label>
							<textarea name="script" class="form-control"><?= $loginscript->Script; ?></textarea>
						</div>

						<div class="col-12">
							<label class="control-label">Opmerkingen</label>
							<textarea id="comments" name="comments" class="editortools" rows="10"><?= $loginscript->Comments; ?></textarea>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<?php if ($this->uri->segment(2) == "updateLogonScript") { ?>
			<div class="col-md-4">
				<a href="<?= base_url(); ?>SystemManagement/deleteLogonScript/<?= $this->uri->segment(3); ?>" class="btn btn-danger btn-block" id="removeRuleButton">Verwijderen</a>
			</div>
		<?php } ?>
		<div class="col-md-4">
			<button type="submit" class="btn btn-success btn-block">Opslaan en sluiten</button>
		</div>
	</div>
</form>

<script>
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
