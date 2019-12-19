<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Domeinnamen');
define('PAGE', 'domain');

include 'application/views/inc/header.php';
?>


<div class="row">
	<div class="col-auto ml-auto">
		<a class="btn btn-success" href="<?= base_url('domains/create/') ?>">Domeinnaam toevoegen</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">dvr</i>
				</div>
				<h4 class="card-title">Domeinnamen</h4>
			</div>
			<div class="card-body">
				<div class="notification-bar alert" style="display: none;"></div>
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="domainTable" width="100%">
						<thead>
							<tr>
								<td>Domeinnaam</td>
								<td>Klant</td>
								<td>Reseller</td>
								<td>Registratiedatum</td>
								<td>Eerstvolgende factuur</td>
								<td>Hosting?</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($domains as $domain) {
								$invoiceDate = getLatestInvoiceDate($domain->Id);
							?>
								<tr data-href="<?= base_url('domains/update/'.$domain->Id) ?>">
									<td><?= $domain->Name; ?></td>
									<td><?= $domain->Customer != 0 ? getCustomer($domain->Customer) : null ?></td>
									<td><?= $domain->Reseller != 0 ? getCustomer($domain->Reseller) : null ?></td>
									<td data-order="<?= date('Ymd', strtotime($domain->RegisterDate)) ?>"><?= date('d-m-Y', strtotime($domain->RegisterDate)); ?></td>
									<td data-order="<?= $invoiceDate != null ? date('Ymd', $invoiceDate) : '0' ?>"><?= $invoiceDate != null ? date('d-m-Y', $invoiceDate) : null ?></td>
									<td><?= $domain->HasHosting == 1 ? 'Ja' : 'Nee' ?></td>
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
		
		$('#domainTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [[3, "desc"]]
		});
		
	});
	
</script>

<?php include 'application/views/inc/footer.php'; ?>
