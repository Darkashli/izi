<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Instellingen');
define('PAGE', 'settings');

define('SUBMENUPAGE', 'productgroups');
define('SUBMENU', $this->load->view('settings/tab', array(), true));

include 'application/views/inc/header.php';
?>
<div class="row">
    <?= SUBMENU; ?>
    <div class="col-auto">
        <a class="btn btn-success float-right go-up" href="<?= base_url('settings/creategroup/') ?>">Productgroep toevoegen</a>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-header card-header-icon card-header-primary">
                <div class="card-icon">
                    <i class="material-icons">layers</i>
                </div>
                <h4 class="card-title">Productgroepen</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="statusesTable" width="100%">
                        <thead>
                            <tr>
                                <td>Id</td>
                                <td>Naam</td>
                                <td>Omschrijving</td>
                                <td>Valt onder</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productgroups as $productgroup) { ?>
                                <tr data-href="<?= base_url('settings/editgroup/' . $productgroup->Id) ?>">
                                    <td><?= $productgroup->Id; ?></td>
                                    <td><?= $productgroup->Name; ?></td>
                                    <td><?= $productgroup->Description; ?></td>
                                    <td><?= getProductGroupName($productgroup->ParentId); ?></td>
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
        $('#statusesTable').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"
            }
        });
    });
</script>

<?php include 'application/views/inc/footer.php'; ?>
