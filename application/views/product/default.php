<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Producten');
define('PAGE', 'product');

include 'application/views/inc/header.php';
?>


<div class="row">
    
    <div class="col-auto ml-auto">
        <a class="btn btn-success" href="<?= base_url('product/create/') ?>">Product toevoegen</a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-icon card-header-primary">
                <div class="card-icon">
                    <i class="material-icons">layers</i>
                </div>
                <h4 class="card-title">Producten</h4>
            </div>
            <div class="card-body">
                <div class="notification-bar alert" style="display: none;"></div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="productTable" width="100%">
                        <thead>
                            <tr>
                                <td>Productnummer</td>
                                <td>EAN code</td>
                                <td>Product</td>
                                <td>Verkoopprijs</td>
                                <td>VVP</td>
                                <td>Beschikbaar</td>
                                <td>Productgroup</td>
                                <td>Actief</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product) { ?>
                                <tr data-href="<?= base_url('product/edit/'.$product->Id) ?>">
                                    <td><?= html_escape($product->ArticleNumber); ?></td>
                                    <td><?= $product->EanCode; ?></td>
                                    <td><?= html_escape($product->Description); ?></td>
                                    <td><?= $product->SalesPrice; ?></td>
                                    <td><?= $product->Vvp; ?></td>
                                    <td><?= $product->Stock - $countBackOrder[$product->Id] ?></td>
                                    <td><?= getProductGroupName($product->ProductGroup); ?></td>
                                    <td><?= $product->Active == 1 ? 'ja' : 'nee' ?></td>
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
        $('#productTable').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"
            }
        });
    });
    
</script>

<?php include 'application/views/inc/footer.php'; ?>
