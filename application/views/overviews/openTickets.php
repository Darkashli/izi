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
                    <i class="material-icons">layers</i>
                </div>
                <div class="card-title">
                    <h4 class="float-left">Openstaande tickets</h4>
                    <div class="float-right">
                        <form method="post">
                            <select name="userFilter" class="selectpicker" data-style="btn btn-info btn-round" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
                                <option value="all" <?= set_select('userFilter', 'all', ($postUserId == 'all')) ?>>Alle tickets</option>
                                <?php foreach ($users as $user) {
                                    if (!$user->Activated) {
                                        continue;
                                    } ?>
                                    <option value="<?= $user->Id ?>" <?= set_select('userFilter', $user->Id, ($postUserId == $user->Id)) ?>><?= getUserFullName($user->Id) ?></option>
                                <?php } ?>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="closedTickets" width="100%">
                        <thead>
                            <tr>
                                <td>Klantnaam</td>
                                <td>Ticket nummer</td>
                                <td>Datum</td>
                                <td>Omschrijving</td>
                                <td>Status</td>
                                <td>Gekoppeld aan</td>
                            </tr>
                        </thead>

                        <?php
                        foreach ($tickets as $ticket) {

                            $lastTicketRule = getLastTicketRule($ticket->TicketId);
                            $status = getStatus($lastTicketRule->Status);

                            if ($lastTicketRule->Status == $latestStatus->Id) {
                                continue;
                            }
                            if ($postUserId != 'all' && $postUserId != $lastTicketRule->UserIdLink) {
                                continue;
                            }
                            ?>
                            <tr data-href="<?= base_url() . 'work/update/' . $ticket->TicketId; ?>" data-session="<?= $_SESSION['user']->Id ?>" data-user="<?= $lastTicketRule->UserIdLink ?>">
                                <td><?= getCustomerName($ticket->CustomerId); ?></td>
                                <td><?= $ticket->Number; ?></td>
                                <td><?= date('d-m-Y', $lastTicketRule->Date); ?></td>
                                <td><?= $ticket->Description; ?></td>
                                <td><?= getStatusBlock($status->Id).' '.$status->Description ?></td>
                                <td><?= getUserFullName($lastTicketRule->UserIdLink) ?></td>
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
        
        $('#closedTickets').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"
            },
            "order": [[2, "desc"]]
        });
    });
    
</script>

<?php include 'application/views/inc/footer.php'; ?>
