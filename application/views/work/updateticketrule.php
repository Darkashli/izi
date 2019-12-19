<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Update toevoegen');
define('PAGE', 'work');

include 'application/views/inc/header.php';
?>
<form method="post" action="<?= base_url(); ?>work/<?= $this->uri->segment(2); ?>/<?= $this->uri->segment(3); ?>" enctype="multipart/form-data">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">local_offer</i>
					</div>
					<h4 class="card-title">Update toevoegen</h4>
				</div>
				<div class="card-body">

					<div class="row">
						<div class="col-lg-6 col-md-12 col-sm-12 form-group label-floating">
							<label class="control-label">Naam medewerker</label>
							<?= $user; ?>
						</div>

						<div class="col-lg-6 col-md-12 col-sm-12 form-group label-floating">
							<label class="control-label">Contactpersoon klant</label>
							<?= $contact; ?>
						</div>

						<div class="col-lg-6 col-md-12 col-sm-12 form-group label-floating">
							<label class="control-label">Ticket koppelen aan</label>
							<?= $userLink; ?>
						</div>

						<div class="col-lg-6 col-md-12 col-sm-12 form-group label-floating">
							<label class="control-label">Aard van uitgevoerde werkzaamheden</label>
							<?= $natureOfWork; ?>
						</div>

						<div class="col-lg-6 col-md-12 col-sm-12 form-group label-floating">
							<label class="control-label">Hoe is het contact onstaan</label>
							<?= $contactMoment; ?>
						</div>

						<div class="col-lg-6 col-md-12 col-sm-12 form-group label-floating">
							<label class="control-label">Status</label>
							<?= $status; ?>
						</div>

						<div class="col-md-6">
							<label>Actie medewerker</label>
							<textarea id="actionUser" name="actionUser" class="editortools" rows="10"><?= $ticketrule->ActionUser; ?></textarea>
						</div>
						
						<div class="col-md-6">
							<label>Interne notitie</label>
							<textarea id="internalNote" name="internalNote" class="editortools" rows="10" ><?= $ticketrule->InternalNote; ?></textarea>
						</div>
						
					</div>

					<div class="row">
						<div class="col-sm-6 col-md-3 input-group">
							<div class="form-group label-floating">
								<label class="control-label">Datum <small>*</small></label>
								<input class="form-control date-picker" type="text" name="date" value="<?= date('d-m-Y', $ticketrule->Date); ?>" />
							</div>
							<div class="input-group-append">
								<span class="input-group-text">
									<i class="material-icons">date_range</i>
								</span>
							</div>
						</div>
						<div class="col-sm-6 col-md-3 input-group">
							<div class="form-group label-floating">
								<label class="control-label">Begintijd <small>*</small></label>
								<input class="form-control time-picker" id="timepicker" type="text" name="startwork" value="<?= date('H:i', $ticketrule->StartWork); ?>" onchange="calculateTotal()" />
							</div>
							<div class="input-group-append">
								<span class="input-group-text">
									<i class="fas fa-clock-o"></i>
								</span>
							</div>
						</div>
						<div class="col-sm-6 col-md-3 input-group">
							<div class="form-group label-floating">
								<label class="control-label">Eindtijd <small>*</small></label>
								<input class="form-control time-picker" id="timepicker" type="text" name="endwork" value="<?= date('H:i', $ticketrule->EndWork); ?>" onchange="calculateTotal()"/>
							</div>
							<div class="input-group-append">
								<span class="input-group-text">
									<i class="fas fa-clock-o icon-time"></i>
								</span>
							</div>
						</div>

						<div class="col-md-1 col-lg-1 col-sm-2 form-group label-floating">
							<label class="control-label">Totaal</label>
							<input name="totalWork" readonly class="form-control" value="0:00" />
						</div>

					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">notifications</i>
					</div>
					<h4 class="card-title">Klant melding</h4>
				</div>
				<div class="card-body"><?= $ticketbase->CustomerNotification; ?></div>
			</div>
		</div>
		<?php if ($attachments != NULL) { ?>
			<div class="col-12">
				<div class="card">
					<div class="card-header card-header-icon card-header-primary">
						<div class="card-icon">
							<i class="material-icons">cloud_download</i>
						</div>
						<h4 class="card-title">Bijlagen</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover w-100" id="attachmentTable">
								<thead>
									<tr>
										<td>Weergavenaam</td>
										<td>Bestandsnaam</td>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($attachments as $attachment) { ?>
										<tr data-href="<?= base_url("uploads/$business->DirectoryPrefix/tickets/T$attachment->TicketId/$attachment->Name") ?>" data-target="blank">
											<td><?= $attachment->DisplayName ?></td>
											<td><?= $attachment->Name ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">cloud_upload</i>
					</div>
					<h4 class="card-title">Bijlage toevoegen</h4>
				</div>
				<div class="card-body">
					<div id="rij1" class="row align-items-center uploadrow">
						<div class="col-md-6 form-group label-floating">
							<label class="control-label">Omschrijving</label>
							<input id="FileDescription1" name="FileDescription[]" class="form-control">
						</div>
						<div class="col-8 col-md-5 dragdrop">
							<input name="upload[]" id="upload1" type="file" />
						</div>
						<div class="col-4 col-md-1 text-center">
							<button type="button" onclick="addRow()" class="btn btn-success btn-round btn-fab btn-fab-mini">
								<i class="material-icons">add</i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-md-4">
			<button class="btn btn-block btn-success" type="submit">Opslaan en sluiten</button>
		</div>
		<?php if ($this->uri->segment(2) == 'updateticketrule') : ?>
			<div class="col-md-4">
				<a href="<?= base_url(); ?>work/removeticketrule/<?= $ticketrule->Id; ?>" class="btn btn-block btn-danger" id="removeRuleButton">Verwijderen</a>
			</div>
		<?php endif; ?>
	</div>
</form>

<?php include 'application/views/inc/footer.php'; ?>

<script type="text/javascript">

	var currentRow = 2;
	var lastRow = currentRow - 1;
	var nextRow = currentRow + 1;

	function removeRow(i) {
		$('#rij' + i).remove();
	}

	function addRow(){
		var myRow = '<div id="rij' + currentRow + '" class="row align-items-center uploadrow">';
		myRow += '<div class="col-md-6 form-group label-floating is-empty">';
		myRow += '<label class="control-label">Omschrijving</label>';
		myRow += '<input id="FileDescription' + currentRow + '" name="FileDescription[]" class="form-control">';
		myRow += '</div>';
		myRow += '<div class="col-8 col-md-5 dragdrop">';
		myRow += '<input name="upload[]" id="upload' + currentRow + '" type="file" />';
		myRow += '</div>';
		myRow += '<div class="col-4 col-md-1 text-center">';
		myRow += '<button type="button" onclick="removeRow(' + currentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini">';
		myRow += '<i class="material-icons">remove</i>';
		myRow += '</button>';
		myRow += '</div>';
		myRow += '</div>';
		
		$("div.uploadrow:last").after(myRow);
		initDragdrop($('#rij' + currentRow + ' .dragdrop'));
		
		currentRow ++;
	}

	var secondsPerMinute = 60;
	var minutesPerHour = 60;

	function convertSecondsToHHMMSS(intSecondsToConvert) {
		var hours = convertHours(intSecondsToConvert);
		var minutes = getRemainingMinutes(intSecondsToConvert);
		minutes = (minutes == 60) ? "00" : minutes;
		var seconds = getRemainingSeconds(intSecondsToConvert);
		return hours + ":" + minutes;
	}

	function convertHours(intSeconds) {
		var minutes = convertMinutes(intSeconds);
		var hours = Math.floor(minutes / minutesPerHour);
		return hours;
	}
	function convertMinutes(intSeconds) {
		return Math.floor(intSeconds / secondsPerMinute);
	}
	function getRemainingSeconds(intTotalSeconds) {
		return (intTotalSeconds % secondsPerMinute);
	}
	function getRemainingMinutes(intSeconds) {
		var intTotalMinutes = convertMinutes(intSeconds);
		return (intTotalMinutes % minutesPerHour);
	}

	function HMStoSec1(T) { // h:m:s
		var A = T.split(/\D+/);
		return (A[0] * 60 + +A[1]) * 60 + +A[2]
	}

	function calculateTotal() {

		totaal = 0.0;

		if (document.getElementsByName('startwork')[0].value > "") {
			totaal = parseFloat(document.getElementsByName('endwork')[0].value - document.getElementsByName('startwork')[0].value).toFixed(2);

			//document.factuur.aantal0.value * document.factuur.prijs0.value;
		}

		if (document.getElementsByName('endwork')[0].value > "") {
			totaal = parseFloat(document.getElementsByName('endwork')[0].value - document.getElementsByName('startwork')[0].value).toFixed(2);
		}

		var e = (document.getElementsByName('endwork')[0].value);
		var s = (document.getElementsByName('startwork')[0].value);

		var time1 = HMStoSec1(e + ":00");
		var time2 = HMStoSec1(s + ":00");
		var diff = (time1 - time2);

		var tijd = convertSecondsToHHMMSS(diff);
		var data = tijd.split(":");
		var minuut = ((data[1] / 60)).toString();

		var minuutr = 00;
		if (minuut == 0) {

		} else if (minuut < 0.1) {
			console.log("minuut " + minuut);
			var m = parseFloat(minuut).toFixed(2).split(".");
			minuutr = m[1];
		} else {
			console.log("Hoger " + minuut);
			minuutr = (minuut * 100).toPrecision(2);
		}

		document.getElementsByName('totalWork')[0].value = data[0] + "." + minuutr;

	}

	$(document).ready(function () {


		$('.time-picker').on("dp.change", function (e) {
			calculateTotal();
		});

		$('.date-picker').datetimepicker({
			locale: 'nl',
			format: 'L',
			useCurrent: false,
			widgetPositioning: {
				vertical: 'top'
			},
			icons: datetimepickerIcons
		});

		$('.time-picker').datetimepicker({
			locale: 'nl',
			format: 'LT',
			widgetPositioning: {
				vertical: 'top'
			},
			icons: datetimepickerIcons
		});

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

		calculateTotal();
		
		$('#attachmentTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"

			},
		});
		
	});
</script>
