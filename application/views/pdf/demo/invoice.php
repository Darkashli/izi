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
                color: #aaa;
                font-size: 0.9em;
            }

            #header {
                top: 0;
                padding-bottom: 20px;
            }

            #footer {
                bottom: 40px;
                border-top: 0.1pt solid #aaa;
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
            }

            .page-number {
                text-align: right;
            }

            hr {
                page-break-after: always;
                border: 0;
            }


            .invoiceAdres {
                font-size: 17px;
                margin-bottom: 30px;
            }

            .invoiceAdres td:nth-child(2) {

            }

            thead {
                font-weight: bold;
            }

            .tableBorder {
                border: #000000 1px solid;
            }

            .invoiceSpec td:nth-child(2),
            .invoiceSpec td:nth-child(4),
            .invoiceSpec td:nth-child(6) {
                text-align: right;
            }

            .invoiceCustomer,
            .invoiceSpec {
                margin-bottom: 30px;
            }

            .invoiceTotal {
                font-size: 15px;
                text-align: right;
            }

            table .invoiceDetail {
                padding: 10px; 
            }
            
            .invoiceDetail {
                height: 780px;
            }
            
            .invoiceDetail th,
            .invoiceDetail td{
                height: 30px!important;
                padding: 0px;
                vertical-align: top;
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

        </style>
    </head>

    <body>
        <div id="header">
        </div>

        
        <div id="footer">
        </div>
        
        <table class="w100 invoiceAdres">
            <tr>
                <td class="w50">FACTUUR<br />
                    <span><?=$concerns;?></span></td>
                <td class="w50"><b>Factuuradres</b><br />
                    <?php if ($customer != null) { ?>
                        <?= $customer->Name; ?><br />
                        <?= $customer->StreetName ?> <?= $customer->StreetNumber ?><?= $customer->StreetAddition; ?><br />
                        <?= $customer->ZipCode ?> <?= $customer->City; ?>
                    <?php }else{ ?>
                        <?= $invoice->CompanyName ?? $invoice->FrontName.' '.$invoice->Insertion.' '.$invoice->LastName ?><br>
                        <?= $invoice->Address ?> <?= $invoice->AddressNumber ?> <?= $invoice->AddressAddition ?><br>
                        <?= $invoice->ZipCode ?> <?= $invoice->City ?>
                    <?php } ?>
                </td>
            </tr>
        </table>

        <table class="w100 tableBorder invoiceCustomer"> 
            <tr>
                <td>Debiteurnummer: <?= $customer->Id ?? '-' ?><br />
                    Factuurnummer: <?= $invoice->InvoiceNumber; ?>
                </td>

                <td>Factuurdatum: <?= date('d-m-Y', $invoice->InvoiceDate); ?><br />
                    Uw BTW nr: <?= $customer->BTW ?? '-' ?>
                </td>

                <td>Betalingsconditie:<br />
                    <b><?=$paymentCondition;?></b>
                </td>
            </tr>
        </table>
        
        <table class="w100 tableBorder invoiceDetail">
            <thead>
                <tr>
                    <td>Artikel code</td>
                    <td>Aantal</td>
                    <td>Omschrijving</td>
                    <td class="aling-right">Prijs</td>
                    <td class="aling-right">Korting%</td>
                    <td class="aling-right">Totaal prijs</td>
                </tr>
            </thead>
            <?php foreach ($invoiceRules as $invoiceRule) { ?>
                <tr>
                    <td><?= $invoiceRule->ArticleC; ?></td>
                    <td><?= $invoiceRule->Amount; ?></td>
                    <td><?= $invoiceRule->Description; ?>
                        <?php if ($invoiceRule->MetaData != null):
                            $metaDatas = unserialize($invoiceRule->MetaData);
                            $countMetaData = ( count($metaDatas) -1 );
                            echo " ";
                            foreach ($metaDatas as $key => $metaData): ?>
                                <?= $metaData['value'] . ( $key < $countMetaData ? ',' : null ) ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                    <td class="aling-right"><?= $invoiceRule->Price; ?></td>
                    <td class="aling-right"><?= $invoiceRule->Discount; ?></td>
                    <td class="aling-right"><?= $invoiceRule->Total; ?></td>
                </tr>
            <?php }
            ?>
        </table>


        <table class="w100 invoiceSpec">
            <tr>
                <td><b>Specificatie BTW</b><br />
                    Totaal excl. 21% BTW<br />
                    Totaal excl. 9% BTW<br />
                    Totaal excl. 0% BTW<br />

                </td>

                <td class="padding-right-30"><br />
                    <?= $invoice->TotalExTax21; ?><br />
                    <?= $invoice->TotalExTax6; ?><br />
                    <?= $invoice->TotalExTax0; ?><br />
                </td>

                <td><br />
                    Totaal 21% BTW<br />
                    Totaal 9% BTW<br />
                </td>

                <td class="padding-right-30"><br />
                    <?= $invoice->TotalTax21; ?><br />
                    <?= $invoice->TotalTax6; ?><br />

                </td>

                <td><b>Totalen</b><br />
                    Totaal excl. BTW <br />
                    Totaal incl. BTW <br />
                </td>

                <td class="padding-right-30"><br />
                    <?= $invoice->TotalEx; ?><br />
                    <?= $invoice->TotalIn; ?><br />
                </td>
            </tr>
        </table>

        <table class="w100 tableBorder invoiceTotal">
            <tr>
                <td></td>
                <td class="bedragen-totaal"><b>Totaal te voldoen op rekeningnummer <?= $business->IBAN; ?> in EUR</b></td>
                <td class="bedragen-totaal"><b><?= $invoice->TotalIn; ?></b></td>
            </tr>
        </table>


    </body>
</html>
