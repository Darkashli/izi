<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Klanten');

if ($this->uri->segment(3)):
    define('PAGE', $this->uri->segment(3));
else:
    define('PAGE', 'customer');
endif;


include 'application/views/inc/header.php';
?>

<div class="row">
    <div class="col-auto ml-auto">
        <a class="btn btn-success float-right" href="<?= base_url(); ?>customers/create">Klant toevoegen</a>
        <?php switch ($this->uri->segment(3)) {
            case 'salesorder': ?>
                <div class="dropdown float-right orderdrop">
                    <button class="btn btn-info dropdown-toggle nopad-bot" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Eenmalige klanten
                    </button>
                    <div class="dropdown-menu dropdown-menu-right iconstyle" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="<?= base_url(); ?>SalesOrders/createorder">
                            <i class="material-icons">mode_edit</i>
                            Order maken
                        </a>
                        <a class="dropdown-item iconstyle" href="<?= base_url(); ?>SalesOrders/listAnonymousOrders">
                            <i class="material-icons">visibility</i>
                            Overzicht orders
                        </a>
                    </div>
                </div>
                <?php break;
            case 'invoice': ?>
                <div class="dropdown float-right orderdrop">
                    <button class="btn btn-info dropdown-toggle nopad-bot" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Eenmalige klanten
                    </button>
                    <div class="dropdown-menu dropdown-menu-right iconstyle" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="<?= base_url(); ?>Invoices/createinvoice">
                            <i class="material-icons">mode_edit</i>
                            Factuur maken
                        </a>
                        <a class="dropdown-item iconstyle" href="<?= base_url(); ?>invoices/anonymous">
                            <i class="material-icons">visibility</i>
                            Overzicht facturen
                        </a>
                    </div>
                </div>
                <?php break;
            case 'quotation': ?>
                <div class="dropdown float-right orderdrop">
                    <button class="btn btn-info dropdown-toggle nopad-bot" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Eenmalige klanten
                    </button>
                    <div class="dropdown-menu dropdown-menu-right iconstyle" aria-labelledby="dropdownMenuButton">
                        <!-- <a class="dropdown-item" href="<?= base_url(); ?>quotation/createquotation">
                            <i class="material-icons">mode_edit</i>
                            Offerte maken
                        </a> -->
                        <a class="dropdown-item iconstyle" href="<?= base_url(); ?>quotation/anonymousquotations">
                            <i class="material-icons">visibility</i>
                            Overzicht offertes
                        </a>
                    </div>
                </div>
                <?php break;
        } ?>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-header card-header-icon card-header-primary">
                <div class="card-icon">
                    <i class="material-icons">people</i>
                </div>
                <h4 class="card-title">Klanten</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="customersTable" width="100%">
                        <thead>
                            <tr>
                                <td>Klantnaam</td>
                                <td>Adres</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>Woonplaats</td>
                                <td>Telefoonnummer</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $customer) { ?>
                                <?php if ($this->uri->segment(3) == 'invoice'): ?>
                                    <tr data-href="<?= base_url() . 'invoices/createinvoice/' . $customer->Id; ?>">
                                <?php elseif ($this->uri->segment(3) == 'systemmanagement'): ?>
                                    <tr data-href="<?= base_url() . 'SystemManagement/index/' . $customer->Id; ?>">
                                <?php elseif ($this->uri->segment(3) == 'salesorder'): ?>
                                    <tr data-href="<?= base_url() . 'SalesOrders/createorder/' . $customer->Id; ?>">
                                <?php elseif ($this->uri->segment(3) == 'website'): ?>
                                    <tr data-href="<?= base_url() . 'Website/index/' . $customer->Id; ?>">
                                <?php elseif ($this->uri->segment(3) == 'quotation'): ?>
                                    <tr data-href="<?= base_url() . 'Quotation/create/' . $customer->Id; ?>">
                                <?php elseif ($this->uri->segment(3)): ?>
                                    <tr data-href="<?= base_url() . 'customers/' . $this->uri->segment(3) . '/' . $customer->Id; ?>">
                                <?php else: ?>
                                    <tr data-href="<?= base_url() . 'customers/edit/' . $customer->Id; ?>">
                                <?php endif; ?>
                                    <td><?= $customer->Name; ?></td>
                                    <td><?= $customer->StreetName; ?></td>
                                    <td><?= $customer->StreetNumber; ?><?= $customer->StreetAddition; ?></td>
                                    <td><?= $customer->ZipCode; ?></td>
                                    <td><?= $customer->City; ?></td>
                                    <td><?= $customer->PhoneNumber; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

$(document).ready(function () {
    
    $('#customersTable').DataTable({
        "language": {
            "url": "/assets/language/Dutch.json"
        }
    });
    
});

</script>

<?php include 'application/views/inc/footer.php'; ?>
