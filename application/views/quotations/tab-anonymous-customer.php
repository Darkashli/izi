<div class="row">
	<div class="col-12 form-group label-floating">
		<label class="control-label">Klantnaam</label>
		<input type="text" name="company_name" class="form-control" value="<?= $quotation->CustomerName ?>" />
	</div>
	<div class="col-md-4 form-group label-floating">
		<label class="control-label">Voornaam</label>
		<input type="text" name="front_name" class="form-control" value="<?= $quotation->ContactFirstName ?>" />
	</div>
	<div class="col-md-4 form-group label-floating">
		<label class="control-label">Tussenvoegsel</label>
		<input name="insertion" class="form-control" value="<?= $quotation->ContactInsertion; ?>" />
	</div>
	<div class="col-md-4 form-group label-floating">
		<label class="control-label">Achternaam</label>
		<input type="text" name="last_name" class="form-control" value="<?= $quotation->ContactLastName ?>" />
	</div>
	<div class="col-md-6 form-group label-floating">
		<label class="control-label" for="sex">Geslacht</label>
		<select class="form-control" name="sex" id="sex">
			<option></option>
			<option value="male" <?= $quotation->ContactSex == 'male' ? 'selected' : null ?>>Mannelijk</option>
			<option value="female" <?= $quotation->ContactSex == 'female' ? 'selected' : null ?>>Vrouwelijk</option>
		</select>
	</div>
	<div class="col-md-6 form-group label-floating">
		<label class="control-label" for="salutation">Aanhef <small>*</small></label>
		<select class="form-control" name="salutation" id="salutation" required>
			<option></option>
			<option value="formal" <?= $quotation->ContactSalutation == 'formal' ? 'selected' : null ?>>formeel</option>
			<option value="informal" <?= $quotation->ContactSalutation == 'informal' ? 'selected' : null ?>>informeel</option>
			<option value="combined" <?= $quotation->ContactSalutation == 'combined' ? 'selected' : null ?>>combinatie</option>
		</select>
	</div>
	<div class="col-md-6 form-group label-floating">
		<label class="control-label">Straat / Postbus</label>
		<input type="text" name="address" class="form-control" value="<?= $quotation->CustomerStreet ?>" />
	</div>
	<div class="col-md-3 form-group label-floating">
		<label class="control-label">Huisnummer</label>
		<input type="number" name="address_number" class="form-control" value="<?= $quotation->CustomerHouseNumber ?>" />
	</div>
	<div class="col-md-3 form-group label-floating">
		<label class="control-label">Toevoeging</label>
		<input type="text" name="address_addition" class="form-control" value="<?= $quotation->CustomerHouseNumberAddition ?>" />
	</div>
	<div class="col-lg-3 col-md-6 form-group label-floating">
		<label class="control-label">Postcode</label>
		<input type="text" name="zip_code" class="form-control" value="<?= $quotation->CustomerZipCode ?>" />
	</div>
	<div class="col-lg-5 col-md-6 form-group label-floating">
		<label class="control-label">Woonplaats</label>
		<input type="text" name="city" class="form-control" value="<?= $quotation->CustomerCity ?>" />
	</div>
	<div class="col-lg-4 form-group label-floating">
		<label class="control-label">Land</label>
		<input type="text" name="country" class="form-control" value="<?= $quotation->CustomerCountry ?>" />
	</div>
	<div class="col-12 form-group label-floating">
		<label class="control-label">E-mailadres <small>*</small></label>
		<input type="text" name="email" class="form-control" value="<?= $quotation->CustomerMailaddress ?>" required />
	</div>
</div>
