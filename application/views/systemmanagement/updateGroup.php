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
						<i class="material-icons">group_work</i>
					</div>
					<h4 class="card-title">Groepen</h4>
				</div>
				<div class="card-body">
						<div class="row">
							<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
								<label class="control-label">Naam</label>
								<input type="text" name="name" class="form-control" value="<?= $group->Name; ?>" />
							</div>

							<div class="col-lg-6 col-xl-6 col-md-6 form-group">
								<label>Type</label>
								<select name="type" class="form-control" required="">
									<option></option>
									<option <?= ($group->Type == 'Beveiligingsgroep') ? 'selected' : null ?>>Beveiligingsgroep</option>
									<option <?= ($group->Type == 'Distributiegroep') ? 'selected' : null ?>>Distributiegroep</option>
									<option <?= ($group->Type == 'Mailbox') ? 'selected' : null ?>>Mailbox</option>
								</select>
							</div>

							<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
								<label class="control-label">wachtwoord</label>
								<input type="text" name="password" class="form-control" value="<?= $group->Password; ?>" />
							</div>

							<div class="col-12">
								<label class="control-label">Opmerkingen</label>
								<textarea id="comments" name="comments" class="editortools" rows="10"><?= $group->Comments; ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row justify-content-center">
		<?php if ($this->uri->segment(2) == "updateGroup") { ?>
			<div class="col-md-4">
				<a href="<?= base_url(); ?>SystemManagement/deleteGroup/<?= $this->uri->segment(3); ?>" class="btn btn-danger btn-block" id="removeRuleButton">Verwijderen</a>
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
