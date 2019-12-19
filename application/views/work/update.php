<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Ticket');
define('CUSTOMER', $customer->Id);
define('SUBTITEL', 'Ticket: ' . getCustomerName($customer->Id) . ' (' . $customer->Id . ')');
define('PAGE', 'work');

define('SUBMENUPAGE', 'edit');
define('SUBMENU', $this->load->view('work/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
</div>

<form method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">local_offer</i>
					</div>
					<h4 class="card-title">Ticket</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group label-floating">
								<label class="control-label">Korte omschrijving ticket <small>*</small></label>
								<input class="form-control" type="text" name="description" value="<?= $ticket->Description; ?>" required />
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Prioriteit</label>
								<select class="form-control" name="priority">
									<option value="1" <?= $ticket->Priority == 1 ? 'selected' : NULL ?>>Laag</option>
									<option value="2" <?= $ticket->Priority == 2 ? 'selected' : NULL ?>>Gemiddeld</option>
									<option value="3" <?= $ticket->Priority == 3 ? 'selected' : NULL ?>>Hoog</option>
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
									<option value="<?= $project->Id ?>" <?= ($ticket->PhaseId != null && $project->Id == $ticketProject->Id) ? 'selected' : null ?>><?= $project->ProjectNumber.' - '.$project->Description ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div id="project_phase_div" class="col-md-6 form-group label-floating <?= $ticket->PhaseId ? null : 'hidden' ?>">
							<label class="control-label" for="project_phase">projectfase <small>*</small></label>
							<select class="form-control" name="project_phase" id="project_phase" <?= $ticket->PhaseId ? 'required' : null ?>>
								<?php foreach ($projectPhases as $projecPhase): ?>
									<option value="<?= $projecPhase->Id ?>" <?= $projecPhase->Id == $ticket->PhaseId ? 'selected' : null ?>><?= $projecPhase->Name ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<label>Wens/melding klant</label>
							<textarea id="customerNotification" name="customerNotification" class="editortools" rows="10" ><?= $ticket->CustomerNotification; ?></textarea>
						</div>
						<div class="col-lg-6">
							<label>Interne notitie</label>
							<textarea id="internalNote" name="internalNote" class="editortools" rows="10" ><?= $ticketRule->InternalNote; ?></textarea>
						</div>
					</div>
					<div class="row">
						<div id="prognosis_div" class="col-lg-6 form-group">
							<label class="control-label">Prognose (in uren) <small class="<?= $ticket->PhaseId ? null : 'hidden' ?>">*</small></label>
							<input class="form-control" type="number" step="any" name="prognosis" value="<?= $ticket->Prognosis ?>" <?= $ticket->PhaseId ? 'required' : null ?> />
						</div>
					</div>
				</div>
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
							<table class="table table-striped table-hover" id="attachmentTable" width="100%">
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
			<button class="btn btn-block btn-success" type="submit">Opslaan</button>
		</div>
	</div>
</form>

<script type="text/javascript">

var currentRow = 2;

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
