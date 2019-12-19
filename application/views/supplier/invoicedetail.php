<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Facturen');
define('PAGE', 'supplier');

include 'application/views/inc/header.php';
?>
<form method="post" action="<?= base_url(); ?>supplier/openinvoice/<?= $this->uri->segment(3); ?>" class="w-100">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">format_list_numbered</i>
                    </div>
                    <h4 class="card-title">Factuurdetails</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <?= $supplierData->Name; ?> <br />
                            <?= $supplierData->StreetName; ?> <?= $supplierData->StreetNumber; ?><?= $supplierData->StreetAddition; ?><br />
                            <?= $supplierData->ZipCode; ?> <?= $supplierData->City; ?><br />
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <a href="<?= base_url(); ?>InvoicePdf/invoiceBoughtPDF/<?= $invoice->Id; ?>" class="btn btn-primary btn-block">Download factuur</a>
                        </div>

                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <table class="table table-striped table-condensed" width="100%">
                                <thead>
                                    <tr>
                                        <td>Aantal</td>
                                        <td>Omschrijving</td>
                                        <td>Prijs p/s</td>
                                        <td>Totaal</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($invoiceRules as $invoiceRule): ?>
                                        <tr>
                                            <td><?= $invoiceRule->Amount; ?></td>
                                            <td><?= $invoiceRule->Description; ?></td>
                                            <td><?= $invoiceRule->Price; ?></td>
                                            <td><?= $invoiceRule->Total; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="material-icons">euro_symbol</i>
                                </span>
                            </div>
                            <div class="form-group label-floating">
                                <label class="control-label">(deel)bedrag</label>
                                <input class="form-control" type="number" step="any" name="amount" value="<?= $invoice->TotalIn - $totalInvoicePaymentAmounts->Amount ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="material-icons">date_range</i>
                                </span>
                            </div>
                            <div class="form-group label-floating">
                                <label class="control-label">Betaaldatum</label>
                                <input class="form-control date-picker" type="text" name="paymentdate" value="<?= date('d-m-Y') ?>" />
                            </div>
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
                    <h4 class="card-title">Betalingen</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive mt-4">
                            <table class="table table-striped table-sm" width="100%">
                                <thead>
                                    <tr>
                                        <td>Betaald op</td>
                                        <td>Bedrag</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($invoicePayments)) { ?>
                                        <?php foreach ($invoicePayments as $invoicePayment){ ?>
                                            <tr>
                                                <td><?= date('d-m-Y', $invoicePayment->Date); ?></td>
                                                <td><?= $invoicePayment->Amount; ?></td>
                                                <td class="text-right"><button type="button" class="btn btn-danger btn-round btn-fab btn-fab-mini" onclick="areYouSure(<?= $invoicePayment->Id ?>)"><i class="material-icons">delete</i></button></td>
                                            </tr>
                                        <?php } ?>
                                    <?php }else{ ?>
                                        <tr>
                                            <td colspan="3">Geen betalingen om te weergeven</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-6">
            <button type="submit" class="btn btn-success btn-block">Opslaan en sluiten</button>
        </div>
    </div>
</form>

<script type="text/javascript">

    $(document).ready(function () {
        
        $('.date-picker').datetimepicker({
            locale: 'nl',
            format: 'L',
            useCurrent: false,
            icons: datetimepickerIcons
        });
    });
    
    function areYouSure(invoicePaymentId){
        var linkURL = '<?= base_url('supplier/deleteInvoicePayment') ?>/' + invoicePaymentId;
        swal({
            title: 'Weet u het zeker?',
            text: 'Deze actie kan niet ongedaan worden gemaakt!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ja, verwijder dit!'
        }).then(function (result) {
            if (result.value) {
                window.location.href = linkURL;
            }
        }).catch(swal.noop)
    }
    
</script>

<?php include 'application/views/inc/footer.php'; ?>
