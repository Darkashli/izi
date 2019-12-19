<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Overzichten');
define('SUBTITEL', 'Periode van ' . $from . ' tot ' . $to);
define('PAGE', 'overviews');

include 'application/views/inc/header.php';
?>

<div class="row">
    <div class="col-auto ml-auto">
        <a class="btn btn-success float-right" href="<?= base_url(); ?>invoicePdf/overviewBought/<?= $this->uri->segment(3); ?>/<?= $this->uri->segment(4); ?>/<?= $supplierId ?>/pdf">Maak pdf</a>
    </div>
	<div class="col-auto">
        <a class="btn btn-success float-right" href="<?= base_url(); ?>invoicePdf/overviewBought/<?= $this->uri->segment(3); ?>/<?= $this->uri->segment(4); ?>/<?= $supplierId ?>/csv">Maak CSV</a>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-header card-header-icon card-header-primary">
                <div class="card-icon">
                    <i class="material-icons">local_offer</i>
                </div>
                <h4 class="card-title">Overzicht inkoopfactuur</h4>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-hover w-100" id="soldInvoices">
                        <thead>
                            <tr>
                                <td>Leveranciernaam</td>
                                <td>Factuurnummer</td>
                                <td>Inkoopnummer</td>
                                <td>Factuurdatum</td>
                                <td>Totaal excl. BTW</td>
                                <td>Totaal incl. BTW</td>
                                <td>Vervaldatum</td>
                            </tr>
                        </thead>

                        <?php foreach ($invoices as $invoice) { ?>
                            <tr data-href="<?= base_url('supplier/openinvoice/'.$invoice->Id); ?>">
                                <td><?= $invoice->SupplierId != null ? getSupplierName($invoice->SupplierId) : ($invoice->CompanyName != null ? $invoice->CompanyName : $invoice->FrontName.' '.$invoice->Insertion.' '.$invoice->LastName) ?></td>
                                <td><?= $invoice->InvoiceNumber; ?></td>
                                <td><?= $invoice->PurchaseNumber; ?></td>
                                <td><?= date('d-m-Y', $invoice->InvoiceDate); ?></td>
                                <td><?= $invoice->TotalEx; ?></td>
                                <td><?= $invoice->TotalIn; ?></td>
                                <td><?= date('d-m-Y', $invoice->ExpirationDate); ?></td>
                            </tr>
                        <?php } ?>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function () {
        $.fn.dataTable.moment('DD-MM-YYYY');

        $('#soldInvoices').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"
            },
            "order": [[2, "desc"]],
            "sDom": "lfrtip" // default is lfrtip, where the f is the filter
        });
    });
    
</script>

<?php include 'application/views/inc/footer.php'; ?>
