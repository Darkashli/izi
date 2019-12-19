<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Overzichten');
define('PAGE', 'overviews');

define('SUBTITEL', 'Overzicht verkoopfactuur | Periode van ' . $from . ' tot ' . $to);

include 'application/views/inc/header.php';
?>

<div class="row">
    <div class="col-auto ml-auto">
        <a class="btn btn-success" href="<?= base_url(); ?>invoicePdf/overviewSold/<?= $this->uri->segment(3); ?>/<?= $this->uri->segment(4); ?>/<?= $customerId; ?>/pdf">Maak pdf</a>
    </div>
	<div class="col-auto">
        <a class="btn btn-success" href="<?= base_url(); ?>invoicePdf/overviewSold/<?= $this->uri->segment(3); ?>/<?= $this->uri->segment(4); ?>/<?= $customerId; ?>/csv">Maak CSV</a>
    </div>
</div>

<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
    <div class="card">
        <div class="card-header card-header-icon card-header-primary">
            <div class="card-icon">
                <i class="material-icons">local_offer</i>
            </div>
            <h4 class="card-title">Overzicht</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="soldInvoices" width="100%">
                    <thead>
                        <tr>
                            <td>Klantnaam</td>
                            <td>Factuurnummer</td>
                            <td>Factuurdatum</td>
                            <td>Totaal excl. BTW</td>
                            <td>Totaal incl. BTW</td>
                            <td>Vervaldatum</td>
                            <?php if ($business->Name == 'CommPro Automatisering'): ?>
                                <td>&nbsp;</td>
                            <?php endif; ?>
                            <td>&nbsp;</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invoices as $invoice) { ?>
                            <tr>
                                <td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= $invoice->CustomerId != null ? getCustomerName($invoice->CustomerId) : ($invoice->CompanyName != null ? $invoice->CompanyName : $invoice->FrontName.' '.$invoice->Insertion.' '.$invoice->LastName) ?></td>
                                <td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= $invoice->InvoiceNumber; ?></td>
                                <td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= date('d-m-Y', $invoice->InvoiceDate); ?></td>
                                <td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= $invoice->TotalEx; ?></td>
                                <td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= $invoice->TotalIn; ?></td>
                                <td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= date('d-m-Y', $invoice->ExpirationDate); ?></td>
                                <?php if ($business->Name == 'CommPro Automatisering'): ?>
                                    <td class="td-actions text-right">
                                        <a href="<?= base_url('InvoicePdf/invoiceMailVdsadmin/'.$invoice->Id) ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Mailen naar vdsadmin.mvl15637@mailtobasecone.com">
                                            <i class="material-icons">email</i>
                                        </a>
                                    </td>
                                <?php endif; ?>
                                <td data-href="<?= base_url('customers/openinvoice/'.$invoice->Id) ?>"><?= $invoice->SentPerMail ? '<i class="material-icons text-success">check_circle</i>' : null ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    
    $(document).ready(function () {
        $.fn.dataTable.moment('DD-MM-YYYY');

        $('#soldInvoices').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"
            },
            "order": [[2, "desc"]],
            "sDom": "lfrtip", // default is lfrtip, where the f is the filter,
            columnDefs: [
                {
                    "orderable": false,
                    "targets": [6]
                }
            ]
        });
    });
    
</script>

<?php include 'application/views/inc/footer.php'; ?>
