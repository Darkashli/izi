<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Website onderhoud');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Website onderhoud: ' . getCustomerName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
define('PAGE', 'website');

include 'application/views/inc/header.php';
?>
<div class="row">
	<div class="col-md-3 ml-auto text-right">
		<a class="btn btn-success" href="<?= base_url('Website/create/'.$this->uri->segment(3)) ?>">Website toevoegen</a>
	</div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
	<div class="card">
		<div class="card-header card-header-icon card-header-primary">
			<div class="card-icon">
				<i class="material-icons">devices</i>
			</div>
			<h4 class="card-title">Website onderhoud</h4>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-hover" id="websiteTable" width="100%">
					<thead>
						<tr>
							<td>Domeinnaam</td>
							<td>Hosting</td>
							<td>Content Management Systeem</td>
							<td>Laatste updates</td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($websites as $website) { ?>
							<tr data-href="<?= base_url('Website/edit/'.$website->Id) ?>">
								<td><?= $website->DomainName; ?></td>
								<td><?= $website->Hosting; ?></td>
								<td><?= $website->CMS; ?></td>
								<td><?= date('d-m-Y', $website->LatestUpdate); ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		$('#websiteTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			}
		});
	});
</script>

<?php include 'application/views/inc/footer.php'; ?>
