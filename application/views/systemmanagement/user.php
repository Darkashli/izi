<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Systeembeheer');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Gebruikers: ' . getCustomerName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
define('PAGE', 'systemmanagement');

define('SUBMENUPAGE', 'users');
define('SUBMENU', $this->load->view('systemmanagement/tab', array(), true));

include 'application/views/inc/header.php';
?>
<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success float-right" href="<?= base_url('SystemManagement/createUser/'.$this->uri->segment(3)) ?>">Gebruiker toevoegen</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">group</i>
				</div>
				<h4 class="card-title">Gebruikers</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="userTable" width="100%">
						<thead>
							<tr>
								<td>Voornaam</td>
								<td>&nbsp;</td>
								<td>Achternaam</td>
								<td>E-mail</td>
								<td>Gebruikersnaam</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($users as $user) { ?>
								<tr data-href="<?= base_url('SystemManagement/updateUser/'.$user->Id) ?>">
									<td><?= $user->FirstName; ?></td>
									<td><?= $user->Insertion; ?></td>
									<td><?= $user->LastName; ?></td>
									<td><?= $user->Email; ?></td>
									<td><?= $user->Username; ?></td>
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
		$('#userTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [[0, "asc"]]
		});
	});
</script>

<?php include 'application/views/inc/footer.php'; ?>
