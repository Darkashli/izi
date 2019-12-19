<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Geïmporteerde facturen');
define('PAGE', 'webshop');

include 'application/views/inc/header.php';
?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">local_offer</i>
				</div>
				<h4 class="card-title"><?= TITEL ?></h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover w-100">
						<thead>
							<tr>
								<td>Factuurnummer</td>
								<td>Factuurdatum</td>
								<td>Totaal incl. BTW</td>
								<td>Betaald</td>
								<td>Vervaldatum</td>
								<td>Status</td>
								<td>StatusId</td>
								<td class="text-right">Actie</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($invoices as $invoice)
							{

								$paid = '0';

								$payments = getInvoiceP($invoice->Id, $invoice->BusinessId);

								foreach ($payments as $payment)
								{
									$paid += $payment->Amount;
								}
							?>
								<tr data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>">
									<td><?= $invoice->InvoiceNumber; ?></td>
									<td data-order="<?= date('YmdHis', $invoice->InvoiceDate) ?>"><?= date('d-m-Y', $invoice->InvoiceDate); ?></td>
									<td>€ <?= number_format($invoice->TotalIn, 2, ',', '') ?></td>
									<td>€ <?= number_format($paid, 2, ',', '') ?></td>
									<td><?= date('d-m-Y', $invoice->ExpirationDate); ?></td>
									<td><?= ($invoice->PaymentDate) ? 'Betaald op ' . date('d-m-Y', $invoice->PaymentDate) : round((strtotime(date('d-m-Y')) - strtotime(date('d-m-Y', $invoice->ExpirationDate))) / 60 / 60 / 24) . ' dagen vervallen'; ?></td>
									<td><?= ($invoice->PaymentDate) ? '1' : '5' ?></td>
									<td class="td-actions text-right">
										<a href="<?= base_url(); ?>invoices/editInvoice/<?= $invoice->Id; ?>" class="btn btn-info btn-round btn-fab btn-fab-mini <?= ($invoice->PaymentDate) ? 'disabled' : ''; ?>" title="Aanpassen">
											<i class="material-icons">edit</i>
										</a>
										<a href="<?= base_url("invoices/setToRegular/$invoice->Id") ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Zet om naar vaste klant">
											<i class="material-icons">people</i>
										</a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'application/views/inc/footer.php'; ?>
