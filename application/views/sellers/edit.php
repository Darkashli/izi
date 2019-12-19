<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Verkoopkanaal');
define('PAGE', 'sellers');

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
                    <h4 class="card-title">Adresgegevens verkoopkanaal</h4>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-9 col-xs-9 form-group label-floating">
                            <label class="control-label">Naam verkoopkanaal <small>*</small></label>
                            <input value="<?= $seller->Name; ?>" class="form-control" name="name" <?= $readonly; ?> required />
                        </div>
                        <div class="col-md-3 col-xs-3 form-group label-floating">
                            <label class="control-label">Klantnummer</label>
                            <input type="number" value="<?= $seller->Client_id; ?>" class="form-control" name="ClientId" <?= $readonly; ?> />
                        </div>
                        <div class="col-md-8 col-xs-12 form-group label-floating">
                            <label class="control-label">Straat/postbus</label>
                            <input value="<?= $seller->Street; ?>" name="streetname" class="form-control" <?= $readonly; ?>/>
                        </div>
                        <div class="col-md-2 col-xs-6 form-group label-floating">
                            <label class="control-label">Huisnummer</label>
                            <input value="<?= $seller->House_number; ?>" name="streetnumber" type="number" class="form-control" <?= $readonly; ?> />
                        </div>
                        <div class="col-md-2 col-xs-6 form-group label-floating">
                            <label class="control-label">Toevoeging </label>
                            <input value="<?= $seller->Number_addition; ?>" name="streetaddition" class="form-control" <?= $readonly; ?> />
                        </div>
                        <div class="col-md-4 col-xs-5 form-group label-floating">
                            <label class="control-label">Postcode </label>
                            <input value="<?= $seller->Zip_code; ?>" name="zipcode" class="form-control" <?= $readonly; ?> maxlength="6" onchange="findStreet()" />
                        </div>
                        <div class="col-md-8 col-xs-7 form-group label-floating">
                            <label class="control-label">Woonplaats <small>*</small></label>
                            <input value="<?= $seller->City; ?>" name="city" class="form-control" <?= $readonly; ?> required />
                        </div>

                        <div class="col-md-12 col-xs-12 form-group label-floating">
                            <label class="control-label">Land</label>
                            <input value="<?= $seller->Country; ?>" name="country" class="form-control" <?= $readonly; ?> />
                        </div>

                       <div class="col-md-8 col-xs-8 form-group">
                            <label>Standaard vervoerder</label>
                            <select class="form-control" name="transport" value="Default_transport">
                                <option></option>
                                <?php foreach ($transporters as $transporter) { ?>
                                    <option value="<?= $transporter->Transporter_id?>"<?= $seller->Default_transport == $transporter->Transporter_id ? 'selected' : null ?>><?= $transporter->Name ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-4 col-xs-4 togglebutton">
                            <label class="control-label">
                                <input type="checkbox" name="onlyOption" class="d-block" <?= $seller->Only_option ? 'checked' : null ?>>
                                Deze vervoerder als enige optie
                            </label>
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
                            <input value="<?= $seller->Phone; ?>" name="phonenumber" class="form-control" <?= $readonly; ?> />
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">Faxnummer</label>
                            <input value="<?= $seller->Fax; ?>" name="fax" class="form-control" <?= $readonly; ?> />
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">E-mailadres</label>
                            <input value="<?= $seller->Mail; ?>" name="email" class="form-control" <?= $readonly; ?> />
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">Website</label>
                            <input value="<?= $seller->Website; ?>" name="website" class="form-control" <?= $readonly; ?> />
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">Facebookpagina</label>
                            <input value="<?= $seller->Facebook; ?>" name="facebook" class="form-control" <?= $readonly; ?> />
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">Twitterprofiel</label>
                            <input value="<?= $seller->Twitter; ?>" name="twitter" class="form-control" <?= $readonly; ?> />
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
                    <h4 class="card-title">Order import</h4>
                </div>
                <div class="card-body">
                    <div class="row">

                       <div class="col-12">
                            <label>Gebruik import formaat voor:</label>
                            <select class="selectpicker" name="import" data-style="btn btn-round btn-primary" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
                                <option value=""></option>
                                <option value="blokker" <?= $seller->Import == 'blokker' ? 'selected' : NULL ?> <?= in_array('blokker', $assignedImports) ? 'disabled' : NULL ?>>Blokker<?= in_array('blokker', $assignedImports) ? ' (reeds toegewezen)' : NULL ?></option>
                                <option value="bol.com" <?= $seller->Import == 'bol.com' ? 'selected' : NULL ?> <?= in_array('bol.com', $assignedImports) ? 'disabled' : NULL ?>>Bol.com<?= in_array('bol.com', $assignedImports) ? ' (reeds toegewezen)' : NULL ?></option>
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
