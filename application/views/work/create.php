<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Tickets');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Ticket: ' . getCustomerName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
define('PAGE', 'work');

include 'application/views/inc/header.php';
?>

<form method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">local_offer</i>
					</div>
					<h4 class="card-title">Tickets</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group label-floating">
								<label class="control-label">Naam medewerker</label>
								<?= $user; ?>
							</div>
							<div class="form-group label-floating">
								<label class="control-label">Incident koppelen aan</label>
								<?= $userLink; ?>
							</div>
							<div class="form-group label-floating">
								<label class="control-label">Korte omschrijving ticket <small>*</small></label>
								<input class="form-control" type="text" name="description" required autocomplete="off" />
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group label-floating">
								<label class="control-label">Contactpersoon klant</label>
								<?= $contact; ?>
							</div>
							<div class="form-group label-floating">
								<label class="control-label">Aard van de werkzaamheden</label>
								<?= $natureOfWork; ?>
							</div>
							<div class="form-group label-floating">
								<label class="control-label">Contactmoment</label>
								<?= $contactMoment; ?>
							</div>
							<div class="form-group">
								<label>Prioriteit</label>
								<select class="form-control" name="priority">
									<option value="1">Laag</option>
									<option value="2" selected>Gemiddeld</option>
									<option value="3">Hoog</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 form-group label-floating">
							<label class="control-label" for="project">Koppelen aan project</label>
							<select class="form-control" id="project" onchange="populateProjectPhases()">
								<option></option>
								<?php foreach ($projects as $project): ?>
									<option value="<?= $project->Id ?>"><?= $project->ProjectNumber.' - '.$project->Description ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div id="project_phase_div" class="col-md-6 form-group label-floating hidden">
							<label class="control-label" for="project_phase">projectfase <small>*</small></label>
							<select class="form-control" name="project_phase" id="project_phase" required>
								<!-- To be filled dynamically -->
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<label for="customerNotification">Wens/melding klant</label>
							<textarea id="customerNotification" name="customerNotification" class="FormElement editortools" rows="10"></textarea>
						</div>
						<div class="col-lg-6">
							<label>Interne notitie</label>
							<textarea id="internalNote" name="internalNote" class="FormElement editortools" rows="10"></textarea>
						</div>
					</div>
					<div class="row">
						<div id="prognosis_div" class="col-lg-6 form-group">
							<label class="control-label">Prognose (in uren) <small class="hidden">*</small></label>
							<input class="form-control" type="number" step="any" name="prognosis" autocomplete="off" />
						</div>
					</div>
				</div>
			</div>
		</div>
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
			<button class="btn btn-block btn-success" type="submit">Opslaan</button>
		</div>
	</div>
</form>

<script type="text/javascript">
	var currentRow = 2;

	$(document).ready(function () {

		populateProjectPhases();

	});

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

	/**
	 * AJAX function to get project phases for the selected project.
	 *
	 */
	function populateProjectPhases() {
		var projectId = $('#project').val();

		if (projectId != '') {
			$('#project_phase').prop('required', true);
			$('#project_phase_div').slideDown();
			$('#prognosis_div input').prop('required', true);
			$('#prognosis_div label small').show();

			$.ajax({
				url: "<?= base_url('projects/ajax_getPhases/') ?>" + projectId
			}).done(function(msg){
				var data = JSON.parse(msg);
				var html = '<option></option>';

				if (data.error) {
					console.log(data.error);
				}
				else {
					$.each(data.projectPhases, function(phaseIndex, phase){
						html += '<option value="' + phase.Id + '">' + phase.Name + '</option>';
					});
				}

				$('#project_phase').html(html);
			});
		}
		else {
			$('#project_phase').prop('required', false);
			$('#project_phase_div').slideUp();
			$('#prognosis_div input').prop('required', false);
			$('#prognosis_div label small').hide();
		}
	}

</script>

<?php include 'application/views/inc/footer.php'; ?>
