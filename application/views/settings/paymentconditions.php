<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Instellingen');
define('PAGE', 'settings');

define('SUBMENUPAGE', 'paymentconditions');
define('SUBMENU', $this->load->view('settings/tab', array(), true));

include 'application/views/inc/header.php';
?>
<div class="row">
    <?= SUBMENU; ?>
    <div class="col-auto">
        <a class="btn btn-success float-right go-up" href="<?= base_url('settings/createpaymentconditoin') ?>">Nieuwe betaalconditie toevoegen</a>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-header card-header-icon card-header-primary">
                <div class="card-icon">
                    <i class="material-icons">layers</i>
                </div>
                <h4 class="card-title">Betaalcondities</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover w-100" id="paymentocnditionsTable">
                        <thead>
                            <tr>
                                <td>Id</td>
                                <td>Betaalconditie</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($paymentconditions)): ?>
                                <?php foreach ($paymentconditions as $paymentcondition) { ?>
                                    <tr data-href="<?= base_url('settings/editpaymentcondition/' . $paymentcondition->Id) ?>">
                                        <td><?= $paymentcondition->Id; ?></td>
                                        <td><?= $paymentcondition->Name; ?></td>
                                    </tr>
                                <?php } ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#paymentocnditionsTable').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"
            }
        });
    });
</script>

<?php include 'application/views/inc/footer.php'; ?>
