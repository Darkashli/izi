<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Leverancier');
if ($this->uri->segment(2) != 'create') {
    define('SUBTITEL', getSupplierName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
}
define('PAGE', 'supplier');

define('SUBMENUPAGE', 'edit');
define('SUBMENU', $this->load->view('supplier/tab', array(), true));

include 'application/views/inc/header.php';
?>

<?php if ($this->uri->segment(2) != 'create') { ?>
    <div class="row">
        <?= SUBMENU; ?>
    </div>
<?php } ?>

<form method="post">
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
                            <label class="control-label">Bedrijfsnaam <small>*</small></label>
                            <input value="<?= $supplier->Name; ?>" class="form-control" name="name" <?= $readonly; ?> required />
                        </div>
                        <div class="col-md-8 col-xs-12 form-group label-floating">
                            <label class="control-label">Straat/postbus (Factuuradres) <small>*</small></label>
                            <input value="<?= $supplier->StreetName; ?>" name="streetname" class="form-control" <?= $readonly; ?> required />
                        </div>
                        <div class="col-md-2 col-xs-6 form-group label-floating">
                            <label class="control-label">Huisnummer </label>
                            <input value="<?= $supplier->StreetNumber; ?>" name="streetnumber" type="number" class="form-control" <?= $readonly; ?> />
                        </div>
                        <div class="col-md-2 col-xs-6 form-group label-floating">
                            <label class="control-label">Toevoeging </label>
                            <input value="<?= $supplier->StreetAddition; ?>" name="streetaddition" class="form-control" <?= $readonly; ?> />
                        </div>
                        <div class="col-md-4 col-xs-5 form-group label-floating">
                            <label class="control-label">Postcode </label>
                            <input value="<?= $supplier->ZipCode; ?>" name="zipcode" class="form-control" <?= $readonly; ?> maxlength="6" onchange="findStreet()" />
                        </div>
                        <div class="col-md-8 col-xs-7 form-group label-floating">
                            <label class="control-label">Woonplaats <small>*</small></label>
                            <input value="<?= $supplier->City; ?>" name="city" class="form-control" <?= $readonly; ?> required />
                        </div>

                        <div class="col-md-12 col-xs-12 form-group label-floating">
                            <label class="control-label">Land</label>
                            <input value="<?= $supplier->Country; ?>" name="country" class="form-control" <?= $readonly; ?> />
                        </div>
                    </div>
                </div>
            </div>
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
                            <input value="<?= $supplier->PhoneNumber; ?>" name="phonenumber" class="form-control" <?= $readonly; ?> />
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">Faxnummer</label>
                            <input value="<?= $supplier->Fax; ?>" name="fax" class="form-control" <?= $readonly; ?> />
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">E-mailadres</label>
                            <input value="<?= $supplier->Email; ?>" name="email" class="form-control" <?= $readonly; ?> />
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">Website</label>
                            <input value="<?= $supplier->Website; ?>" name="website" class="form-control" <?= $readonly; ?> />
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">euro_symbol</i>
					</div>
                    <h4 class="card-title">Financiële gegevens</h4>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Betalingsconditie</label>
                            <select class="form-control" name="paymentcondition">
								<?php foreach ($paymentConditions as $paymentCondition): ?>
									<option value="<?= $paymentCondition->Name ?>" <?= $paymentCondition->Name == $supplier->PaymentCondition ? 'selected' : NULL ?>><?= $paymentCondition->Name ?></option>
								<?php endforeach; ?>
							</select>
                        </div>
                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">Termijn (dagen) <small>*</small></label>
                            <input value="<?= $supplier->TermOfPayment; ?>" name="termofpayment" class="form-control" <?= $readonly; ?> type="number" required/>
                        </div>
                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">IBAN nummer</label>
                            <input value="<?= $supplier->IBAN; ?>" name="iban" class="form-control" <?= $readonly; ?> />
                        </div>
                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">BTW nummer</label>
                            <input value="<?= $supplier->BTW; ?>" name="btw" class="form-control" <?= $readonly; ?> />
                        </div>
                    </div>
                </div>
            </div>

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
                            <textarea id="note" name="note" class="w100 form-control" ><?= $supplier->Note; ?></textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header card-header-icon card-header-primary">
            <div class="card-icon">
                <i class="material-icons">map</i>
            </div>
            <h4 class="card-title">Locatie</h4>
        </div>
        <div class="card-body">
            <?php
            if (empty($supplier->VisitCity) || empty($supplier->VisitStreetName) || empty($supplier->VisitStreetNumber)) {
                $street = str_replace(' ', '+', $supplier->StreetName) . $supplier->StreetNumber . $supplier->StreetAddition . ',' . $supplier->City;
                if (!empty($supplier->Country)) {
                    $street .= ',' . $supplier->Country;
                }
            } else {
                $street = str_replace(' ', '+', $supplier->VisitStreetName) . $supplier->VisitStreetNumber . $supplier->VisitStreetAddition . ',' . $supplier->VisitCity;
                if (!empty($supplier->VisitCountry)) {
                    $street .= ',' . $supplier->VisitCountry;
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
    <?php if ($this->session->userdata('user')->CustomerManagement == 1) { ?>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <button class="btn btn-block btn-success" type="submit">Opslaan</button>
            </div>
        </div>
    <?php } ?>
</form>


<?php include 'application/views/inc/footer.php'; ?>
