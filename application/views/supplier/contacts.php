<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Contactpersonen');
define('PAGE', 'supplier');
define('SUBTITEL', 'Contactpersonen: ' . getSupplierName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');

define('SUBMENUPAGE', 'contacts');
define('SUBMENU', $this->load->view('supplier/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>

	<div class="col-auto">
		<a class="btn btn-success float-right" href="<?= base_url('supplier/createcontact/'.$this->uri->segment(3)) ?>">Contactpersoon toevoegen</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">local_offer</i>
				</div>
				<h4 class="card-title">Contactpersonen</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="contactTable" width="100%">
						<thead>
							<tr>
								<td>Voornaam</td>
								<td>&nbsp;</td>
								<td>Achternaam</td>
								<td>E-mailadres</td>
								<td>Telefoon</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($contacts as $contact) { ?>
								<tr data-href="<?= base_url('supplier/updatecontact/'.$contact->Id) ?>">
									<td><?= $contact->FirstName; ?></td>
									<td><?= $contact->Insertion; ?></td>
									<td><?= $contact->LastName; ?></td>
									<td><?= $contact->Email; ?></td>
									<td><?= !empty($contact->PhoneNumber) ? $contact->PhoneNumber : $contact->PhoneMobile; ?></td>
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
		$('#contactTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [[2, "asc"]]
		});
	});
</script>

<?php include 'application/views/inc/footer.php'; ?>
