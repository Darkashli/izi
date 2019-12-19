<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Instellingen');
define('PAGE', 'settings');

define('SUBMENUPAGE', 'settings');
define('SUBMENU', $this->load->view('settings/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
    <?= SUBMENU; ?>
</div>

<form method="post" enctype="multipart/form-data">
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
                            <input class="form-control" name="name" value="<?= $business->Name; ?>" required />
                        </div>
                        <div class="col-md-8 col-xs-12 form-group label-floating">
                            <label class="control-label">Straat/postbus <small>*</small></label>
                            <input class="form-control" name="streetname" value="<?= $business->StreetName; ?>" required />
                        </div>
                        <div class="col-md-2 col-xs-6 form-group label-floating">
                            <label class="control-label">Huisnummer <small>*</small></label>
                            <input class="form-control" name="streetnumber" value="<?= $business->StreetNumber; ?>" type="number" required />
                        </div>
                        <div class="col-md-2 col-xs-6 form-group label-floating">
                            <label class="control-label">Toevoeging </label>
                            <input class="form-control" value="<?= $business->StreetAddition; ?>" name="streetaddition" />
                        </div>
                        <div class="col-md-4 col-xs-5 form-group label-floating">
                            <label class="control-label">Postcode <small>*</small></label>
                            <input class="form-control" value="<?= $business->ZipCode; ?>" name="zipcode" required />
                        </div>
                        <div class="col-md-8 col-xs-7 form-group label-floating">
                            <label class="control-label">Woonplaats <small>*</small></label>
                            <input class="form-control" value="<?= $business->City; ?>" name="city" required />
                        </div>
                        <div class="col-md-12 col-xs-12 form-group label-floating">
                            <label class="control-label">Land</label>
                            <input class="form-control" value="<?= $business->Country; ?>" name="country" />
                        </div>
                        <div class="col-12">
                            <ul class="list-group">
                                <li class="list-group-item active">Rechten / modules</li>
                                <li class="list-group-item form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="module_tickets" <?= $business->ModuleTickets == 0 ? null : 'checked' ?>>
                                        Tickets
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </li>
                                <li class="list-group-item form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="module_websites" <?= $business->ModuleWebsite == 0 ? null : 'checked' ?>>
                                        Websites
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </li>
                                <li class="list-group-item form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="module_systemmanagement" <?= $business->ModuleSystemManagement == 0 ? null : 'checked' ?>>
                                        Systeembeheer
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </li>
                                <li class="list-group-item form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="module_transporters" <?= $business->ModuleTransporters == 0 ? null : 'checked' ?>>
                                        Vervoerders
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </li>
                                <li class="list-group-item form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="module_sellers" <?= $business->ModuleSellers == 0 ? null : 'checked' ?>>
                                        Verkoopkanalen
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </li>
                                <li class="list-group-item form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="module_priceagreement" <?= $business->ModulePriceAgreement == 0 ? null : 'checked' ?>>
                                        Prijsafspraken
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </li>
                                <li class="list-group-item form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="module_repeatinginvoices" <?= $business->ModuleRepeatingInvoice == 0 ? null : 'checked' ?>>
                                        Periodieke facturen
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </li>
                                <li class="list-group-item form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="module_quotation" <?= $business->ModuleQuotation == 0 ? null : 'checked' ?>>
                                        Offertes
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">subject</i>
                    </div>
                    <h4 class="card-title">Administratieve gegevens</h4>
                </div>
                <div class="card-body">
                    <div class="col-md-12 col-xs-12">
                        <label>Factuurtekst</label>
                        <textarea id="invoiceTekst" class="editortools" name="invoicetext" rows="15"><?= $business->InvoiceText; ?></textarea>
                    </div>

                    <div class="col-md-12 col-xs-12">
                        <label>Factuurtekst (kopie)</label>
                        <textarea id="invoiceCopyText" class="editortools" name="invoicecopytext" rows="15"><?= $business->InvoiceCopyText; ?></textarea>
                    </div>

                    <div class="col-md-12 col-xs-12">
                        <label>Herinneringtekst</label>
                        <textarea id="reminderText" class="editortools" name="remindertext" rows="15"><?= $business->ReminderText; ?></textarea>
                    </div>

                    <div class="col-md-12 col-xs-12">
                        <label>Aanmaningtekst</label>
                        <textarea id="dunningText" class="editortools" name="dunningtext" rows="15"><?= $business->DunningText; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">subject</i>
                    </div>
                    <h4 class="card-title">Offerte gegevens</h4>
                </div>
                <div class="card-body">
                    <div class="col-md-12 col-xs-12">
                        <label>Offerte tekst</label>
                        <textarea id="quotationEmailTekst" class="editortools" name="quotationemailtext" rows="15"><?= $business->QuotationEmailText; ?></textarea>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12 col-xs-12">
                        <label>offerte bevestiging emailtekst customer</label>
                        <textarea id="signConfirmationForCustomer" class="editortools" name="signconfirmationforcustomer" rows="15"><?= $business->SignConfirmationForCustomer; ?></textarea>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12 col-xs-12">
                        <label>offerte bevestiging emailtekst collaborator</label>
                        <textarea id="signConfirmationForCollaborator" class="editortools" name="signconfirmationforcollaborator" rows="15"><?= $business->SignConfirmationForCollaborator; ?></textarea>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12 col-xs-12">
                        <label>offerte bevestiging na het controlleren van de behandelaar</label>
                        <textarea id="OfferConfirmed" class="editortools" name="offerconfirmed" rows="15"><?= $business->OfferConfirmed; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">euro_symbol</i>
                    </div>
                    <h4 class="card-title">Gebruiker e-mails</h4>
                </div>
                <div class="card-body">

                    <div class="col-12">
                        <label>Tekst e-mail gebruiker (Nieuwe gebruiker)</label>
                        <textarea id="NewUserMailText" class="editortools" name="NewUserMailText" rows="15"><?= $business->NewUserMailText; ?></textarea>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
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
                            <label class="control-label">Telefoonnummer <small>*</small></label>
                            <input class="form-control" value="<?= $business->PhoneNumber; ?>" name="phonenumber" required/>
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">Faxnummer</label>
                            <input class="form-control" value="<?= $business->Fax; ?>" name="fax" />
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">E-mailadres <small>*</small></label>
                            <input class="form-control" value="<?= $business->Email; ?>" name="email" required/>
                        </div>

                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">Website <small>*</small></label>
                            <input class="form-control" value="<?= $business->Website; ?>" name="website" required/>
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
                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">IBAN nummer <small>*</small></label>
                            <input class="form-control" value="<?= $business->IBAN; ?>" name="iban" required />
                        </div>
                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">BTW nummer</label>
                            <input class="form-control" value="<?= $business->BTW; ?>" name="btw" />
                        </div>
                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">KVK nummer</label>
                            <input class="form-control" value="<?= $business->KVK; ?>" name="kvk" />
                        </div>
                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">BIC</label>
                            <input class="form-control" value="<?= $business->BIC; ?>" name="bic" />
                        </div>
                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">G-rekening nummer</label>
                            <input class="form-control" value="<?= $business->GREK; ?>" name="grek" />
                        </div>
                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">Factuur verzenden vanaf e-mail <small>*</small></label>
                            <input class="form-control" value="<?= $business->InvoiceEmail; ?>" name="invoiceemail" required />
                        </div>
                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">E-mailadres kopie factuur</label>
                            <input class="form-control" value="<?= $business->InvoiceCopyEmail; ?>" name="invoicecopyemail" />
                        </div>
                        <div class="col-md-6 col-xs-12 form-group label-floating">
                            <label class="control-label">E-mailadres ticket</label>
                            <input type="email" class="form-control" name="workEmail" value="<?= $business->WorkEmail; ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">euro_symbol</i>
                    </div>
                    <h4 class="card-title">Ticketgegevens</h4>
                </div>
                <div class="card-body">

                    <div class="col-12">
                        <label>Tekst e-mail ticket (Nieuw collega)</label>
                        <textarea id="workemailtextbc" class="editortools" name="workemailtextbc" rows="15"><?= $business->WorkEmailTextBC; ?></textarea>
                    </div>
                    <div class="col-12">
                        <label>Tekst e-mail ticket (Nieuw contactpersoon)</label>
                        <textarea id="workemailtextcc" class="editortools" name="workemailtextcc" rows="15"><?= $business->WorkEmailTextCC; ?></textarea>
                    </div>

                    <div class="col-12">
                        <label>Tekst e-mail ticket (Update contactpersoon)</label>
                        <textarea id="workemailtextcu" class="editortools" name="workemailtextcu" rows="15"><?= $business->WorkEmailTextCU; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">subject</i>
                    </div>
                    <h4 class="card-title">Condities</h4>
                </div>

                <div class="card-body">
                    <div class="col">
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                 <input type="radio" id="conditionsGeneralTextRadio" onclick="checkBox()" class="form-check-input" name="conditionsgeneralradio" value="text" checked>Text
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                 <input type="radio" id="conditionsGeneralPdfRadio" onclick="checkBox()" class="form-check-input" name="conditionsgeneralradio" value="pdf">PDF
                            </label>
                        </div>
                        <div id="conditionsGeneralText" class="mt-2">
                            <label>Algemene voorwaarden</label>
                            <textarea class="editortools" name="conditionsgeneraltext" rows="15" >
                            </textarea>
                        </div>
                    </div>
                        <br>
                        <div class="col-md-6 dragdrop" id="conditionsGeneralPdf" style="display:none">
                             <input type="file" name="conditionsgeneralpdf" />
                        </div>
                </div>

                <div class="card-body">
                    <div class="col">
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                 <input type="radio" id="conditionsSalesTextRadio" onclick="checkBox()" class="form-check-input" name="conditionssalesradio" value="text" checked>Text
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                 <input type="radio" id="conditionsSalesPdfRadio" onclick="checkBox()" class="form-check-input" name="conditionssalesradio" value="pdf">PDF
                            </label>
                        </div>
                        <div id="conditionsSalesText" class="mt-2">
                            <label>Toevoegen verkoopvoorwaarden</label>
                            <textarea class="editortools" name="conditionssalestext" rows="15" ></textarea>
                        </div>
                    </div>
                        <br>
                        <div class="col-md-6 dragdrop" id="conditionsSalesPdf" style="display:none">
                              <input type="file" name="conditionssalespdf" />
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-4">
            <button type="submit" class="btn btn-success btn-block">Opslaan en sluiten</button>
        </div>
    </div>
</form>

<?php include 'application/views/inc/footer.php'; ?>

<script>
    function checkBox(){

        var conditionGeneralValue = $('input[name="conditionsgeneralradio"]:checked').val();
        var conditionSaleValue = $('input[name="conditionssalesradio"]:checked').val();

        if (conditionGeneralValue == 'text'){
            $("#conditionsGeneralText").slideDown();
            $("#conditionsGeneralPdf").slideUp();
        } else {
            $("#conditionsGeneralText").slideUp();
            $("#conditionsGeneralPdf").slideDown();
                    }

        if (conditionSaleValue == 'text'){
            $("#conditionsSalesText").slideDown();
            $("#conditionsSalesPdf").slideUp();
        } else {
            $("#conditionsSalesText").slideUp();
            $("#conditionsSalesPdf").slideDown();
        }
    }
</script>
