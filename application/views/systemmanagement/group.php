<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Systeembeheer');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Groepen: ' . getCustomerName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
define('PAGE', 'systemmanagement');

define('SUBMENUPAGE', 'groups');
define('SUBMENU', $this->load->view('systemmanagement/tab', array(), true));

include 'application/views/inc/header.php';
?>
<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success float-right" href="<?= base_url('SystemManagement/createGroup/'.$this->uri->segment(3)) ?>">Groep toevoegen</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">group_work</i>
				</div>
				<h4 class="card-title">Groepen</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="groupTable" width="100%">
						<thead>
							<tr>
								<td>Naam</td>
								<td>Groepstype</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($groups as $group) { ?>
								<tr data-href="<?= base_url('SystemManagement/updateGroup/'.$group->Id) ?>">
									<td><?= $group->Name; ?></td>
									<td><?= $group->Type; ?></td>
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
		$('#groupTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [[0, "asc"]]
		});
	});
</script>

<?php include 'application/views/inc/footer.php'; ?>
