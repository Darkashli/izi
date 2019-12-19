<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Verkoopkanalen');
define('PAGE', 'sellers');

include 'application/views/inc/header.php';
?>
<div class="row">
    <div class="col-md-3 ml-auto">
        <a class="btn btn-success float-right" href="<?= base_url(); ?>sellers/create">Verkoopkanaal toevoegen</a>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-header card-header-icon card-header-primary">
                <div class="card-icon">
                    <i class="material-icons">work</i>
                </div>
                <h4 class="card-title">Verkoopkanalen</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="dataTables-example" width="100%">
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
                            <?php foreach ($sellers as $seller) { ?>
                            <tr data-href="<?= base_url() . 'sellers/edit/' . $seller->Seller_id; ?>">
                                <td><?= $seller->Name; ?></td>
                                <td><?= $seller->Street; ?></td>
                                <td><?= $seller->House_number; ?><?= $seller->Number_addition; ?></td>
                                <td><?= $seller->Zip_code; ?></td>
                                <td><?= $seller->City; ?></td>
                                <td><?= $seller->Phone; ?></td>
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
        $('#dataTables-example').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"
            }
        });
    });
    
</script>

<?php include 'application/views/inc/footer.php'; ?>
