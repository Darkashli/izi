<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Historie');
define('PAGE', 'product');

define('SUBMENUPAGE', 'history');
define('SUBMENU', $this->load->view('product/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">local_offer</i>
				</div>
				<div class="card-title">
					<h4 class="float-left"><?= TITEL ?></h4>
					<div class="float-right">
						<form method="post">
							<div class="form-inline form-horizontal">
								<label class="control-label">Datum van</label>
								<input type="text" name="dateFrom" class="form-control date-picker" value="<?= set_value('dateFrom', date('d-m-Y', strtotime($from))) ?>">
								<label class="control-label">Datum tot</label>
								<input type="text" name="dateTo" class="form-control date-picker" value="<?= set_value('dateTo', date('d-m-Y', strtotime($to))) ?>">
								<label class="control-label">In- / verkoop</label>
								<div class="form-group">
									<select name="invoiceFilter" class="form-control">
										<option value="both" <?= set_select('invoiceFilter', 'both', true) ?>>Beide</option>
										<option value="customer" <?= set_select('invoiceFilter', 'customer', false) ?>>Verkoop</option>
										<option value="supplier" <?= set_select('invoiceFilter', 'supplier', false) ?>>Inkoop</option>
									</select>
								</div>
								<button type="submit" class="btn btn-round btn-success btn-fab btn-fab-mini">
									<i class="material-icons">filter_list</i>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="productTable" class="table table-striped table-hover" width="100%">
						<thead>
							<tr>
								<td>In- / verkoopfactuur</td>
								<td>Klant / leverancier</td>
								<td>Datum</td>
								<td>Voorraadmutatie</td>
							</tr>
						</thead>
						<?php if ($invoiceFilter != 'supplier') { ?>
							<?php foreach ($invoiceRules as $invoiceRule):
								$invoice = getInvoice($invoiceRule->InvoiceId);
								$customer = getCustomer($invoiceRule->CustomerId);
								if ($invoice == null || ($invoice->InvoiceDate <= strtotime($from) || $invoice->InvoiceDate >= strtotime($to))) {
									continue;
								}
							?>
								<tr>
									<td><?= $invoiceRule->InvoiceNumber ?></td>
									<td>
										<?php if ($customer != null) { ?>
											<?= $customer ?>
										<?php }elseif ($invoice != null) { ?>
											<?= $invoice->CompanyName ?? $invoice->FrontName.' '.$invoice->Insertion.' '.$invoice->LastName ?>
										<?php }else{ echo ''; } ?>
									</td>
										<?php if ($invoice != null) { ?>
											<td data-order="<?= date('YmdHis', $invoice->InvoiceDate) ?>">
												<?= date('d-m-Y', $invoice->InvoiceDate) ?>
											</td>
										<?php }else{ ?>
											<td></td>
										<?php } ?>
									<td>-<?= $invoiceRule->Amount ?></td>
								</tr>
							<?php endforeach; ?>
						<?php } ?>
						<?php if ($invoiceFilter != 'customer') { ?>
							<?php foreach ($InvoiceRulesSupplier as $invoiceRule):
								$invoice = getInvoiceS($invoiceRule->InvoiceId);
								$supplier = getSupplierName($invoiceRule->SupplierId);
								if ($invoice == null || ($invoice->InvoiceDate <= strtotime($from) || $invoice->InvoiceDate >= strtotime($to))) {
									continue;
								}
							?>
								<tr>
									<td><?= $invoiceRule->InvoiceNumber ?></td>
									<td>
										<?php if ($supplier != null) { ?>
											<?= $supplier ?>
										<?php }elseif ($invoice != null) { ?>
											<?= $invoice->CompanyName ?? $invoice->FrontName.' '.$invoice->Insertion.' '.$invoice->LastName ?>
										<?php }else{ echo ''; } ?>
									</td>
										<?php if ($invoice != null) { ?>
											<td data-order="<?= date('YmdHis', $invoice->InvoiceDate) ?>">
												<?= date('d-m-Y', $invoice->InvoiceDate) ?>
											</td>
										<?php }else{ ?>
											<td></td>
										<?php } ?>
									<td><?= $invoiceRule->Amount ?></td>
								</tr>
							<?php endforeach; ?>
						<?php } ?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'application/views/inc/footer.php'; ?>

<script type="text/javascript">
	$(document).ready(function () {
	    $('#productTable').DataTable({
	        "language": {
	            "url": "/assets/language/Dutch.json"
	        },
			"order": [[2, "desc"]]
	    });

	    $('input.date-picker').datetimepicker({
	        locale: 'nl-NL',
	        format: 'DD-MM-YYYY',
			icons: datetimepickerIcons
	    });
	});
</script>
