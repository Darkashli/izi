<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Gesloten tickets');
define('PAGE', 'overviews');

include 'application/views/inc/header.php';
?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
	<div class="card">
		<div class="card-header card-header-icon card-header-primary">
			<div class="card-icon">
				<i class="material-icons">local_offer</i>
			</div>
			<h4 class="card-title">Gesloten tickets</h4>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-hover" id="closedTickets" width="100%">
					<thead>
						<tr>
							<td>Klantnaam</td>
							<td>Ticket nummer</td>
							<td>Datum</td>
							<td>Omschrijving</td>
							<td>Status</td>
						</tr>
					</thead>

					<?php
					foreach ($tickets as $ticket) {

						$lastTicketRule = getLastTicketRule($ticket->TicketId);
						$status = getStatus($lastTicketRule->Status);
						?>
						<tr data-href="<?= base_url() . 'work/update/' . $ticket->TicketId; ?>">
							<td><?= getCustomerName($ticket->CustomerId); ?></td>
							<td><?= $ticket->Number; ?></td>
							<td><?= date('d-m-Y', $lastTicketRule->Date); ?></td>
							<td><?= $ticket->Description; ?></td>
							<td><?= getStatusBlock($status->Id).' '.$status->Description ?></td>
						</tr>
					<?php } ?>

				</table>
			</div>
		</div>
	</div>
</div>


<script>
	
	$(document).ready(function () {
		$('#closedTickets').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [[2, "desc"]],
			columnDefs: [
				{type: 'date-eu', targets: 2}
			]
		});
	});
	
</script>

<?php include 'application/views/inc/footer.php'; ?>
