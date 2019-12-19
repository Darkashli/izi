<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Offertes');
define('PAGE', 'settings');
define('SUBMENUPAGE', 'quotations');
define('SUBMENU', $this->load->view('settings/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="row">
			<div class="col">
			</div>
			<div class="col-auto">
				<a class="btn btn-success float-right" href="<?= base_url('settings/createreason/') ?>">Aanleiding toevoegen</a>
			</div>
		</div>
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">dialpad</i>
				</div>

				<h4 class="card-title">Aanleidingen</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="reasonsTable" width="100%">
						<thead>
							<tr>
								<td>Omschrijving</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($reasons as $reason): ?>
								<tr data-href="<?= base_url('settings/updatereason/'.$reason->Id)?>">
									<td><?= $reason->Description ?></td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col">
			</div>
			<div class="col-auto">
				<a class="btn btn-success float-right" href="<?= base_url('settings/createQuotationStatus') ?>">Status toevoegen</a>
			</div>
		</div>
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">layers</i>
				</div>

				<h4 class="card-title">Statussen</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" width="100%">
						<thead>
							<tr>
								<td>Omschrijving</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($statusses as $status): ?>
								<tr data-href="<?= base_url('settings/updateQuotationStatus/'.$status->Id)?>">
									<td><?= $status->Description ?></td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="row">
			<div class="col">
			</div>
			<div class="col-auto">
				<a class="btn btn-success float-right" href="<?= base_url('settings/createtext/') ?>">Standaard tekst toevoegen</a>
			</div>
			<div class="col-12">
				<div class="card">
					<div class="card-header card-header-icon card-header-primary">
						<div class="card-icon">
							<i class="material-icons">code</i>
						</div>

						<h4 class="card-title">Standaard teksten</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover" id="defaulttextsTable" width="100%">
								<thead>
									<tr>
										<td>Titel</td>
										<td>Omschrijving</td>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($defaulttexts as $defaulttext): ?>
										<?php
											if (strlen($defaulttext->Text) > 65)
											{
												$name = substr($defaulttext->Text, 0, 50).'...';
											}
											else
											{
												$name = $defaulttext->Text;
											}
										?>
										<tr data-href="<?= base_url('settings/updatetext/'.$defaulttext->Id)?>">
											<td><?= $defaulttext->Titel ?></td>
											<td><?= $name ?></td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	
	$(document).ready(function () {
		
		$('#reasonsTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			}
		});

		$('#defaulttextsTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			}
		});

	});
	
</script>

<?php include 'application/views/inc/footer.php'; ?>
