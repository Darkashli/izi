<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Software');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Software: ' . getCustomerName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
define('PAGE', 'systemmanagement');

define('SUBMENUPAGE', 'software');
define('SUBMENU', $this->load->view('systemmanagement/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success float-right" href="<?= base_url('SystemManagement/createSoftware/'.$this->uri->segment(3)) ?>">Software toevoegen</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">extension</i>
				</div>
				<h4 class="card-title">Software</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="softwareTable" width="100%">
						<thead>
							<tr>
								<td>Ontwikkelaar</td>
								<td>Productnaam</td>
								<td>Leverancier</td>
								<td>Telefoonnummer leverancier</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($softwares as $software) { ?>
								<tr data-href="<?= base_url('SystemManagement/updateSoftware/'.$software->Id) ?>">
									<td><?= $software->Developer ?></td>
									<td><?= $software->ProductName; ?></td>
									<td><?= $software->SupplierName; ?></td>
									<td><?= $software->SupplierPhonenumber; ?></td>
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
		$('#softwareTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [[0, "asc"]]
		});
	});
</script>

<?php include 'application/views/inc/footer.php'; ?>
