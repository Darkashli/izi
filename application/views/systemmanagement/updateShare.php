<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Systeembeheer');
define('PAGE', 'systemmanagement');

include 'application/views/inc/header.php';

$i = 1;
?>

<form method="post" action="<?= base_url(); ?>SystemManagement/<?= $this->uri->segment(2); ?>/<?= $this->uri->segment(3); ?>">
	<div class="row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">share</i>
					</div>
					<h4 class="card-title">Shares & Rechten</h4>
				</div>
				<div class="card-body">
					
					<div class="row">
					
						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Schijfletter</label>
							<input type="text" name="driveletter" class="form-control" value="<?= $share->DriveLetter; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Beschrijving</label>
							<input type="text" name="description" class="form-control" value="<?= $share->Description; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Netwerknaam</label>
							<input type="text" name="networkname" class="form-control" value="<?= $share->NetworkName; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Locatie server</label>
							<input type="text" name="locationserver" class="form-control" value="<?= $share->LocationServer; ?>" />
						</div>

						<div class="col-12">
							<div class="row align-items-center">
								<?php
								if ($share->Permission != null) {
									foreach (unserialize($share->Permission) as $key => $value) {
										?>

										<div class= "col-10 col-sm-6 form-group label-floating">
											<?php if ($i == 1) { ?><label class="control-label">Groep / Gebruiker</label><?php } ?>
											<input type="text" name="group<?= $i; ?>" class="form-control" value="<?= $key; ?>" required />
										</div>

										<div class="col-10 col-sm-5 form-group label-floating">
											<?php if ($i == 1) { ?><label class="control-label">Rechten</label><?php } ?>
											<?= form_dropdown('permission' . $i, unserialize(SHAREPERMISSION), $value, CLASSDROPDOWN); ?>
										</div>

										<div class="col-2 col-sm-1 text-right insertafter" id="row_<?= $i; ?>">
											<?php if ($i == 1) { ?>
												<button href="javascript:;" type="button" onclick="addRow()" class="btn btn-success btn-round btn-fab btn-fab-mini"><i class="material-icons">add</i></button>
											<?php }else{ ?>
												<button href="javascript:;" type="button" onclick="removeRow(<?= $i; ?>)" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i></button>
											<?php } ?>
											<input name="number[]" type="hidden" readonly value="<?= $i; ?>" />
										</div>

										<?php
										$i++;
									}
								} else {
									?>
										<div class= "col-10 col-sm-6 form-group label-floating">
											<label class="control-label">Groep / Gebruiker</label>
											<input type="text" name="group1" class="form-control" />
										</div>

										<div class="col-10 col-sm-5 form-group label-floating">
											<label class="control-label">Rechten</label>
											<?= form_dropdown('permission1', unserialize(SHAREPERMISSION), '', CLASSDROPDOWN); ?>
										</div>

										<div class="col-2 col-sm-1 text-right insertafter" id="row_1">
											<button type="button" onclick="addRow()" class="btn btn-success btn-round btn-fab btn-fab-mini"><i class="material-icons">add</i></button>
											<input name="number[]" type="hidden" readonly value="1" />
										</div>

								<?php $i++; } ?>

							</div>
						</div>

						<div class="col-12">
							<label>Opmerkingen</label>
							<textarea id="comments" name="comments" class="editortools" rows="10"><?= $share->Comments; ?></textarea>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<?php if ($this->uri->segment(2) == "updateShare") { ?>
			<div class="col-md-4">
				<a href="<?= base_url(); ?>SystemManagement/deleteShare/<?= $this->uri->segment(3); ?>" class="btn btn-danger btn-block" id="removeRuleButton">Verwijderen</a>
			</div>
		<?php } ?>
		<div class="col-md-4">
			<button type="submit" class="btn btn-success btn-block">Opslaan en sluiten</button>
		</div>
	</div>
</form>

<?php include 'application/views/inc/footer.php'; ?>

<script type="text/javascript">
	var currentRow = <?= $i ?>;

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

	function removeRow(i) {
		$('#row_' + i).prev().remove();
		$('#row_' + i).prev().remove(); // (There are 2 divs that need to be removed).
		$('#row_' + i).remove();
	}

	function addRow() {
		var newRow = '<div class= "col-10 col-sm-6 form-group label-floating is-empty">';
		newRow += '<label class="control-label">Groep / Gebruiker</label>';
		newRow += '<input type="text" name="group' + currentRow + '" class="form-control" required />';
		newRow += '</div>';

		newRow += '<div class="col-10 col-sm-5 form-group">';
		newRow += '<label>Rechten</label>';
		newRow += '<select name="permission' + currentRow + '" class="form-control">';
		newRow += '<option value="1">Volledig beheer</option>';
		newRow += '<option value="2">Lezen en schrijven</option>';
		newRow += '<option value="3">Lezen</option>';
		newRow += '</select>';
		newRow += '</div>';

		newRow += '<div class="col-2 col-sm-1 text-right insertafter" id="row_' + currentRow + '">';
		newRow += '<button type="button" onclick="removeRow(' + currentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i></button>';
		newRow += '<input name="number[]" type="hidden" readonly value="' + currentRow + '" />';
		newRow += '</div>';

		$(".insertafter:last").after(newRow);

		currentRow++;
	}

</script>
