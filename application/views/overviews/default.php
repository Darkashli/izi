<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Overzichten');
define('PAGE', 'overviews');

include 'application/views/inc/header.php';
?>
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
        <form method="post">
            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">local_offer</i>
                    </div>
                    <h4 class="card-title">Overzicht verstuurde facturen</h4>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-4 form-group label-floating">
                            <label class="control-label">Periode van <small>*</small></label>
                            <input class="form-control date-picker" value="<?= date('01-m-Y') ?>" name="from" required />
                        </div>

                        <div class="col-md-4 form-group label-floating">
                            <label class="control-label">Periode tot <small>*</small></label>
                            <input class="form-control date-picker" value="<?= date('t-m-Y') ?>" name="to" required />
                        </div>

                        <div class="col-md-4 form-group">
                            <select class="form-control selectpicker" name="customerid" data-style="btn btn-link" data-live-search="true" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
                                <option value=""></option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= $customer->Id ?>"><?= $customer->Name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <input type="hidden" name="overviewSold" />

                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4 col-xl-6">
                    <button type="submit" class="btn btn-success btn-block">Zoeken</button>
                </div>
            </div>
        </form>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
        <form method="post">
            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">local_offer</i>
                    </div>
                    <h4 class="card-title">Overzicht ontvangen facturen</h4>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-4 form-group label-floating">
                            <label class="control-label">Periode van <small>*</small></label>
                            <input class="form-control date-picker" value="<?= date('01-m-Y') ?>" name="from" />
                        </div>

                        <div class="col-md-4 form-group label-floating">
                            <label class="control-label">Periode tot <small>*</small></label>
                            <input class="form-control date-picker" value="<?= date('t-m-Y') ?>" name="to" />
                        </div>

                        <div class="col-md-4 form-group">
                            <select class="form-control selectpicker" name="supplierid" data-style="btn btn-link" data-live-search="true" data-none-selected-text="<?= MAKEYOURCHOISE ?>">
                                <option value=""></option>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <option value="<?= $supplier->Id ?>"><?= $supplier->Name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <input type="hidden" name="overviewBought" />

                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4 col-xl-6">
                    <button type="submit" class="btn btn-success btn-block">Zoeken</button>
                </div>
            </div>
        </form>
    </div>
    <?php if (checkModule('ModuleTickets')) { ?>
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
            <form method="post">
                <div class="card">
                    <div class="card-header card-header-icon card-header-primary">
                        <div class="card-icon">
                            <i class="material-icons">local_offer</i>
                        </div>
                        <h4 class="card-title">Overzicht activiteiten per medewerker</h4>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4 form-group label-floating">
                                <label class="control-label">Periode van <small>*</small></label>
                                <input class="form-control date-picker" value="<?= date('d-m-Y') ?>" name="from" />
                            </div>

                            <div class="col-md-4 form-group label-floating">
                                <label class="control-label">Periode tot <small>*</small></label>
                                <input class="form-control date-picker" value="<?= date('t-m-Y') ?>" name="to" />
                            </div>

                            <div class="col-md-4 form-group label-floating">
                                <label class="control-label">Medewerker</label>
                                <?= $user; ?>
                            </div>

                            <input type="hidden" name="overviewWork" />


                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-4 col-xl-6">
                        <button type="submit" class="btn btn-success btn-block">Zoeken</button>
                    </div>
                </div>
            </form>
        </div>
    <?php } ?>

    <?php if (checkModule('ModuleSellers')) { ?>
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
            <form method="post" action="<?= base_url('Overviews/overviewOrders') ?>">
                <div class="card">
                    <div class="card-header card-header-icon card-header-primary">
                        <div class="card-icon">
                            <i class="material-icons">local_offer</i>
                        </div>
                        <h4 class="card-title">Overzicht verstuurde orders</h4>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4 form-group label-floating">
                                <label class="control-label">Periode van <small>*</small></label>
                                <input class="form-control date-picker" value="<?= date('d-m-Y') ?>" name="from" />
                            </div>

                            <div class="col-md-4 form-group label-floating">
                                <label class="control-label">Periode tot <small>*</small></label>
                                <input class="form-control date-picker" value="<?= date('t-m-Y') ?>" name="to" />
                            </div>

                            <div class="col-md-4 form-group">
                                <label>Verkoopkanaal</label>
                                <select class="form-control" name="seller" required>
                                    <option value="0">- Alle -</option>
                                    <?php foreach ($sellers as $seller) { ?>
                                        <option value="<?= $seller->Seller_id ?>"><?= $seller->Name ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <input type="hidden" name="overviewOrders" />

                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-4 col-xl-6">
                        <button type="submit" class="btn btn-success btn-block">Zoeken</button>
                    </div>
                </div>
            </form>
        </div>
    <?php } ?>

    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-header card-header-icon card-header-primary">
                <div class="card-icon">
                    <i class="material-icons">local_offer</i>
                </div>
                <h4 class="card-title">Overzicht ontvangen facturen</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (checkModule('ModuleTickets')) { ?><div class="col-6"><a href="<?= base_url(); ?>overviews/closedTickets" class="btn btn-success btn-block">Overzicht gesloten tickets</a></div><?php } ?>
                    <?php if (checkModule('ModuleTickets')) { ?><div class="col-6"><a href="<?= base_url(); ?>overviews/openTickets" class="btn btn-success btn-block">Overzicht openstaande tickets</a></div><?php } ?>
                    <div class="col-6"><a href="<?= base_url(); ?>overviews/openInvoice" class="btn btn-success btn-block">Overzicht openstaande facturen</a></div>
                    <?php if (checkModule('ModuleRepeatingInvoice')) { ?><div class="col-6"><a href="<?= base_url(); ?>overviews/overviewRepeatingInvoice" class="btn btn-success btn-block">Overzicht periodieke facturen</a></div><?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <form method="post" action="<?= base_url(); ?>overviews/stockList">
            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">local_offer</i>
                    </div>
                    <h4 class="card-title">Voorraad</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Productgroup</label>
                            <select name="productgroup" class="form-control">
                                <option value="alle">** Alle **</option>
                                <?php foreach ($productgroups as $productgroup) { ?>
                                    <option value="<?= $productgroup->Id ?>"><?= $productgroup->Name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Leverancier</label>
                            <select name="supplier" class="form-control">
                                <option value="alle">** Alle **</option>
                                <?php foreach ($suppliers as $supplier) { ?>
                                    <option value="<?= $supplier->Id ?>"><?= $supplier->Name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4 col-xl-6">
                    <button type="submit" class="btn btn-success btn-block">Voorraadlijst</button>
                </div>
            </div>
        </form>
    </div>
    
    <div class="col-md-6">
        <form method="post">
            <div class="card">
                <div class="card-header card-header-icon card-header-primary">
                    <div class="card-icon">
                        <i class="material-icons">local_offer</i>
                    </div>
                    <h4 class="card-title">Overzicht btw</h4>
                </div>
                <div class="card-body">
                    
                    <div class="row">
                        
                        <div class="col-md-4 form-group label-floating">
                            <label class="control-label">Jaar</label>
                            <input class="form-control date-picker-year" name="year" value="<?= date('Y') ?>">
                        </div>
                        
                        <div class="col-md-4 form-check form-check-radio">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="periodType" id="periodTypeMonth" value="month" onchange="toggleOverviewBtwPeriodType()" checked>
                                Maand
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="periodType" id="periodTypeQuartal" value="quartal" onchange="toggleOverviewBtwPeriodType()">
                                Kwartaal
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        
                        <div class="col-md-4">
                            <div id="toggleOverviewBtwMonth" class="form-group">
                                <label>Maand</label>
                                <select class="form-control"name="month">
                                    <option value="1" <?= date('n') == 1 ? 'selected' : NULL ?>>Januari</option>
                                    <option value="2" <?= date('n') == 2 ? 'selected' : NULL ?>>februari</option>
                                    <option value="3" <?= date('n') == 3 ? 'selected' : NULL ?>>Maart</option>
                                    <option value="4" <?= date('n') == 4 ? 'selected' : NULL ?>>April</option>
                                    <option value="5" <?= date('n') == 5 ? 'selected' : NULL ?>>Mei</option>
                                    <option value="6" <?= date('n') == 6 ? 'selected' : NULL ?>>Juni</option>
                                    <option value="7" <?= date('n') == 7 ? 'selected' : NULL ?>>Juli</option>
                                    <option value="8" <?= date('n') == 8 ? 'selected' : NULL ?>>Augustus</option>
                                    <option value="9" <?= date('n') == 9 ? 'selected' : NULL ?>>September</option>
                                    <option value="10" <?= date('n') == 10 ? 'selected' : NULL ?>>Oktober</option>
                                    <option value="11" <?= date('n') == 11 ? 'selected' : NULL ?>>November</option>
                                    <option value="12" <?= date('n') == 12 ? 'selected' : NULL ?>>December</option>
                                </select>
                            </div>
                            <div class="hidden form-group" id="toggleOverviewBtwQuartal">
                                <label>Kwartaal</label>
                                <select class="form-control" name="quartal">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="overviewBtw" />
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4 col-xl-6">
                    <button type="submit" class="btn btn-success btn-block">Overzicht btw</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function () {
        
        $('.date-picker').datetimepicker({
            locale: 'nl',
            format: 'L',
            useCurrent: false,
			icons: datetimepickerIcons
        });
        
        $('.date-picker-year').datetimepicker({
            locale: 'nl',
            format: 'YYYY',
            useCurrent: false,
			icons: datetimepickerIcons
        });
        
    });
    
    function toggleOverviewBtwPeriodType(){
        var inputMonth = $("#periodTypeMonth");
        var inputQuartal = $("#periodTypeQuartal");
        var toggleMonth = $("#toggleOverviewBtwMonth");
        var toggleQuartal = $("#toggleOverviewBtwQuartal");
        if ($(inputMonth).prop("checked") == true) {
            $(toggleMonth).slideDown();
            $(toggleQuartal).slideUp();
        }
        else if ($(inputQuartal).prop("checked") == true) {
            $(toggleQuartal).slideDown();
            $(toggleMonth).slideUp();
        }
    }
    
</script>

<?php include 'application/views/inc/footer.php'; ?>
