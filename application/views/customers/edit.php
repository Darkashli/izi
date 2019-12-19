<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Klanten');
if ($this->uri->segment(2) != 'create') {
	define('CUSTOMER', $this->uri->segment(3));
	define('SUBTITEL', getCustomerName(CUSTOMER) . ' (' . CUSTOMER . ')');
}
define('PAGE', 'customer');

define('SUBMENUPAGE', 'edit');
define('SUBMENU', $this->load->view('customers/tab', array(), true));

include 'application/views/inc/header.php';
?>

<?php if ($this->uri->segment(2) != 'create') { ?>
	<div class="row">
		<?= SUBMENU; ?>
	</div>
<?php } ?>

<form method="post" autocomplete="false">
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
							<input value="<?= $customer->Name; ?>" class="form-control" name="name" <?= $readonly; ?> required />
						</div>
						<div class="col-md-8 col-xs-12 form-group label-floating">
							<label class="control-label">Straat/postbus (Factuuradres) <small>*</small></label>
							<input value="<?= $customer->StreetName; ?>" name="streetname" class="form-control" <?= $readonly; ?> required />
						</div>
						<div class="col-md-2 col-xs-6 form-group label-floating">
							<label class="control-label">Huisnummer </label>
							<input value="<?= $customer->StreetNumber; ?>" name="streetnumber" type="number" class="form-control" <?= $readonly; ?> />
						</div>
						<div class="col-md-2 col-xs-6 form-group label-floating">
							<label class="control-label">Toevoeging </label>
							<input value="<?= $customer->StreetAddition; ?>" name="streetaddition" class="form-control" <?= $readonly; ?> />
						</div>
						<div class="col-md-4 col-xs-5 form-group label-floating">
							<label class="control-label">Postcode </label>
							<input value="<?= $customer->ZipCode; ?>" name="zipcode" class="form-control" <?= $readonly; ?> maxlength="6" />
						</div>
						<div class="col-md-8 col-xs-7 form-group label-floating">
							<label class="control-label">Woonplaats <small>*</small></label>
							<input value="<?= $customer->City; ?>" name="city" class="form-control" <?= $readonly; ?> required />
						</div>

						<div class="col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Land</label>
							<input value="<?= $customer->Country; ?>" name="country" class="form-control" <?= $readonly; ?> />
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
							<input value="<?= $customer->PhoneNumber; ?>" name="phonenumber" class="form-control" <?= $readonly; ?> />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Faxnummer</label>
							<input value="<?= $customer->Fax; ?>" name="fax" class="form-control" <?= $readonly; ?> />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">E-mailadres</label>
							<input value="<?= $customer->Email; ?>" name="email" class="form-control" <?= $readonly; ?> />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Website</label>
							<input value="<?= $customer->Website; ?>" name="website" class="form-control" <?= $readonly; ?> />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Facebookpagina</label>
							<input name="facebookPage" value="<?= $customer->FacebookPage; ?>" class="form-control" <?= $readonly; ?> />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Twitterprofiel</label>
							<input name="twitterProfile" value="<?= $customer->TwitterProfile; ?>" class="form-control" <?= $readonly; ?> />
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
							<input value="<?= $customer->VisitStreetName; ?>" name="visitstreetname" class="form-control" <?= $readonly; ?> />
						</div>
						<div class="col-md-2 col-xs-6 form-group label-floating">
							<label class="control-label">Huisnummer</label>
							<input value="<?= $customer->VisitStreetNumber; ?>" name="visitstreetnumber"  class="form-control" <?= $readonly; ?> />
						</div>
						<div class="col-md-2 col-xs-6 form-group label-floating">
							<label class="control-label">Toevoeging</label>
							<input value="<?= $customer->VisitStreetAddition; ?>" name="visitstreetaddition" class="form-control" <?= $readonly; ?> />
						</div>

						<div class="col-md-4 col-xs-5 form-group label-floating">
							<label class="control-label">Postcode</label>
							<input value="<?= $customer->VisitZipCode; ?>" name="visitzipcode" class="form-control" <?= $readonly; ?> />
						</div>
						<div class="col-md-8  col-xs-7 form-group label-floating">
							<label class="control-label">Woonplaats</label>
							<input value="<?= $customer->VisitCity; ?>" name="visitcity" class="form-control" <?= $readonly; ?> />
						</div>

						<div class="col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Land</label>
							<input value="<?= $customer->VisitCountry; ?>" name="visitcountry" class="form-control" <?= $readonly; ?> />
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
							<input value="<?= $customer->TermOfPayment; ?>" name="termofpayment" class="form-control" <?= $readonly; ?> type="number" required/>
						</div>
						<div class="col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">IBAN nummer</label>
							<input value="<?= $customer->IBAN; ?>" name="iban" class="form-control" <?= $readonly; ?> />
						</div>
						<div class="col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">BTW nummer</label>
							<input value="<?= $customer->BTW; ?>" name="btw" class="form-control" <?= $readonly; ?> />
						</div>
						<div class="col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Contactpersoon financiën</label>
							<input value="<?= $customer->ToAttention; ?>" name="toattention" class="form-control" <?= $readonly; ?>/>
						</div>
						<div class="col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">E-mailadres <small>*</small></label>
							<input value="<?= $customer->EmailFinancial; ?>" name="emailfinancial" class="form-control" <?= $readonly; ?> required />
						</div>
						<div class="col-md-6 col-sm-12 form-group label-floating">
							<label class="control-label">Telefoonnummer</label>
							<input value="<?= $customer->PhonenumberFinancial; ?>" name="phonenumberfinancial" class="form-control" <?= $readonly; ?> />
						</div>

						<div class="col-md-6 form-group">
							<label class="control-label">Facturen aan (andere klant):</label>
							<select class="form-control selectpicker" name="headcustomerid" <?php echo $readonly; ?> data-style="btn btn-link" data-live-search="true" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
								<option value=""></option>
								<?php foreach ($customers as $customersValue): ?>
									<option value="<?= $customersValue->Id ?>" <?= $customer->HeadCustomerId == $customersValue->Id ? 'selected' : null ?>><?= $customersValue->Name ?></option>
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
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">map</i>
					</div>
					<h4 class="card-title">Locatie</h4>
				</div>
				<div class="card-body">
					<?php
					if (empty($customer->VisitCity) || empty($customer->VisitStreetName) || empty($customer->VisitStreetNumber)) {
						$street = str_replace(' ', '+', $customer->StreetName) . $customer->StreetNumber . $customer->StreetAddition . ',' . $customer->City;
						if (!empty($customer->Country)) {
							$street .= ',' . $customer->Country;
						}
					} else {
						$street = str_replace(' ', '+', $customer->VisitStreetName) . $customer->VisitStreetNumber . $customer->VisitStreetAddition . ',' . $customer->VisitCity;
						if (!empty($customer->VisitCountry)) {
							$street .= ',' . $customer->VisitCountry;
						}
					}
					?>
					
					<iframe
						width="100%"
						height="226"
						frameborder="0" style="border:0"
						src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAs7rakJy6jPHmh4MYW9bLy1nvHKjEExsY&q=<?= $street; ?>" allowfullscreen>
					</iframe>
				</div>
			</div>
		</div>
	</div>

	<div class="row justify-content-center">
		<?php if ($this->session->userdata('user')->CustomerManagement == 1) { ?>
			<div class="col-md-4">
				<button class="btn btn-block btn-success" type="submit">Opslaan</button>
			</div>
		<?php } ?>
	</div>


</form>

<script type="text/javascript">

	function stopRKey(evt) {
		var evt = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type == "text")) {
			return false;
		}
	}

	document.onkeypress = stopRKey;

	// function findStreet() {
	// 
	// 	$.ajax({
	// 		url: '<?= base_url(); ?>customers/searchZip',
	// 		type: 'GET',
	// 		data: {
	// 			zip: document.getElementsByName('zipcode')[0].value,
	// 			number: document.getElementsByName('streetnumber')[0].value + document.getElementsByName('streetaddition')[0].value
	// 		},
	// 		success: function (data) {
	// 			// do something;
	// 			var result = JSON.parse(data);
	// 			document.getElementsByName('streetname')[0].value = result.Street;
	// 			document.getElementsByName('city')[0].value = result.City;
	// 		}
	// 	});
	// 
	// }

</script>

<?php include 'application/views/inc/footer.php'; ?>
