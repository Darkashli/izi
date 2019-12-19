<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Verkoopkanaal');
define('PAGE', 'transporters');

include 'application/views/inc/header.php';
?>

<form method="post">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">subject</i>
					</div>
                    <h4 class="card-title">Adresgegevens Vervoerder</h4>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-9 col-xs-9 form-group label-floating">
                            <label class="control-label">Naam Vervoerder <small>*</small></label>
                            <input value="<?= $transporter->Name; ?>" class="form-control" name="name" <?= $readonly; ?> required />
                        </div>
                        <div class="col-md-3 col-xs-3 form-group label-floating">
                            <label class="control-label">Klantnummer</label>
                            <input type="number" value="<?= $transporter->Client_id; ?>" class="form-control" name="ClientId" <?= $readonly; ?> />
                        </div>
                        <div class="col-md-8 col-xs-12 form-group label-floating">
                            <label class="control-label">Straat/postbus</label>
                            <input value="<?= $transporter->Street; ?>" name="streetname" class="form-control" <?= $readonly; ?>/>
                        </div>
                        <div class="col-md-2 col-xs-6 form-group label-floating">
                            <label class="control-label">Huisnummer</label>
                            <input value="<?= $transporter->House_number; ?>" name="streetnumber" type="number" class="form-control" <?= $readonly; ?> />
                        </div>
                        <div class="col-md-2 col-xs-6 form-group label-floating">
                            <label class="control-label">Toevoeging </label>
                            <input value="<?= $transporter->Number_addition; ?>" name="streetaddition" class="form-control" <?= $readonly; ?> />
                        </div>
                        <div class="col-md-4 col-xs-5 form-group label-floating">
                            <label class="control-label">Postcode </label>
                            <input value="<?= $transporter->Zip_code; ?>" name="zipcode" class="form-control" <?= $readonly; ?> maxlength="6" onchange="findStreet()" />
                        </div>
                        <div class="col-md-8 col-xs-7 form-group label-floating">
                            <label class="control-label">Woonplaats <small>*</small></label>
                            <input value="<?= $transporter->City; ?>" name="city" class="form-control" <?= $readonly; ?> required />
                        </div>

                        <div class="col-md-12 col-xs-12 form-group label-floating">
                            <label class="control-label">Land</label>
                            <input value="<?= $transporter->Country; ?>" name="country" class="form-control" <?= $readonly; ?> />
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
                            <input value="<?= $transporter->Phone; ?>" name="phonenumber" class="form-control" <?= $readonly; ?> />
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">Faxnummer</label>
                            <input value="<?= $transporter->Fax; ?>" name="fax" class="form-control" <?= $readonly; ?> />
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">E-mailadres</label>
                            <input value="<?= $transporter->Mail; ?>" name="email" class="form-control" <?= $readonly; ?> />
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">Website</label>
                            <input value="<?= $transporter->Website; ?>" name="website" class="form-control" <?= $readonly; ?> />
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">Facebookpagina</label>
                            <input value="<?= $transporter->Facebook; ?>" name="facebook" class="form-control" <?= $readonly; ?> />
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">Twitterprofiel</label>
                            <input value="<?= $transporter->Twitter; ?>" name="twitter" class="form-control" <?= $readonly; ?> />
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">

            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">import_export</i>
					</div>
                    <h4 class="card-title">Verkooprder import</h4>
                </div>
                <div class="card-body">
                    <div class="row">

                       <div class="col-12">
                            <label for="imports">Gebruik vervoerder voor import van:</label>
                            <select class="selectpicker" data-style="btn btn-primary btn-round" name="imports[]" id="imports" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
                                <option value="blokker" <?= in_array('blokker', $transporterImportNames) ? 'selected' : NULL ?> <?= in_array('blokker', $assignedImportIds) ? 'disabled' : NULL ?>>Blokker<?= in_array('blokker', $assignedImportIds) ? ' (reeds toegewezen)' : NULL ?></option>
                                <option value="bol.com" <?= in_array('bol.com', $transporterImportNames) ? 'selected' : NULL ?> <?= in_array('bol.com', $assignedImportIds) ? 'disabled' : NULL ?>>Bol.com<?= in_array('bol.com', $assignedImportIds) ? ' (reeds toegewezen)' : NULL ?></option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

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
