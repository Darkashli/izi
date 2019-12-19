<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Systeembeheer');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Shares & Rechten: ' . getCustomerName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
define('PAGE', 'systemmanagement');

define('SUBMENUPAGE', 'shares');
define('SUBMENU', $this->load->view('systemmanagement/tab', array(), true));

include 'application/views/inc/header.php';
?>
<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success float-right" href="<?= base_url('SystemManagement/createShare/'.$this->uri->segment(3)) ?>">Share toevoegen</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">share</i>
				</div>
				<h4 class="card-title">Shares & Rechten</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="shareTable" width="100%">
						<thead>
							<tr>
								<td>Schijfletter</td>
								<td>Netwerknaam</td>
								<td>Locatie server</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($shares as $share) { ?>
								<tr data-href="<?= base_url('SystemManagement/updateShare/'.$share->Id) ?>">
									<td><?= $share->DriveLetter; ?></td>
									<td><?= $share->NetworkName; ?></td>
									<td><?= $share->LocationServer; ?></td>
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
		$('#shareTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [[0, "asc"]]
		});
	});
</script>

<?php include 'application/views/inc/footer.php'; ?>
