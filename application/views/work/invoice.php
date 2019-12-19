<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Urenverantwoording');
define('PAGE', 'work');

include 'application/views/inc/header.php';
?>

<!-- /. NAV SIDE  -->
<div id="page-content-wrapper">
    <div class="container-fluid xyz">

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    Ticket: <?= getCustomerName($customer->Id); ?> (<?= $customer->Id; ?>)
                </h1>
            </div>
        </div> 

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block"> 
                        <div class="notification-bar alert" style="display: none;"></div>

                        <div class="col-lg-12 col-md-12 height-42 margin-bottom-30">
                            <div class="col-lg-12 col-md-12 col-xs-12">
                                <?php
                                $pagina = "invoice";
                                include 'application/views/work/tab.php';
                                ?>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-xs-12">


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include 'application/views/inc/footer.php'; ?>