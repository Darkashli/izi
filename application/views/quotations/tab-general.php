<div class="row">
	<div class="col-12">
		<div class="row">
			<div class="col-md-6 form-group label-floating">
				<label class="control-label">Datum van schrijven <small>*</small></label>
				<input class="form-control date-picker" type="text" name="date" value="<?= date('d-m-Y', strtotime($quotation->CreatedDate)); ?>" required />
			</div>
			<div class="col-md-6 form-group label-floating">
				<label class="control-label">Offerte gemaakt door <small>*</small></label>
				<input type="text" class="form-control" name="creator_name" value="<?= $quotation->CreatorName ?>" list="users" required>
				<datalist id="users">
					<?php foreach ($users[0] as $userId => $user): ?>
						<option value="<?= $user ?>">
					<?php endforeach; ?>
				</datalist>
			</div>
		</div>
		<div class="row">
			<?php if ($customer != null): ?>
				<div class="col-md-6 form-group label-floating">
					<label class="control-label" for="contact">Contactpersoon<?= $this->uri->segment(2) == 'create' ? ' <small>*</small>' : null ?></label>
					<select class="form-control" name="contact" id="contact" <?= $this->uri->segment(2) == 'create' ? 'required' : null ?>>
						<option></option>
						<?php foreach ($contacts as $contact): ?>
							<option value="<?= $contact->Id ?>" <?= $contact->Id == $quotation->ContactId ? 'selected' : null ?>><?= $contact->FirstName.' '.$contact->Insertion.' '.$contact->LastName ?></option>
						<?php endforeach ?>
					</select>
				</div>
			<?php endif; ?>
			<div class="col-md-6 form-group label-floating">
				<label class="control-label">Onderwerp <small>*</small></label>
				<input class="form-control" type="text" name="subject" value="<?= $quotation->Subject ?>" required />
			</div>
			<div class="col-md-6 form-group label-floating">
				<label class="control-label" for="reason">Naar aanleiding van <small>*</small></label>
				<?php if ($this->uri->segment(2) == 'update'): ?>
					<input type="text" id="reason" class="form-control" name="reason" value="<?= $quotation->Reason ?>" required>
				<?php else: ?>
					<select class="form-control" name="reason" id="reason" required>
						<option></option>
						<?php foreach ($reasons as $reason): ?>
							<option><?= $reason->Description ?></option>
						<?php endforeach ?>
					</select>
				<?php endif; ?>
			</div>
			<div class="col-md-6 form-group label-floating">
				<label class="control-label">Datum contact <small>*</small></label>
				<input class="form-control date-picker" type="text" name="dateContact" value="<?= date('d-m-Y', strtotime($quotation->ContactDate)); ?>" required />
			</div>
			<?php if (isset($templates)): ?>
				<div class="col-md-6 form-group label-floating">
					<label class="control-label">Template <small>*</small></label>
					<select class="form-control" name="template" required>
						<option></option>
						<?php foreach ($templates as $templateKey => $template): ?>
							<option value="<?= $templateKey ?>" <?= $templateKey == $quotation->Template ? 'selected' : null ?>><?= $template ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
