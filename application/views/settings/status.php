<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Instellingen');
define('PAGE', 'settings');

define('SUBMENUPAGE', 'statusses');
define('SUBMENU', $this->load->view('settings/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success float-right" href="<?= base_url('settings/createstatus/') ?>">Status toevoegen</a>
	</div>
</div>

<div class="row">
	<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">layers</i>
				</div>
				<h4 class="card-title">Statussen</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="statusesTable" width="100%">
						<thead>
							<tr>
								<td>&nbsp;</td>
								<td>Omschrijving</td>
								<td>Voortgangspercentage</td>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($statuses as $status) {
								?>
								<tr data-href="<?= base_url('settings/updatestatus/'.$status->Id) ?>">
									<td data-order="<?= $status->Order; ?>"><?= getStatusBlock($status->Id) ?></td>
									<td><?= $status->Description; ?></td>
									<td><?= number_format($status->ProgressPercentage, 2, ',', '.'); ?>%</td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		$('#statusesTable').DataTable({
			rowReorder: true,
			"language": {
				"url": "/assets/language/Dutch.json"
			}
		});

	});
</script>

<?php include 'application/views/inc/footer.php'; ?>
