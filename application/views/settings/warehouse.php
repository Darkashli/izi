<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Instellingen');
define('PAGE', 'settings');

define('SUBMENUPAGE', 'warehouses');
define('SUBMENU', $this->load->view('settings/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success float-right" href="<?= base_url('settings/createwarehouse/') ?>">Magazijn toevoegen</a>
	</div>
</div>

<div class="row">
	<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">layers</i>
				</div>
				<h4 class="card-title">Magazijnen</h4>
			</div>
			<div class="card-body">
				
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="warehouseTable" width="100%">
						<thead>
							<tr>
								<td>Naam</td>
								<td>Omschrijving</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($warehouses as $warehouse) { ?>
								<tr data-href="<?= base_url('settings/updatewarehouse/'.$warehouse->Id) ?>">
									<td><?= $warehouse->Name; ?></td>
									<td><?= $warehouse->Description; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				
			</div>
		</div>
	</div>
</div>


<script>
	$(document).ready(function () {
		$('#warehouseTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			}
		});
	});
</script>

<?php include 'application/views/inc/footer.php'; ?>
