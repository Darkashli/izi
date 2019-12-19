<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Voortgang');
define('CUSTOMER', $customer->Id);
define('SUBTITEL', 'Ticket: ' . getCustomerName($customer->Id) . ' (' . $customer->Id . ')');
define('PAGE', 'work');

define('SUBMENUPAGE', 'progress');
define('SUBMENU', $this->load->view('work/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success float-right" href="<?= base_url('customers/work/'.$customer->Id) ?>">Alle tickets</a>
		<a class="btn btn-success float-right" href="<?= base_url('work/createticketrule/'.$this->uri->segment(3)) ?>">Update toevoegen</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">local_offer</i>
				</div>
				<h4 class="card-title">Voortgang</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover">
						<?php foreach ($ticketRules as $ticketRule): ?>
							<tr data-href="<?= base_url('work/updateticketrule/'.$ticketRule->Id) ?>">
								<td><?= getStatusBlock($ticketRule->Status).' '.getStatus($ticketRule->Status)->Description ?></td>
								<td><?= date('d-m-Y', $ticketRule->Date); ?></td>
								<td><?= $ticketRule->TotalWork; ?></td>
								<td><?= getAccountName($ticketRule->UserIdLink); ?></td>
								<td><?= trim_text($ticketRule->ActionUser); ?></td>
							</tr>
						<?php endforeach; ?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'application/views/inc/footer.php'; ?>
