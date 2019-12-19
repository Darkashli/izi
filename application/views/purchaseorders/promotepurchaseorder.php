<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Inkooporder omzetten');
define('PAGE', 'purchaseorder');

include 'application/views/inc/header.php';?>

<form method="post" action="<?= base_url('PurchaseOrders/promotepurchaseorder/'.$this->uri->segment(3)) ?>">
	<div class="card">
		<div class="card-header card-header-icon card-header-primary">
			<div class="card-icon">
				<i class="material-icons">local_offer</i>
			</div>
			<h4 class="card-title">Inkooporder omzetten</h4>
		</div>
		<div class="card-body">
			<div class="row justify-content-center">
				<div class="col-12 form-group label-floating">
					<label class="control-label">Factuurnummer <small>*</small></label>
					<input name="invoice_number" class="form-control" type="text" required/>
				</div>
			</div>
			<?php if ($purchaseOrder->SupplierId == null) { ?>
				<div class="row">
					<div class="col-md-6 form-group">
						<label>Betalingsconditie</label>
						<select class="form-control" name="paymentcondition">
							<?php foreach ($paymentConditions as $paymentCondition): ?>
								<option value="<?= $paymentCondition->Name ?>" <?= $paymentCondition->Name == $purchaseOrder->PaymentCondition ? 'selected' : NULL ?>><?= $paymentCondition->Name ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-6 col-sm-12 form-group label-floating">
						<label class="control-label">Termijn (dagen) <small>*</small></label>
						<input name="termofpayment" class="form-control" type="number" required/>
					</div>
				</div>
			<?php } ?>
			<div class="row justify-content-center">
				<div class="col-md-4">
					<button type="submit" class="btn btn-success btn-block">Opslaan en sluiten</button>
				</div>
			</div>
		</div>
	</div>
</form>

<?php include 'application/views/inc/footer.php';?>
