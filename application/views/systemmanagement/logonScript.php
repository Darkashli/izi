<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Systeembeheer');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Login scripts: ' . getCustomerName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
define('PAGE', 'systemmanagement');

define('SUBMENUPAGE', 'login');
define('SUBMENU', $this->load->view('systemmanagement/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success float-right" href="<?= base_url('SystemManagement/createLogonScript/'.$this->uri->segment(3)) ?>">Login script toevoegen</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">description</i>
				</div>
				<h4 class="card-title">Login scripts</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="logonTable"  width="100%">
						<thead>
							<tr>
								<td>Bestandsnaam</td>
								<td>Netwerknaam</td>
								<td>Locatie server</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($logons as $logon) { ?>
								<tr data-href="<?= base_url('SystemManagement/updateLogonScript/'.$logon->Id) ?>">
									<td><?= $logon->FileName; ?></td>
									<td><?= $logon->NetworkName; ?></td>
									<td><?= $logon->LocationServer; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('#logonTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"

			},
			"order": [[0, "asc"]]
		});
	});
</script>

<?php include 'application/views/inc/footer.php'; ?>
