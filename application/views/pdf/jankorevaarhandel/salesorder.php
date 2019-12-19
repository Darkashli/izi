<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <META HTTP-EQUIV="Content-Type" content="text/html; charset=utf-8"/>
        <style>

            html, body {
                height: 100%;
            }

            body {
                margin: 2.5cm 0 0 0;
                text-align: justify;
                font-size: 15px;
                font-family: tahoma, Helvetica, Arial, Verdana;
            }

            #header,
            #footer {
                position: fixed;
                left: 0;
                right: 0;
                font-size: 0.9em;
            }

            #header {
                top: 0;
                padding-bottom: 20px;
            }

            #footer {
                bottom: 40px;
            }

            #header table,
            #footer table {
                width: 100%;
                border-collapse: collapse;
                border: none;
            }

            #header td,
            #footer td {
                padding: 0;
                width: 33%;
            }

            #header img {
                margin-top: -10px;
                height: 55px;
                width: auto;
            }
            
            #rules{
                padding-bottom: 101px; /* = Footer (height + bottom) */
            }

            .page-number {
                text-align: right;
            }

            hr {
                page-break-after: always;
                border: 0;
            }

            thead {
                font-weight: bold;
            }

            .h100 {
                height: 100%;
            }

            .w100 {
                width: 100%;
            }

            .w95 {
                width: 95%;
            }

            .w50 {
                width: 50%;
            }

            .w33 {
                width: 33%;
            }

            .padding-bottom-50 {
                padding-bottom: 50px;
            }

            .margin-top-20 {
                margin-top: 20px;
            }

            .border {
                border-top: 0.1pt solid #aaa;
                border-spacing: 0px;
                margin: 20px 0;
            }

            .border td {
                border: 0.5px solid gray;
                border-spacing: 0px;
            }

            .aling-right {
                text-align: right;
            }

            .padding-right-30{
                padding-right: 30px;
            }

            .lh-1-5{
                line-height: 1.5rem;
            }

            .txt-top{
                text-align: top;
            }

            .font-15{
                font-size: 15px;
            }
        </style>
    </head>

    <body>

        <div id="header">
            <img class="w100" src="<?= base_url('assets/images/business/jankorevaarhandel/logo.png'); ?>">
            <table class="w100">
                <tr>
                    <td><?php echo $business->StreetName ?> <?php echo $business->StreetNumber ?><?php echo $business->StreetAddition ?><br>
                    <?php echo $business->ZipCode ?> <?php echo $business->City ?></td>
                    <td>Telefoon: <?php echo $business->PhoneNumber ?><br>
                    E-mail: <?php echo $business->Email ?></td>
                </tr>
            </table>
        </div>

        <div id="footer">
            <p>Op al onze transacties zijn van toepassing de algemene verkoop-, leverings- en betalingsvoorwaarden zoals gedeponeerd bij het handelsregister te 's-Hertogenbosch onder nummer 17223862</p>
        </div>

        <table class="w100 padding-bottom-50">
            <tr>
                <td><b><u>Verzendadres</u></b><br />
                    <br>
                <?php if ($customer != null) { ?>
                        <?= $customer->Name; ?><br />
                        <?= $customer->StreetName ?> <?= $customer->StreetNumber ?><?= $customer->StreetAddition; ?><br />
                        <?= $customer->ZipCode ?> <?= $customer->City; ?>
                <?php }else{ ?>
                        <?= $salesOrder->CompanyName != null ? $salesOrder->CompanyName : $salesOrder->FrontName.' '.$salesOrder->Insertion.' '.$salesOrder->LastName; ?><br />
                        <?= $salesOrder->Address ?> <?= $salesOrder->AddressNumber ?><?= $salesOrder->AddressAddition; ?><br />
                        <?= $salesOrder->ZipCode ?> <?= $salesOrder->City; ?>
                <?php } ?>
                </td>
                <td>
                    <h1>PAKBON</h1>
                </td>
            </tr>
        </table>

        <table class="w100 padding-bottom-50 lh-1-5 txt-top font-15">
            <tr>
                <td class="w50">Ordernummer: <?= $salesOrder->OrderNumber ?><br>
                <!-- Klantnummer: ###<br> -->
                Orderdatum: <?= date('d-m-Y', strtotime($salesOrder->OrderDate)) ?></td>
                <td>Uw referentie: <?= $salesOrder->Reference ?><br>
                <?php if (isset($salesOrder->Seller_id) && $salesOrder->Seller_id != 0) { ?>
                    Verkoopkanaal: <?= getSellerName($salesOrder->Seller_id) ?><br>
                <?php }if (isset($salesOrder->Colli)) { ?>
                    Aantal colli / pallets: <?= $salesOrder->Colli ?><td>
                <?php } ?>
            </tr>
        </table>

        <table id="rules" class="w100 lh-1-5">
            <thead>
                <tr>
                    <td>Art. nr</td>
                    <td>EAN code</td>
                    <td>Omschrijving</td>
                    <td>Locatie</td>
                    <td class="aling-right">Aant.verp.</td>
                </tr>
            </thead>
            <?php foreach ($salesOrderRules as $salesOrderRule) { ?>
                <tr>
                    <td><?= $salesOrderRule->ArticleC; ?></td>
                    <td><?= $salesOrderRule->EanCode; ?></td>
                    <td><?= $salesOrderRule->Description; ?>
                        <?php if ($salesOrderRule->MetaData != null):
                            $metaDatas = unserialize($salesOrderRule->MetaData);
                            $countMetaData = ( count($metaDatas) -1 );
                            echo " ";
                            foreach ($metaDatas as $key => $metaData): ?>
                                <?= $metaData['value'] . ( $key < $countMetaData ? ',' : null ) ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $salesOrderRule->WarehouseLocation ?><?= ($salesOrderRule->WarehouseLocation != null || $salesOrderRule->WarehouseName != null) ? '||' : null ?><?= $salesOrderRule->WarehouseName ?></td>
                    <td class="aling-right"><?= (int)$salesOrderRule->Amount; ?></td>
                </tr>
            <?php }
            ?>
        </table>

    </body>
</html>
