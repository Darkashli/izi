<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Overzichten');
define('PAGE', 'overviews');

include 'application/views/inc/header.php';
?>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-header card-header-icon card-header-primary">
                <div class="card-icon">
                    <i class="material-icons">access_time</i>
                </div>
                <h4 class="card-title">Openstaande facturen</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="openInvoice">
                        <thead>
                            <tr>
                                <td>Klantnaam</td>
                                <td>Factuurnummer</td>
                                <td>Factuurdatum</td>
                                <td>Totaal incl. BTW</td>
                                <td>Vervaldatum</td>
                                <td>Status</td>
                                <td>Herinneringen</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoices as $invoice) { ?>
                                <tr data-href="<?= base_url() . 'customers/openinvoice/' . $invoice->Id; ?>">
                                    <td><?= $invoice->CustomerId != 0 ? getCustomerName($invoice->CustomerId) : ($invoice->CompanyName != null ? $invoice->CompanyName : $invoice->FirstName.' '.$invoice->LastName) ?></td>
                                    <td><?= $invoice->InvoiceNumber; ?></td>
                                    <td><?= date('d-m-Y', $invoice->InvoiceDate); ?></td>
                                    <td><?= $invoice->TotalIn; ?></td>
                                    <td><?= date('d-m-Y', $invoice->ExpirationDate); ?></td>
                                    <td><?= ($invoice->PaymentDate) ? 'Betaald op ' . date('d-m-Y', $invoice->PaymentDate) : round((strtotime(date('d-m-Y')) - strtotime(date('d-m-Y', $invoice->ExpirationDate))) / 60 / 60 / 24) . ' dagen vervallen'; ?></td>
                                    <td><span class="badge badge-default"><?= countInvoiceReminders($invoice->Id, $invoice->BusinessId) ?></span></td>
                                </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function () {
        $.fn.dataTable.moment('DD-MM-YYYY');

        $('#openInvoice').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"
            },
            "order": [[2, "desc"]]
        });
    });
    
</script>

<?php include 'application/views/inc/footer.php'; ?>
