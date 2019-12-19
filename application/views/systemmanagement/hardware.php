<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Systeembeheer');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Hardware: ' . getCustomerName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
define('PAGE', 'systemmanagement');

define('SUBMENUPAGE', 'hardware');
define('SUBMENU', $this->load->view('systemmanagement/tab', array(), true));
include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success float-right" href="<?= base_url('SystemManagement/createHardware/'.$this->uri->segment(3)) ?>">Apparaat toevoegen</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">desktop_windows</i>
				</div>
				<h4 class="card-title">Hardware</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="hardwareTable" width="100%">
						<thead>
							<tr>
								<td>Soort</td>
								<td>Merk</td>
								<td>Type</td>
								<td>IP adres</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($hardwares as $hardware) { ?>
								<tr data-href="<?= base_url('SystemManagement/updateHardware/'.$hardware->Id) ?>">
									<td><?= getKindHardware($hardware->Kind); ?></td>
									<td><?= $hardware->Brand; ?></td>
									<td><?= $hardware->Type; ?></td>
									<td><?= $hardware->IpAddress; ?></td>
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
		$('#hardwareTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [[0, "asc"]]
		});
	});
</script>

<?php include 'application/views/inc/footer.php'; ?>
