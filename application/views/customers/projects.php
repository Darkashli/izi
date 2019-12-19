<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Projecten');
define('PAGE', 'projects');

include 'application/views/inc/header.php';
?>

<div class="row">
	<div class="col-auto ml-auto">
		<a class="btn btn-success" href="<?= base_url("projects/create/$customer->Id") ?>">Project toevoegen</a>
	</div>
</div>

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
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="projectTable" width="100%">
						<thead>
							<tr>
								<td>Project nr.</td>
								<td>Omschrijving</td>
								<td>Project betreft</td>
								<td>&nbsp;</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($projects as $project): ?>
								<tr data-href="<?= base_url("projects/overview/$project->Id") ?>">
									<td><?= $project->ProjectNumber ?></td>
									<td><?= $project->Description ?></td>
									<td><?= getNatureOfWork($project->NatureOfWorkId) ?></td>
									<td class="td-actions text-center"><a href="<?= base_url("projects/update/$project->Id") ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Aanpassen"><i class="material-icons">edit</i></a></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

$(document).ready(function () {
	
	var table = $('#projectTable').DataTable({
		"language": {
			"url": "/assets/language/Dutch.json"
		},
		columnDefs: [
			{
				"orderable": false,
				"targets": [3]
			}
		],
		"order": [[0, "desc"]]
	});
	
});

</script>

<?php include 'application/views/inc/footer.php'; ?>
