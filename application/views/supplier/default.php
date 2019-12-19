<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Leveranciers');

if ($this->uri->segment(3) == 'invoice'):
    define('PAGE', 'invoiceS');
elseif ($this->uri->segment(3) == 'purchaseorder'):
    define('PAGE', 'purchaseorder');
else:
    define('PAGE', 'supplier');
endif;

include 'application/views/inc/header.php';
?>
<div class="row">
    <div class="col-auto ml-auto">
        <a class="btn btn-success float-right" href="<?= base_url(); ?>supplier/create">Leverancier toevoegen</a>
        <?php if ($this->uri->segment(3) == 'purchaseorder'){?>
            <div class="dropdown float-right orderdrop">
                <button class="btn btn-info dropdown-toggle nopad-bot" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Eenmalige leveranciers
                </button>
                <div class="dropdown-menu dropdown-menu-right iconstyle" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="<?= base_url(); ?>PurchaseOrders/createorder">
                        <i class="material-icons">mode_edit</i>
                        Order maken
                    </a>
                    <a class="dropdown-item iconstyle" href="<?= base_url(); ?>PurchaseOrders/listanonymousorders">
                        <i class="material-icons">visibility</i>
                        Overzicht orders
                    </a>
                </div>
            </div>
        <?php }elseif ($this->uri->segment(3) == 'invoice') { ?>
            <div class="dropdown float-right orderdrop">
                <button class="btn btn-info dropdown-toggle nopad-bot" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Eenmalige leveranciers
                </button>
                <div class="dropdown-menu dropdown-menu-right iconstyle" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="<?= base_url(); ?>Invoices/createinvoiceS">
                        <i class="material-icons">mode_edit</i>
                        Factuur maken
                    </a>
                    <a class="dropdown-item iconstyle" href="<?= base_url(); ?>invoices/anonymousS">
                        <i class="material-icons">visibility</i>
                        Overzicht facturen
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-header card-header-icon card-header-primary">
                <div class="card-icon">
                    <i class="material-icons">business</i>
                </div>
                <h4 class="card-title">Leveranciers</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="dataTables-example" width="100%">
                        <thead>
                            <tr>
                                <td>Bedrijfsnaam</td>
                                <td>Adres</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>Woonplaats</td>
                                <td>Telefoonnummer</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($suppliers as $supplier) { ?>
                                <?php if ($this->uri->segment(3) == 'invoice'): ?>
                                    <tr data-href="<?= base_url() . 'invoices/createinvoiceS/' . $supplier->Id; ?>">
                                <?php elseif ($this->uri->segment(3) == 'purchaseorder'): ?>
                                    <tr data-href="<?= base_url() . 'PurchaseOrders/createorder/' . $supplier->Id; ?>">
                                <?php elseif ($this->uri->segment(3)): ?>
                                    <tr data-href="<?= base_url() . 'supplier/' . $this->uri->segment(3) . '/' . $supplier->Id; ?>">
                                <?php else: ?>
                                    <tr data-href="<?= base_url(); ?>supplier/edit/<?= $supplier->Id; ?>">
                                <?php endif; ?>
                                    <td><?= $supplier->Name; ?></td>
                                    <td><?= $supplier->StreetName; ?></td>
                                    <td><?= $supplier->StreetNumber; ?><?= $supplier->StreetAddition; ?></td>
                                    <td><?= $supplier->ZipCode; ?></td>
                                    <td><?= $supplier->City; ?></td>
                                    <td><?= $supplier->PhoneNumber; ?></td>
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
        $('#dataTables-example').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"
            }
        });
    });
    
</script>

<?php include 'application/views/inc/footer.php'; ?>
