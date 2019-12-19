<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Systeembeheer');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Internetgegevens: ' . getCustomerName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
define('PAGE', 'systemmanagement');

define('SUBMENUPAGE', 'internet');
define('SUBMENU', $this->load->view('systemmanagement/tab', array(), true));
include 'application/views/inc/header.php';

$i = 1;
?>

<div class="row">
	<?= SUBMENU; ?>
</div>

<form method="post">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">language</i>
					</div>
					<h4 class="card-title">Internetgegevens</h4>
				</div>
				<div class="card-body">

					<div class="row">
						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Provider</label>
							<input class="form-control" name="provider" value="<?= $internetdata->Provider; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Type abonnement</label>
							<input class="form-control" name="type" value="<?= $internetdata->Type; ?>" />
						</div>

						<div class="col-12 row">

							<?php
							if ($internetdata->IpAddress != null) {
								foreach (unserialize($internetdata->IpAddress) as $key => $value) {
									?>
										<div class="col-10 col-sm-11 form-group label-floating">
											<label class="control-label">IP Adres</label>
											<input type="text" name="ipaddress<?= $i; ?>" class="form-control" value="<?= $value; ?>" required />
										</div>
										<div class="col-2 col-sm-1 text-right insertafter" id="row_<?= $i; ?>">
											<?php if ($i == 1) { ?>
												<button type="button" onclick="addRow()" class="btn btn-success btn-round btn-fab btn-fab-mini"><i class="material-icons">add</i></button>
											<?php }else{ ?>
												<button href="javascript:;" type="button" onclick="removeRow(<?= $i; ?>)" class="btn btn-danger btn-round  btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i></button>
											<?php } ?>
											<input name="number[]" type="hidden" readonly value="<?= $i; ?>" />
										</div>
									<?php
									$i++;
								}
							} else {
								?>
								<div class="col-10 col-sm-11 form-group label-floating">
									<label class="control-label">IP Adres</label>
									<input type="text" name="ipaddress1" class="form-control" />
								</div>
								<div class="col-2 col-sm-1 text-right insertafter" id="row_1">
									<button type="button" onclick="addRow()" class="btn btn-success btn-round btn-just-icon"><i class="material-icons">add</i></button>
									<input name="number[]" type="hidden" readonly value="1" />
								</div>
							<?php $i++; } ?>

						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Primaire DNS</label>
							<input class="form-control" id="PrimaryDns" name="primarydns" value="<?= $internetdata->PrimaryDns; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Secundaire DNS</label>
							<input class="form-control" id="SecondaryDns" name="secondarydns" value="<?= $internetdata->SecondaryDns; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Gebruikersnaam</label>
							<input class="form-control" name="username" value="<?= $internetdata->Username; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Wachtwoord</label>
							<input class="form-control" name="password" value="<?= $internetdata->Password; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">VPI/VCI</label>
							<input class="form-control" name="vpi" value="<?= $internetdata->VPI; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Snelheid</label>
							<input class="form-control" name="speed" value="<?= $internetdata->Speed; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Netwerkadres</label>
							<input class="form-control" id="ipAddress" name="iprange" value="<?= $internetdata->IpRange; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Subnetmasker</label>
							<input class="form-control" id="SubnetMasker" name="subnetmasker" value="<?= $internetdata->SubnetMasker; ?>"/>
						</div>

						<div class="col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Default gateway</label>
							<input class="form-control" id="DefaultGateway" name="defaultgateway" value="<?= $internetdata->DefaultGateway; ?>" />
						</div>

						<div class="col-12">
							<label class="control-label">Opmerking</label>
							<textarea id="note" name="note" class="editortools" rows="10"><?= $internetdata->Note; ?></textarea>
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
	</div>
</form>

<script src="<?= base_url(); ?>assets/js/IpAddress/jquery.input-ip-address-control-1.0.min.js" type="text/javascript"></script>

<script type="text/javascript">
	var currentRow = <?= $i ?>;

	$(function () {
		$('#ipAddress').ipAddress();
		$('#SubnetMasker').ipAddress();
		$('#DefaultGateway').ipAddress();
	});


	function removeRow(i) {
		$('#row_' + i).prev().remove();
		$('#row_' + i).remove();
	}

	function addRow() {
		var before = currentRow - 1;

		var newRow = '<div class="col-10 col-sm-11 form-group label-floating is-empty">';
		newRow += '<label class="control-label">IP Adres</label>';
		newRow += '<input type="text" name="ipaddress' + currentRow + '" class="form-control" required />';
		newRow += '</div>';

		newRow += '<div class="col-2 col-sm-1 text-right insertafter" id="row_' + currentRow + '">';
		newRow += '<button href="javascript:;" type="button" onclick="removeRow(' + currentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i></button>';
		newRow += '<input name="number[]" type="hidden" readonly value="' + currentRow + '" />';
		newRow += '</div>';

		$(".insertafter:last").after(newRow);

		currentRow++;
	}
</script>

<?php include 'application/views/inc/footer.php'; ?>
