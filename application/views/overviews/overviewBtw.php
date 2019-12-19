<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Overzicht btw');
define('PAGE', 'overviews');

include 'application/views/inc/header.php';
?>
<div class="row">
    <div class="col-auto ml-auto">
        <a class="btn btn-success float-right" href="<?= base_url(); ?>invoicePdf/overviewBtw/<?= $this->uri->segment(3); ?>/<?= $this->uri->segment(4); ?>/<?= $this->uri->segment(5); ?>"><i class="fas fa-file-pdf"></i> Maak pdf</a>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header card-header-icon card-header-primary">
                <div class="card-icon">
                    <i class="fas fa-percent"></i>
                </div>
                <h4 class="card-title">Overzicht btw</h4>
            </div>
            <div class="card-body">
                
                <div class="table-responsive">
                    <table class="table table-borderless table-btw w-100" id="tableBtw">
                        <thead>
                            <tr>
                                <td colspan="4" class="text-right">Bedrag waarover omzetbelasting wordt berekend</td>
                                <td colspan="2" class="text-right">Omzetbelasting</td>
                            </tr>
                            <tr class="table-primary">
                                <td><span class="table-badge">1</span></td>
                                <td colspan="5">Prestaties binnenland</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1a</td>
                                <td>Leveringen/diensten belast met btw hoog</td>
                                <td class="text-right">€</td>
                                <td class="text-right"><?= $btw['highBtwRules']['exSalesTax'] ?></td>
                                <td class="text-right">€</td>
                                <td class="text-right"><?= $btw['highBtwRules']['salesTax'] ?></td>
                            </tr>
                            <tr>
                                <td>1b</td>
                                <td>Leveringen/diensten belast met 9%</td>
                                <td class="text-right">€</td>
                                <td class="text-right"><?= $btw['lowBtwRules']['exSalesTax'] ?></td>
                                <td class="text-right">€</td>
                                <td class="text-right"><?= $btw['lowBtwRules']['salesTax'] ?></td>
                            </tr>
                            <tr>
                                <td>1c</td>
                                <td>Leveringen/diensten belast met overige tarieven, behalve 0%</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                            </tr>
                            <tr>
                                <td>1d</td>
                                <td>Privégebruik</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                            </tr>
                            <tr>
                                <td>1e</td>
                                <td>Leveringen/diensten belast met 0% of niet bij u belast</td>
                                <td class="text-right">€</td>
                                <td class="text-right"><?= $btw['noBtwRules']['exSalesTax'] ?></td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr class="table-primary">
                                <td><span class="table-badge">2</span></td>
                                <td colspan="5">Verleggingsregelingen binnenland</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2a</td>
                                <td>Leveringen/diensten waarbij de omzetbelasting naar u is vastgelegd</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr class="table-primary">
                                <td><span class="table-badge">3</span></td>
                                <td colspan="5">Prestaties naar of in het buitenland</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>3a</td>
                                <td>Leveringen naar buiten de EU (uitvoer)</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                            </tr>
                            <tr>
                                <td>3b</td>
                                <td>Leveringen naatr of diensten in landen binnen de EU</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                            </tr>
                            <tr>
                                <td>3c</td>
                                <td>Installatie/afstandsverkopen binnen de EU</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr class="table-primary">
                                <td><span class="table-badge">4</span></td>
                                <td colspan="5">Prestaties vanuit het buitenland aan u verricht</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>4a</td>
                                <td>Leveringen/diensten uit landen buiten de EU</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                            </tr>
                            <tr>
                                <td>4b</td>
                                <td>Leveringen/diensten uit landen binnen de EU</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                                <td class="text-right">€</td>
                                <td class="text-right">0</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr class="table-primary">
                                <td><span class="table-badge">5</span></td>
                                <td colspan="5">Voorbelasting, kleineondernemersregeling en totaal</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>5a</td>
                                <td>Leveringen naar buiten de EU (uitvoer)</td>
                                <td class="text-right" colspan="3">€</td>
                                <td class="text-right">0</td>
                            </tr>
                            <tr>
                                <td>5b</td>
                                <td>Voorbelasting</td>
                                <td class="text-right" colspan="3">€</td>
                                <td class="text-right"><?= $btw['preload'] ?></td>
                            </tr>
                            <tr>
                                <td>5c</td>
                                <td>Subtotaal (rubiek 5a min 5b)</td>
                                <td class="text-right" colspan="3">€</td>
                                <td class="text-right"><?= $btw['total'] ?></td>
                            </tr>
                            <tr>
                                <td>5d</td>
                                <td>Vermindering volgens de kleineondernemersregeling</td>
                                <td class="text-right" colspan="3">€</td>
                                <td class="text-right">0</td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Totaal</th>
                                <th class="text-right" colspan="3">€</th>
                                <th class="text-right"><?= $btw['total'] ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>

<?php include 'application/views/inc/footer.php'; ?>
