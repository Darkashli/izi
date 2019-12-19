<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Website onderhoud');
define('PAGE', 'website');

include 'application/views/inc/header.php';

$i = 1;
?>

<form method="post" action="<?= base_url(); ?>Website/<?= $type; ?>/<?= $this->uri->segment(3); ?>">
	<div class="row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">devices</i>
					</div>
					<h4 class="card-title">Websites</h4>
				</div>
				<div class="card-body">

					<div class="row">
						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Domeinnaam</label>
							<input type="text" name="domainname" class="form-control" value="<?= $website->DomainName; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">IP adres</label>
							<input type="text" name="ipaddress" class="form-control" value="<?= $website->IpAddress; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Provider</label>
							<input type="text" name="provider" class="form-control" value="<?= $website->Provider; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Hostingpartij</label>
							<input type="text" name="hosting" class="form-control" value="<?= $website->Hosting; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Hosting gebruikersnaam</label>
							<input type="text" name="hostingusername" class="form-control" value="<?= $website->HostingUsername; ?>" />
						</div>
						
						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Hosting wachtwoord</label>
							<input type="text" name="hostingpassword" class="form-control" value="<?= $website->HostingPassword; ?>" />
						</div>
						
						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Nameserver 1</label>
							<input type="text" name="nameserver1" class="form-control" value="<?= $website->NameServer1; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Nameserver 2</label>
							<input type="text" name="nameserver2" class="form-control" value="<?= $website->NameServer2; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">FTP host</label>
							<input type="text" name="ftphost" class="form-control" value="<?= $website->FTPHost; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">FTP poort</label>
							<input type="number" name="ftpport" class="form-control" value="<?= $website->FTPPort; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">FTP gebruikersnaam</label>
							<input type="text" name="ftpusername" class="form-control" value="<?= $website->FTPUsername; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">FTP wachtwoord</label>
							<input type="text" name="ftppassword" class="form-control" value="<?= $website->FTPPassword; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Content Management Systeem</label>
							<input type="text" name="cms" class="form-control" value="<?= $website->CMS; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Versie</label>
							<input type="text" name="cmsversion" class="form-control" value="<?= $website->CMSVersion; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Updates via Installatron</label>
							<?= $updatesinstallatron; ?>
						</div>

						<div class="col-md-6 input-group">
							<div class="form-group label-floating">
								<label class="control-label">Datum laatste handmatige updates</label>
								<input class="form-control date-picker" type="text" name="latestupdate" value="<?= date('d-m-Y', $website->LatestUpdate); ?>" />
							</div>
							<div class="input-group-append">
								<span class="input-group-text">
									<i class="material-icons">date_range</i>
								</span>
							</div>
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Toegevoegd aan Google Analytics</label>
							<?= $googleAnalytics; ?>
						</div>
						
						<div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Toegevoegd aan Google Search Console</label>
							<?= $googleSearch; ?>
						</div>

						
						<div class="col-12">
							<h5>Extensies</h5>
							<div class="row align-items-center">
								<?php
								if ($website->Extentions != null) {
									foreach (unserialize($website->Extentions) as $extention) {
										?>
										<div class="col-12 col-sm-3 form-group label-floating">
											<label class="control-label">Naam extensie</label>
											<input type="text" name="name<?= $i; ?>" class="form-control" value="<?= $extention->Name; ?>" />
										</div>

										<div class="col-12 col-sm-4 form-group label-floating">
											<label class="control-label">Soort extensie</label>
											<?= form_dropdown('kindextention' . $i, unserialize(KINDEXTENTION), $extention->Kind, CLASSDROPDOWN); ?>
										</div>

										<div class="col-10 col-sm-4 form-group label-floating">
											<label class="control-label">Versie</label>
											<input type="numer" name="version<?= $i; ?>" class="form-control" value="<?= $extention->Version; ?>" />
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
									<div class="col-12 col-sm-3 form-group label-floating">
										<label class="control-label">Naam extensie</label>
										<input type="text" name="name1" class="form-control" />
									</div>

									<div class="col-12 col-sm-4 form-group label-floating">
										<label class="control-label">Soort extensie</label>
										<?= form_dropdown('kindextention1', unserialize(KINDEXTENTION), '', CLASSDROPDOWN); ?>
									</div>

									<div class="col-10 col-sm-4 form-group label-floating">
										<label class="control-label">Versie</label>
										<input type="numer" name="version1" class="form-control" />
									</div>

									<div class="col-2 col-sm-1 text-right insertafter" id="row_1">
										<button href="javascript:;" type="button" onclick="addRow()" class="btn btn-success btn-round btn-fab btn-fab-mini"><i class="material-icons">add</i></button>
										<input name="number[]" type="hidden" readonly value="1" />
									</div>

								<?php $i++; }
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row justify-content-center">
		<?php if ($type == 'edit') { ?>
			<div class="col-md-4">
				<a href="<?= base_url(); ?>Website/delete/<?= $this->uri->segment(3); ?>" class="btn btn-danger btn-block">Verwijderen</a>
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
		$('.date-picker').datetimepicker({
			locale: 'nl-NL',
			format: 'L',
			icons: datetimepickerIcons
		});
	});

	function removeRow(i) {
		$('#row_' + i).prev().remove();
		$('#row_' + i).prev().remove();
		$('#row_' + i).prev().remove(); // (There are 3 divs that need to be removed).
		$('#row_' + i).remove();
	}

	function addRow() {
		var newRow = '<div class="col-12 col-sm-3 form-group label-floating is-empty">';
		newRow += '<label class="control-label">Naam extensie</label>';
		newRow += '<input type="text" name="name' + currentRow + '" class="form-control" required />';
		newRow += '</div>';

		newRow += '<div class="col-12 col-sm-4 form-group">';
		newRow += '<label>Soort extensie</label>';
		newRow += '<select name="kindextention' + currentRow + '" class="form-control">';
		newRow += '<option value="1">Component</option>';
		newRow += '<option value="2">Module</option>';
		newRow += '<option value="3">Plugin</option>';
		newRow += '<option value="4">Widget</option>';
		newRow += '<option value="5">Overig</option>';
		newRow += '<option value="6">Pakket</option>';
		newRow += '</select>';
		newRow += '</div>';

		newRow += '<div class="col-10 col-sm-4 form-group label-floating is-empty">';
		newRow += '<label class="control-label">Versie</label>';
		newRow += '<input type="numer" name="version' + currentRow + '" class="form-control" />';
		newRow += '</div>';

		newRow += '<div class="col-2 col-sm-1 text-right insertafter" id="row_' + currentRow + '">';
		newRow += '<button href="javascript:;" type="button" onclick="removeRow(' + currentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i></button>';
		newRow += '<input name="number[]" type="hidden" readonly value="' + currentRow + '" />';
		newRow += '</div>';

		$(".insertafter:last").after(newRow);

		currentRow++;
	}
</script>
