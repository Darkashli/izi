<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Overzichten');
define('SUBTITEL', 'Periode van ' . $from . ' tot ' . $to);
define('PAGE', 'overviews');

include 'application/views/inc/header.php';
?>

<div class="row">
    <div class="col-md-3 ml-auto">
        <a class="btn btn-success float-right" href="<?= base_url(); ?>invoicePdf/overviewWork/<?= $this->uri->segment(3); ?>/<?= $this->uri->segment(4); ?>/<?= $this->uri->segment(5); ?>">Maak pdf</a>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-header card-header-icon card-header-primary">
                <div class="card-icon">
                    <i class="material-icons">local_offer</i>
                </div>
                <h4 class="card-title">Overzicht activiteiten van <?= getAccountName($userId); ?></h4>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="activityTable">
                        <thead>
                            <tr>
                                <td>Datum</td>
                                <td>Tijd</td>
                                <td>Klantnaam</td>
                                <td>Omschrijving werkzaamheden</td>
                                <td>Status</td>
                                <td>Gefactureerd</td>
                                <td>Gearchiveerd</td>
                                <td>Zonder actie</td>
                                <td>Totaal</td>
                            </tr>
                        </thead>

                        <?php
                        $totalInvoiced = 0;
                        $totalArchived = 0;
                        $totalOther = 0;

                        foreach ($activities as $activity) {
                            $status = getStatus($activity->Status);
                            ?>
                            <tr>
                                <td><?= date('d-m-Y', $activity->Date); ?></td>
                                <td><?= date('H:i', $activity->StartWork); ?> - <?= date('H:i', $activity->EndWork); ?></td>
                                <td><?= getCustomerName($activity->CustomerId); ?></td>
                                <td><?= trim_text($activity->ActionUser, 50, true, true); ?></td>
                                <td><?= $status->Description; ?></td>
                                <td><?php
                                    if ($activity->TicketStatus == 1) {
                                        echo $activity->TotalWork;
                                        $totalInvoiced += $activity->TotalWork;
                                    }
                                    ?></td>
                                <td><?php
                                    if ($activity->TicketStatus == 2) {
                                        echo $activity->TotalWork;
                                        $totalArchived += $activity->TotalWork;
                                    }
                                    ?></td>
                                <td><?php
                                    if ($activity->TicketStatus == 0) {
                                        echo $activity->TotalWork;
                                        $totalOther += $activity->TotalWork;
                                    }
                                    ?></td>
                                <td><?= $activity->TotalWork; ?></td>
                            </tr>
                        <?php } ?>
                        <tfoot>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><?= $totalInvoiced; ?></td>
                                <td><?= $totalArchived; ?></td>
                                <td><?= $totalOther; ?></td>
                                <td><?= $totalInvoiced + $totalArchived + $totalOther; ?> </td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $.fn.dataTable.moment('DD-MM-YYYY');

        $('#activityTable').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"
            },
            "order": [[0, "desc"]],
            "sDom": "lfrtip" // default is lfrtip, where the f is the filter
        });
    });
</script>

<?php include 'application/views/inc/footer.php'; ?>
