<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Tickets');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Tickets: ' . getCustomerName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
define('PAGE', 'customer');
define('SUBMENUPAGE', 'work');
define('SUBMENU', $this->load->view('customers/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
	<div class="col-auto">
		<a class="btn btn-success" href="<?= base_url(); ?>work/create/<?= $this->uri->segment(3); ?>">Ticket toevoegen</a>
	</div>
</div>

<form method="post">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">local_offer</i>
					</div>
					<div class="card-title">
						<h4 class="float-left">Tickets</h4>
						<div class="float-right">
							<select name="statusFilter" class="selectpicker" data-style="btn btn-info btn-round" data-none-selected-text="<?= MAKEYOURCHOISE ?>" id="statusFilter">
								<option value="open" <?= $ticketStatus == 'open' ? 'selected' : null ?>>Geopend</option>
								<option value="closed" <?= $ticketStatus == 'closed' ? 'selected' : null ?>>Gesloten</option>
							</select>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="ticketTable" width="100%">
							<thead>
								<tr>
									<td>
										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" class="form-check-input check" id="checkAll">
												<span class="form-check-sign">
													<span class="check"></span>
												</span>
											</label>
										</div>
									</td>
									<td>Ticket nr.</td>
									<td>Datum</td>
									<td>Omschrijving</td>
									<td>Prioriteit</td>
									<td>Contactpersoon</td>
									<td>Laatst gekoppeld aan</td>
									<td>Status</td>
									<td>Factuur</td>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($tickets as $ticket) {

									$status = getStatus($ticket->Status);

									if ($ticketStatus == 'closed' && $ticket->Status != $latestStatus->Id) {
										continue;
									}
									if ($ticketStatus == 'open' && $ticket->Status == $latestStatus->Id) {
										continue;
									}
									?>
									<tr>
										<td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="checkbox" class="form-check-input check" name="ticket[]" value="<?= $ticket->TicketId; ?>" <?= ($ticket->TicketStatus == 0) ? '' : 'disabled'; ?>/>
													<span class="form-check-sign">
														<span class="check"></span>
													</span>
												</label>
											</div>
										</td>
										<td data-href="<?= base_url() . 'work/update/' . $ticket->TicketId; ?>"><?= $ticket->Number; ?></td>
										<td data-href="<?= base_url() . 'work/update/' . $ticket->TicketId; ?>" data-order="<?= date('YmdHis', $ticket->Date) ?>"><?= date('d-m-Y', $ticket->Date); ?></td>
										<td data-href="<?= base_url() . 'work/update/' . $ticket->TicketId; ?>"><?= $ticket->Description; ?></td>
										<td data-href="<?= base_url() . 'work/update/' . $ticket->TicketId; ?>" data-order="<?= $ticket->Priority ?>"><?= getPriority($ticket->Priority) ?></td>
										<td data-href="<?= base_url() . 'work/update/' . $ticket->TicketId; ?>"><?= getContactName($ticket->ContactId); ?></td>
										<td data-href="<?= base_url() . 'work/update/' . $ticket->TicketId; ?>"><?= getAccountName($ticket->UserIdLink); ?></td>
										<td data-href="<?= base_url() . 'work/update/' . $ticket->TicketId; ?>"><?= getStatusBlock($status->Id).' '.$status->Description ?></td>
										<td data-href="<?= base_url() . 'work/update/' . $ticket->TicketId; ?>"><?php if ($ticket->TicketStatus == 1): ?>Ja<?php elseif ($ticket->TicketStatus == 2): ?>Gearchiveerd<?php else: ?>Nee<?php endif; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-md-4">
			<button name="invoice" class="btn btn-success btn-block" type="submit">Maak factuur</button>
		</div>
		<div class="col-md-4">
			<button name="archive" class="btn btn-success btn-block" type="submit">Plaats in archief</button>
		</div>
	</div>
</form>

<script type="text/javascript">
	
	$(document).ready(function () {
		$.fn.dataTable.moment('DD-MM-YYYY');

		var table = $('#ticketTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [
				[4, "desc"],
				[2, "desc"]
			],
			"columnDefs": [
				{
					"orderable": false,
					"targets": 0
				}
			]
		});

		$("#checkAll").click(function () {
			$(".check:not(:disabled)").prop('checked', $(this).prop('checked'));
		});
	});
	
	$("#statusFilter").on("changed.bs.select", function(e, clickedIndex, isSelected, previousValue){
		var ticketStatus = $(this).val();
		document.cookie = "customers/work/ticketStatus="+ticketStatus;
		$("form").submit();
		location.reload();
	});

</script>

<?php include 'application/views/inc/footer.php'; ?>
