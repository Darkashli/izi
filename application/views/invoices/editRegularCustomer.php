<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Omzetten naar vaste klant');
define('PAGE', 'invoice');

include 'application/views/inc/header.php';
?>

<form method="post" autocomplete="false">
	<!-- Customer -->
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">subject</i>
					</div>
					<h4 class="card-title">Factuurgegevens</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Klantnaam <small>*</small></label>
							<input value="<?= $customer->Name; ?>" class="form-control" name="name" required />
						</div>
						<div class="col-md-8 col-xs-12 form-group label-floating">
							<label class="control-label">Straat/postbus (Factuuradres)</label>
							<input value="<?= $customer->StreetName; ?>" name="streetname" class="form-control" required />
						</div>
						<div class="col-md-2 col-xs-6 form-group label-floating">
							<label class="control-label">Huisnummer </label>
							<input value="<?= $customer->StreetNumber; ?>" name="streetnumber" type="number" class="form-control" />
						</div>
						<div class="col-md-2 col-xs-6 form-group label-floating">
							<label class="control-label">Toevoeging </label>
							<input value="<?= $customer->StreetAddition; ?>" name="streetaddition" class="form-control" />
						</div>
						<div class="col-md-4 col-xs-5 form-group label-floating">
							<label class="control-label">Postcode </label>
							<input value="<?= $customer->ZipCode; ?>" name="zipcode" class="form-control" maxlength="6" />
						</div>
						<div class="col-md-8 col-xs-7 form-group label-floating">
							<label class="control-label">Woonplaats <small>*</small></label>
							<input value="<?= $customer->City; ?>" name="city" class="form-control" required />
						</div>
						<div class="col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Land</label>
							<input value="<?= $customer->Country; ?>" name="country" class="form-control" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">forum</i>
					</div>
					<h4 class="card-title">Communicatie</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Telefoonnummer</label>
							<input value="<?= $customer->PhoneNumber; ?>" name="phonenumber" class="form-control" />
						</div>
						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Faxnummer</label>
							<input value="<?= $customer->Fax; ?>" name="fax" class="form-control" />
						</div>
						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">E-mailadres</label>
							<input value="<?= $customer->Email; ?>" name="email" class="form-control" />
						</div>
						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Website</label>
							<input value="<?= $customer->Website; ?>" name="website" class="form-control" />
						</div>
						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Facebookpagina</label>
							<input name="facebookPage" value="<?= $customer->FacebookPage; ?>" class="form-control" />
						</div>
						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Twitterprofiel</label>
							<input name="twitterProfile" value="<?= $customer->TwitterProfile; ?>" class="form-control" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">place</i>
					</div>
					<h4 class="card-title">Bezoekadres</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-8 col-xs-12 form-group label-floating">
							<label class="control-label">Straat/postbus (Bezoekadres)</label>
							<input value="<?= $customer->VisitStreetName; ?>" name="visitstreetname" class="form-control" />
						</div>
						<div class="col-md-2 col-xs-6 form-group label-floating">
							<label class="control-label">Huisnummer</label>
							<input value="<?= $customer->VisitStreetNumber; ?>" name="visitstreetnumber"  class="form-control" />
						</div>
						<div class="col-md-2 col-xs-6 form-group label-floating">
							<label class="control-label">Toevoeging</label>
							<input value="<?= $customer->VisitStreetAddition; ?>" name="visitstreetaddition" class="form-control" />
						</div>
						<div class="col-md-4 col-xs-5 form-group label-floating">
							<label class="control-label">Postcode</label>
							<input value="<?= $customer->VisitZipCode; ?>" name="visitzipcode" class="form-control" />
						</div>
						<div class="col-md-8  col-xs-7 form-group label-floating">
							<label class="control-label">Woonplaats</label>
							<input value="<?= $customer->VisitCity; ?>" name="visitcity" class="form-control" />
						</div>
						<div class="col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Land</label>
							<input value="<?= $customer->VisitCountry; ?>" name="visitcountry" class="form-control" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">euro_symbol</i>
					</div>
					<h4 class="card-title">Financiële gegevens</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6 col-sm-12 form-group">
							<label>Betalingsconditie <small>*</small></label>
							<select class="form-control" name="paymentcondition" required>
								<?php foreach ($paymentConditions as $paymentCondition): ?>
									<option value="<?= $paymentCondition->Name ?>" <?= $paymentCondition->Name == $customer->PaymentCondition ? 'selected' : NULL ?>><?= $paymentCondition->Name ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Termijn (dagen) <small>*</small></label>
							<input value="<?= $customer->TermOfPayment; ?>" name="termofpayment" class="form-control" type="number" required/>
						</div>
						<div class="col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">IBAN nummer</label>
							<input value="<?= $customer->IBAN; ?>" name="iban" class="form-control" />
						</div>
						<div class="col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">BTW nummer</label>
							<input value="<?= $customer->BTW; ?>" name="btw" class="form-control" />
						</div>
						<div class="col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Contactpersoon financiën</label>
							<input value="<?= $customer->ToAttention; ?>" name="toattention" class="form-control"/>
						</div>
						<div class="col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">E-mailadres <small>*</small></label>
							<input value="<?= $customer->EmailFinancial; ?>" name="emailfinancial" class="form-control" required />
						</div>
						<div class="col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Telefoonnummer</label>
							<input value="<?= $customer->PhonenumberFinancial; ?>" name="phonenumberfinancial" class="form-control" />
						</div>
						<div class="col-md-6 form-group">
							<label class="control-label">Facturen aan (andere klant):</label>
							<select class="form-control selectpicker" name="headcustomerid" data-style="btn btn-link" data-live-search="true" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
								<option value=""></option>
								<?php foreach ($customers as $customersValue): ?>
									<option value="<?= $customersValue->Id ?>"><?= $customersValue->Name ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">subject</i>
					</div>
					<h4 class="card-title">Notities</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Interne notitie</label>
							<textarea id="note" name="note" class="w100 form-control" ><?= $customer->Note; ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Contact -->
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">person</i>
					</div>
					<h4 class="card-title">Contactpersoon</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-8 col-sm-6 form-group label-floating">
							<label class="control-label">Voornaam <small>*</small></label>
							<input name="firstname" class="form-control" required value="<?= $contact->FirstName; ?>" />
						</div>
						<div class="col-md-4 col-sm-6 form-group label-floating">
							<label class="control-label">Tussenvoegsel</label>
							<input name="insertion" class="form-control" value="<?= $contact->Insertion; ?>" />
						</div>
						<div class="col-md-12 col-sm-12 form-group label-floating">
							<label class="control-label">Achternaam <small>*</small></label>
							<input name="lastname" class="form-control" required value="<?= $contact->LastName; ?>" />
						</div>
						<div class="col-md-6 form-group label-floating">
							<label class="control-label" for="sex">Geslacht</label>
							<select class="form-control" name="sex" id="sex">
								<option></option>
								<option value="male" <?= $contact->Sex == 'male' ? 'selected' : null ?>>Mannelijk</option>
								<option value="female" <?= $contact->Sex == 'female' ? 'selected' : null ?>>Vrouwelijk</option>
							</select>
						</div>
						<div class="col-md-6 form-group label-floating">
							<label class="control-label" for="salutation">Aanhef <small>*</small></label>
							<select class="form-control" name="salutation" id="salutation" required>
								<option></option>
								<option value="formal" <?= $contact->Salutation == 'formal' ? 'selected' : null ?>>formeel</option>
								<option value="informal" <?= $contact->Salutation == 'informal' ? 'selected' : null ?>>informeel</option>
								<option value="combined" <?= $contact->Salutation == 'combined' ? 'selected' : null ?>>combinatie</option>
							</select>
						</div>
						<div class="col-md-6 col-sm-6 form-group label-floating">
							<label class="control-label">E-mail adres <small>*</small></label>
							<input name="email" class="form-control" required value="<?= $contact->Email; ?>" />
						</div>
						<div class="col-md-6 col-sm-6 form-group label-floating">
							<label class="control-label">Telefoonnummer</label>
							<input name="phonenumber" class="form-control" value="<?= $contact->PhoneNumber; ?>" />
						</div>
						<div class="col-md-6 col-sm-6 form-group label-floating">
							<label class="control-label">Mobiel</label>
							<input name="phonemobile" class="form-control" value="<?= $contact->PhoneMobile; ?>" />
						</div>
						<div class="col-md-6 col-sm-6 form-group label-floating">
							<label class="control-label">Functie</label>
							<input name="function" class="form-control" value="<?= $contact->Function; ?>" />
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="togglebutton">
								<label>
									<input type="hidden" name="employed" value="0" />
									<input type="checkbox" name="employed" <?= $contact->Employed ? 'checked' : ''; ?> value="1"> Medewerker in dienst
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row justify-content-center">
		<div class="col-md-4">
			<button class="btn btn-block btn-success" type="submit">Opslaan</button>
		</div>
	</div>

</form>

<?php include 'application/views/inc/footer.php'; ?>
