<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Projecten');
define('PAGE', 'projects');

include 'application/views/inc/header.php';
?>

<form method="post">
	
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">graphic_eq</i>
					</div>
					<h4 class="card-title">Projecten</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group label-floating">
								<label class="control-label">Korte omschrijving <small>*</small></label>
								<input type="text" class="form-control" name="description" value="<?= $project->Description ?>" required>
							</div>
							<div class="form-group label-floating">
								<label class="control-label">Aard van de werkzaamheden</label>
								<?= $natureOfWork; ?>
							</div>
						</div>
						<div class="col-md-6">
							<label>Uitgebreide omschrijving</label>
							<textarea class="editortools" name="long_description" rows="15"><?= $project->LongDescription ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">flag</i>
					</div>
					<h4 class="card-title">Project fasen</h4>
				</div>
				<div class="card-body">
					<div class="row mt-4">
						<div class="col-12">
							<table class="table table-borderless" id="tablePhase">
								<thead>
									<tr>
										<td>Naam</td>
										<td>&nbsp;</td>
									</tr>
								</thead>
								<tbody>
									<?php if ($projectPhases == null): $i = 2; ?>
										<tr id="phaseRow1">
											<td><input type="text" class="form-control" name="phase_name1" id="phase_name1" /><input name="phases[]" type="hidden" readonly value="1" /></td>
											<td class="td-action text-right">
												<button type="button" rel="tooltip" onclick="addRow()" class="btn btn-success btn-round btn-fab btn-fab-mini">
													<i class="material-icons">add</i>
												</button>
											</td>
										</tr>
									<?php else: $i = 1; ?>
										<?php foreach ($projectPhases as $projectPhase): ?>
											<tr id="phaseRow<?= $i ?>">
												<td><input type="text" class="form-control" name="phase_name<?= $i ?>" id="phase_name<?= $i ?>" value="<?= $projectPhase->Name ?>" /><input name="phases[]" type="hidden" readonly value="<?= $i ?>" /></td>
												<td class="td-action text-right">
													<button type="button" rel="tooltip" onclick="removeRow(<?= $i ?>)" class="btn btn-danger btn-round btn-fab btn-fab-mini">
														<i class="material-icons">close</i>
													</button>
													<input type="hidden" name="phase_id<?= $i ?>" value="<?= $projectPhase->Id ?>">
												</td>
											</tr>
										<?php $i++; endforeach; ?>
									<?php endif; ?>
								</tbody>
								<tfoot>
									<td>&nbsp;</td>
									<td class="td-action text-right">
										<button type="button" rel="tooltip" onclick="addRow()" class="btn btn-success btn-round btn-fab btn-fab-mini">
											<i class="material-icons">add</i>
										</button>
									</td>
								</tfoot>
							</table>
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
	
	var currentRow = <?= $i ?>;
	
	function addRow() {
		var myRow = '<tr id="phaseRow' + currentRow + '">';
		myRow += '<td><input type="text" class="form-control" name="phase_name' + currentRow + '" id="phase_name' + currentRow + '" /><input name="phases[]" type="hidden" readonly value="' + currentRow + '" /></td>';
		myRow += '<td class="td-actions text-right"><button type="button" onclick="removeRow(' + currentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i><div class="ripple-container"></div></button></td>';
		myRow += '</tr>';
		$("#tablePhase tbody").append(myRow);
		currentRow++
	}
	
	function removeRow(i) {
		$('#phaseRow' + i).remove();
	}
	
</script>

<?php include 'application/views/inc/footer.php'; ?>
