<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Bedrijven');
define('PAGE', 'settings');
define('SUBMENUPAGE', 'business');
define('SUBMENU', $this->load->view('settings/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
    <?= SUBMENU; ?>
    <?php if (checkPerm(15)) { ?>
        <div class="col-auto">
            <a class="btn btn-success" href="<?= base_url('settings/createbusiness') ?>">Bedrijf toevoegen</a>
        </div>
    <?php } ?>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-icon card-header-primary">
                <div class="card-icon">
                    <i class="material-icons">subject</i>
                </div>
                <h4 class="card-title">Bedrijfsgegevens</h4>
            </div>
            <div class="card-body">
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="businessTable" width="100%">
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
                            <?php
                            foreach ($businesses as $business) {
                                if ($business->Id != $this->session->userdata('user')->BusinessId) {

                                    if ($this->session->userdata('user')->Level < 15) {
                                        continue;
                                    }
                                }
                                ?>
                                <tr data-href="<?= base_url('settings/updatebusiness/'.$business->Id) ?>">
                                    <td><?= $business->Name; ?></td>
                                    <td><?= $business->StreetName; ?></td>
                                    <td><?= $business->StreetNumber; ?><?= $business->StreetAddition; ?></td>
                                    <td><?= $business->ZipCode; ?></td>
                                    <td><?= $business->City; ?></td>
                                    <td><?= $business->PhoneNumber; ?></td>
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
        $('#businessTable').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"

            }
        });
    });
</script>

<?php include 'application/views/inc/footer.php'; ?>
