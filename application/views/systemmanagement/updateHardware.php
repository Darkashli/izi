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
						<i class="material-icons">desktop_windows</i>
					</div>
					<h4 class="card-title">Apparaat</h4>
				</div>
				<div class="card-body">

					<div class="row">
						<div class="col-lg-12 col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Soort</label>
							<?= $hardwareKind; ?>
						</div>

						<div class="col-lg-6 col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Merk</label>
							<input type="text" name="brand" class="form-control" value="<?= $hardware->Brand; ?>" />
						</div>

						<div class="col-lg-6 col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Type</label>
							<input type="text" name="type" class="form-control" value="<?= $hardware->Type; ?>"/>
						</div>

						<div class="col-lg-6 col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Processor</label>
							<input type="text" name="processor" class="form-control" id="processor" value="<?= $hardware->Processor; ?>" />
						</div>

						<div class="col-lg-6 col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Geheugen</label>
							<input type="text" name="memory" class="form-control" id="memory" value="<?= $hardware->Memory; ?>" />
						</div>

						<div class="col-lg-12 col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Besturingssysteem</label>
							<input type="text" name="operatingsystem" class="form-control" id="operatingsystem" value="<?= $hardware->OperatingSystem; ?>" />
						</div>

						<div class="col-12 row">
							<?php
							if ($hardware->HardDisks != null) {
								foreach (unserialize($hardware->HardDisks) as $key => $value) {
									?>
										<div class= "col-10 col-sm-11 form-group label-floating">
											<label class="control-label">Hardeschijf</label>
											<input type="text" name="harddisk<?= $i; ?>" class="form-control" value="<?= $value; ?>" required />
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
									<div class="col-10 col-sm-11 form-group label-floating">
										<label class="control-label">Hardeschijf</label>
										<input type="text" name="harddisk1" class="form-control" />
									</div>

									<div class="col-2 col-sm-1 text-right insertafter" id="row_1">
										<button type="button" onclick="addRow()" class="btn btn-success btn-round btn-fab btn-fab-mini"><i class="material-icons">add</i></button>
										<input name="number[]" type="hidden" readonly value="1" />
									</div>
							<?php $i++; } ?>

						</div>


						<div class="col-lg-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Mac adressen</label>
							<input type="text" name="macaddress1" class="form-control" value="<?= $hardware->MacAddress1; ?>" />
							<input type="text" name="macaddress2" class="form-control" value="<?= $hardware->MacAddress2; ?>" />
						</div>

						<div class="col-lg-6 col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Serienummer</label>
							<input type="text" name="serialnumber" class="form-control" value="<?= $hardware->SerialNumber; ?>" />
						</div>

						<div class="clearfix"></div>

						<div class="col-lg-6 col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Hostnaam</label>
							<input type="text" name="hostname" class="form-control" value="<?= $hardware->Hostname; ?>" />
						</div>


						<div class="col-lg-6 col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">IP adres</label>
							<input type="text" name="ipaddress" class="form-control" id="ipAddress" value="<?= $hardware->IpAddress; ?>" />
						</div>

						<div class="col-12">
							<label>Opmerkingen</label>
							<textarea id="comments" name="comments" class="editortools" rows="10"><?= $hardware->Comments; ?></textarea>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row justify-content-center">
		<?php if ($this->uri->segment(2) == 'updateHardware'){ ?>
			<div class="col-md-4">
				<a href="<?= base_url(); ?>SystemManagement/deleteHardware/<?= $this->uri->segment(3); ?>" id="removeRuleButton" class="btn btn-danger btn-block">Verwijderen</a>
			</div>
		<?php } ?>
		<div class="col-md-4">
			<button type="submit" class="btn btn-success btn-block">Opslaan en sluiten</button>
		</div>
	</div>
</form>

<?php include 'application/views/inc/footer.php'; ?>


<script src="<?= base_url(); ?>assets/js/IpAddress/jquery.input-ip-address-control-1.0.min.js" type="text/javascript"></script>

<script type="text/javascript">
	var currentRow = <?= $i ?>;

	$(document).ready(function () {

		$('#ipAddress').ipAddress();

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
		change();
	});

	function change() {
		var result = $('#kind').val();

		console.log(result);
		if (result == 3) {
			$('#processor').prop('disabled', true);
			$('#memory').prop('disabled', true);
			$('#operatingsystem').prop('disabled', true);
		} else {
			$('#processor').prop('disabled', false);
			$('#memory').prop('disabled', false);
			$('#operatingsystem').prop('disabled', false);
		}
	}


	function removeRow(i) {
		$('#row_' + i).prev().remove();
		$('#row_' + i).remove();
	}

	function addRow() {
		var before = currentRow - 1;

		var newRow = '<div class="col-10 col-sm-11 form-group label-floating is-empty">';
		newRow += '<label class="control-label">Hardeschijf</label>';
		newRow += '<input type="text" name="harddisk' + currentRow + '" class="form-control" required />';
		newRow += '</div>';

		newRow += '<div class="col-2 col-sm-1 text-right insertafter" id="row_' + currentRow + '">';
		newRow += '<button href="javascript:;" type="button" onclick="removeRow(' + currentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i></button>';
		newRow += '<input name="number[]" type="hidden" readonly value="' + currentRow + '" />';
		newRow += '</div>';

		$(".insertafter:last").after(newRow);

		currentRow++;
	}
</script>
