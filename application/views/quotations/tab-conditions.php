<div class="row">
	<div class="col-4 form-group label-floating">
		<label class="control-label">Geldigheid offerte (in dagen) <small>*</small></label>
		<input class="form-control" type="number" name="validity" value="<?= $quotation->ValidDays ?>" required>
	</div>
	<div class="col-4 form-group label-floating">
		<label class="control-label">Levertijd</label>
		<input class="form-control" type="text" name="time" value="<?= $quotation->DeliveryTime ?>">
	</div>
	<div class="col-2 form-group label-floating">
		<label class="control-label" for="payment">Facturatie <small>*</small></label>
		<select class="form-control" name="payment" id="payment" required>
			<option></option>
			<?php foreach ($paymentConditions as $paymentCondition): ?>
				<option value="<?= $paymentCondition->Id ?>" <?= $paymentCondition->Id == $quotation->PaymentConditionId ? 'selected' : null ?>><?= $paymentCondition->Name ?></option>
			<?php endforeach ?>
		</select>
	</div>
	<div class="col-2 form-group label-floating">
		<label class="control-label">Betaaltermijn (dagen)</label>
		<input class="form-control" type="number" name="paymentTerm" value="<?= $quotation->PaymentTerm ?>">
	</div>
</div>
<?php if (!empty($customFields)): ?>
	<h5>Extra velden</h5>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<td>Veld</td>
					<td>Waarde</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($customFields as $customField): ?>
					<tr>
						<td><?= getCustomFieldName($customField->Key) ?></td>
						<td><?= formatCustomFieldValue($customField->Key, $customField->Value) ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif; ?>
