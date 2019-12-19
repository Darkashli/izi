<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Dashboard');
define('PAGE', 'dashboard');

include 'application/views/inc/header.php';
?>

<div class="row">
    <?php if (hasWebshop() && !empty($webshop) && $webshop->OrderFormat == 'invoice'): ?>
        <div class="col-12 text-center">
            <a href="<?= base_url('webshop/syncOrders') ?>" class="btn btn-info">Haal webshop facturen binnen</a>
        </div>
    <?php endif; ?>
    <?php if (checkModule('ModuleTickets')) { ?>
        <div class="col-12">
            <form method="post">
                
                <div class="row">
                    <div class="col-lg-4 ml-md-auto text-right">
                        <select name="userFilter" class="selectpicker" data-style="btn btn-info btn-round" onchange="this.form.submit()" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
                            <option value="all" <?= set_select('userFilter', 'all', ($postUserId == 'all')) ?>>Alle tickets</option>
                            <?php
                            foreach ($users as $user) {
                                if (!$user->Activated) {
                                    continue;
                                }
                                ?>
                                <option value="<?= $user->Id ?>" <?= set_select('userFilter', $user->Id, ($postUserId == $user->Id)) ?>><?= getUserFullName($user->Id) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header card-header-icon card-header-primary">
                                <div class="card-icon">
                                    <i class="material-icons">show_chart</i>
                                </div>
                                <div class="card-title">
                                    <h4 class="float-left">Overzicht tickets</h4>
                                    <div class="float-right form-inline form-horizontal dashboard">
                                        <label class="control-label" for="begin-date">Periode van </label>
                                        <input type="text" name="begin_date" id="begin-date" class="form-control" value="<?= set_value('begin_date', date('d-m-Y')) ?>">
                                        <label class="control-label" for="end-date"> tot en met </label>
                                        <input type="text" name="end_date" id="end-date" class="form-control" value="<?= set_value('end_date', date('d-m-Y')) ?>">
                                        <button type="submit" class="btn btn-round btn-success btn-fab btn-fab-mini"><i class="material-icons">&#xE8B6;</i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="quotation-dashboard row">
                                    <div class="col-xs-12 col-md-3">      <span class="number number-success"><?= $countTickets['new'] ?></span><br>Nieuwe tickets (vandaag)</div>
                                    <div class="col-xs-6 col-md-3">       <span class="number"><?= $countTickets['open'] ?></span><br>Openstaande tickets</div>
                                    <div class="col-xs-6 col-md-3">       <span class="number"><?= $countTickets['closed'] ?></span><br>Gesloten tickets</div>
                                    <div class="col-xs-6 col-md-3 last">  <span class="number"><?= $countTickets['invoiced'] ?></span><br>Gefactureerde tickets</div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">local_offer</i>
                    </div>
                    <h4 class="card-title">Openstaande tickets</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="ticketsTable" width="100%">
                            <thead>
                                <tr>
                                    <td>Klantnaam</td>
                                    <td>Beschrijving</td>
                                    <td>Prioriteit</td>
                                    <td>Aanmelddatum</td>
                                    <td>Status</td>
                                    <td>Gemeld bij</td>
                                    <td>Laatst gekoppeld</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($tickets)) {
                                    foreach ($tickets as $ticket){
                                        
                                        $firstTicketRule = getFirstTicketRule($ticket->TicketId);
                                        // $lastTicketRule = getLastTicketRule($ticket->TicketId);
                                        $status = getStatus($ticket->Status);
                                        
                                        if ($ticket->Status == $latestStatus->Id) {
                                            continue;
                                        }
                                        if ($postUserId != 'all' && $postUserId != $ticket->UserIdLink) {
                                            continue;
                                        }
                                        ?>
                                        
                                        <tr data-href="<?= base_url() . 'work/update/' . $ticket->TicketId; ?>">
                                            <td><?= getCustomerName($ticket->CustomerId); ?></td>
                                            <td><?= $ticket->Description; ?></td>
                                            <td data-order="<?= $ticket->Priority ?>"><?= getPriority($ticket->Priority) ?></td>
                                            <td><?= date('d-m-Y', $firstTicketRule->Date); ?></td>
                                            <td><?php if ($status != NULL): ?><?= getStatusBlock($status->Id).' '.$status->Description ?><?php else: ?> Geen status<?php endif; ?></td>
                                            <td><?= getUserFullName($ticket->UserId) ?></td>
                                            <td><?= getUserFullName($ticket->UserIdLink) ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">graphic_eq</i>
                    </div>
                    <h4 class="card-title">Lopende projecten</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="projectTable" width="100%">
                            <thead>
                                <tr>
                                    <td>Project nr.</td>
                                    <td>Klantnaam</td>
                                    <td>Omschrijving</td>
                                    <td>Project betreft</td>
                                    <td>&nbsp;</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projects as $project):
                                    if (getProjectStatus($project->Id, $firstStatus->Id, $latestStatus->Id) == "Gesloten") {
                                        continue;
                                    }
                                ?>
                                    <tr data-href="<?= base_url("projects/overview/$project->Id") ?>">
                                        <td><?= $project->ProjectNumber ?></td>
                                        <td><?= getCustomerName($project->CustomerId) ?></td>
                                        <td><?= $project->Description ?></td>
                                        <td><?= getNatureOfWork($project->NatureOfWorkId) ?></td>
                                        <td class="td-actions text-center"><a href="<?= base_url("projects/update/$project->Id") ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Aanpassen"><i class="material-icons">edit</i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($this->session->userdata('user')->Tab_CSalesOrders): ?>
        <div class="col-12">
            <form method="post" action="<?= base_url('SalesOrders/convertToInvoice') ?>" id="order-form">
                <div class="card">
                    <div class="card-header card-header-icon card-header-primary">
                        <div class="card-icon">
                            <i class="material-icons">call_made</i>
                        </div>
                        <div class="card-title">
                            <h4 class="float-left">Openstaande verkooporders</h4>
                            <?php if (hasWebshop() && !empty($webshop) && $webshop->OrderFormat == 'order'): ?>
                                <div class="float-right">
                                    <a href="<?= base_url('webshop/syncOrders') ?>" class="btn btn-info">Haal webshop orders binnen</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SalesOrderTable" width="100%">
                                <thead>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input check" id="checkAll">
                                                    <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>Ordernummer</td>
                                        <td>Klantnaam</td>
                                        <td>Orderdatum</td>
                                        <td>Verkoopkanaal</td>
                                        <td>Referentie</td>
                                        <td class="text-right">Actie</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order) { ?>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input check" name="orders[]" value="<?= $order->Id ?>"/>
                                                        <span class="form-check-sign">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td><?= $order->OrderNumber ?></td>
                                            <?php if ($order->CustomerId == null) { ?>
                                                <td><?= $order->CompanyName ?></td>
                                            <?php } else { ?>
                                                <td><?= getCustomerName($order->CustomerId) ?></td>
                                            <?php } ?>
                                            <td><?= date('d-m-Y', strtotime($order->OrderDate)); ?></td>
                                            <td><?= getSellersName($order->Seller_id); ?></td>
                                            <td><?= $order->Reference ?></td>
                                            <td class="td-actions text-right">
                                                <a href="<?= base_url('SalesOrders/editorder/' . $order->Id) ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Aanpassen">
                                                    <i class="material-icons">edit</i>
                                                </a>
                                                
                                                <a href="<?= base_url('SalesOrders/salesorderpdf/' . $order->Id) ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Download pakbon">
                                                    <i class="material-icons">picture_as_pdf</i>
                                                </a>
                                                
                                                <button type="button" class="btn btn-info btn-round btn-fab btn-fab-mini" onclick="promoteWarning(<?= $order->Id ?>)" title="Zet om naar factuur">
                                                    <i class="material-icons">file_upload</i>
                                                </button>
                                                
                                                <a href="<?= base_url('SalesOrders/salesordercsv/' . $order->Id) ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Exporteer als CSV">
                                                    <i class="material-icons">import_export</i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <button class="btn btn-success btn-block" type="button" onclick="promoteWarning()">Omzetten naar factuur</button>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
    
    <?php if (checkModule('ModuleQuotation')): ?>
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">description</i>
                    </div>
                    <div class="card-title">
                        <h4 class="float-left">Openstaande offertes</h4>
                        <?php if (hasWebshop() && !empty($webshop) && $webshop->OrderFormat == 'quotation'): ?>
                            <div class="float-right">
                                <a href="<?= base_url('webshop/syncOrders') ?>" class="btn btn-info">Haal webshop offertes binnen</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="quotationsTable" width="100%">
                            <thead>
                                <tr>
                                    <td>Offertenummer</td>
                                    <td>Klantnaam</td>
                                    <td>Betreft</td>
                                    <td>Bedrag éénmalig</td>
                                    <td>Bedrag terugkeerend</td>
                                    <td>Status</td>
                                    <td class="text-right">Acties</td>
                                    <td>&nbsp;</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quotations as $quotation) { ?>
                                    <tr>
                                        <td data-href="<?= base_url("quotation/detail/$quotation->Id") ?>"><?= $quotation->QuotationNumber; ?></td>
                                        <td data-href="<?= base_url("quotation/detail/$quotation->Id") ?>"><?= !empty($quotation->CustomerId) ? getCustomerName($quotation->CustomerId) : getQuotationName($quotation->Id) ?></td>
                                        <td data-href="<?= base_url("quotation/detail/$quotation->Id") ?>"><?= $quotation->Subject; ?></td>
                                        <td data-href="<?= base_url("quotation/detail/$quotation->Id") ?>"><?= preg_replace('/\./', ',', getCosts($quotation)['costs']) ?></td>
                                        <td data-href="<?= base_url("quotation/detail/$quotation->Id") ?>"><?= preg_replace('/\./', ',', getCosts($quotation)['recurring']) ?></td>
                                        <td data-href="<?= base_url("quotation/detail/$quotation->Id") ?>"><?= getQuotationStatus($quotation->Status, $quotation->BusinessId) ?></td>
                                        <td class="td-actions text-right">
                                            <a href="<?= base_url("quotation/update/$quotation->Id") ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Bewerken"><i class="material-icons">edit</i></a>
                                            <a href="<?= base_url("quotation/downloadQuotation/$quotation->Id") ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Downloaden"><i class="material-icons">file_download</i></a>
                                            <a href="<?= base_url("quotation/convertToInvoice/$quotation->Id") ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Omzetten naar factuur"><i class="material-icons">file_upload</i></a>
                                        </td>
                                        <td data-href="<?= base_url("quotation/detail/$quotation->Id") ?>"><?= $quotation->Status != 'created' ? '<i class="material-icons text-success">check_circle</i>' : null ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (checkModule('ModuleRepeatingInvoice')): ?>
        <div class="col-12">
            <div class="card">              
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">layers</i>
                    </div>
                    <div class="card-title">
                        <h4 class="float-left">Overzicht periodieke facturen</h4>
                        <div class="float-right">
                            <form method="post">
                                <?= $RPIperiod; ?>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="repeatingInvoiceTable" width="100%">
                            <thead>
                                <tr>
                                    <td>Factuurdatum</td>
                                    <td>Tijdsperiode</td>
                                    <td>Klantnaam</td>
                                    <td>Factuur beschrijving</td>
                                    <td>T.A.V</td>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php foreach ($repeatingInvoices as $repeatingInvoice) { ?>
                                    <tr data-href="<?= base_url() . 'customers/updateRepeatingInvoice/' . $repeatingInvoice->Id; ?>">
                                        <td><?= date('d-m-Y', $repeatingInvoice->InvoiceDate); ?></td>
                                        <td><?= getTimePeriodFromDropdown($repeatingInvoice->TimePeriod); ?></td>
                                        <td><?= getCustomerName($repeatingInvoice->CustomerId); ?></td>
                                        <td><?= $repeatingInvoice->InvoiceDescription; ?></td>
                                        <td><?= getContactName($repeatingInvoice->ContactId); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>


<script type="text/javascript">

    $(document).ready(function () {

        $.fn.dataTable.moment('DD-MM-YYYY');

        $('#ticketsTable').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"
            },
            "order": [
                [2, "desc"],
                [3, "desc"]
            ]
        });

        $('#SalesOrderTable').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"
            },
            "order": [[1, "desc"]],
            "columnDefs": [
                {
                    "orderable": false,
                    "targets": [0, 6]
                }
            ]
        });
        
        $('#quotationsTable').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"
            },
            "columnDefs": [
                {
                    "orderable": false,
                    "targets": [6]
                }
            ],
            "order": [[0, "desc"]]
        });
        
        $('#projectTable').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"
            },
            columnDefs: [
                {
                    "orderable": false,
                    "targets": [4]
                }
            ],
            "order": [[0, "desc"]]
        });
        
        $('#repeatingInvoiceTable').DataTable({
                "language": {
                    "url": "/assets/language/Dutch.json"
                },
                "order": [[0, "asc"]]
                // "sDom": "lfrtip" // default is lfrtip, where the f is the filter
            });

        // Datepicker 1.
        $('input#begin-date').datetimepicker({
            locale: 'nl-NL',
            format: 'DD-MM-YYYY',
            icons: datetimepickerIcons
        });

        // Datepicker 2.
        $('input#end-date').datetimepicker({
            locale: 'nl-NL',
            format: 'DD-MM-YYYY',
            icons: datetimepickerIcons
        });

        // Link datepicker 1 to datepicker 2.
        $("input#begin-date").on("dp.change", function (e) {
            $('input#end-date').data("DateTimePicker").minDate(e.date);
        });
        $("input#end-date").on("dp.change", function (e) {
            $('input#begin-date').data("DateTimePicker").maxDate(e.date);
        });

        // Check all checkboxes.
        $("#checkAll").click(function () {
            $(".check:not(:disabled)").prop('checked', $(this).prop('checked'));
        });
    });

    function promoteWarning(salesOrderId = 0) {
        swal({
            title: 'Waarschuwing!',
            text: 'U staat op het punt om één of meerdere verkooporders om te zetten naar een verkoopfactuur. Dit proces kan niet ongedaan gemaakt worden. Weet u zeker dat u door wilt gaan?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4caf50',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ja',
            cancelButtonText: 'Nee',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                if (salesOrderId != 0) {
                    location.href = '<?= base_url('SalesOrders/convertToInvoice') ?>/' + salesOrderId;
                }
                else{
                    $("#order-form").submit();
                }
            }
        })
    }
    
</script>

<?php include 'application/views/inc/footer.php'; ?>
