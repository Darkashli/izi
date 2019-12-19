<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <META HTTP-EQUIV="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">

            html, body {
                height: 100%;
            }

            body {
                margin: 2.5cm 0 0 0;
                text-align: justify;
                font-size: 13px;
                font-family: tahoma, Helvetica, Arial, Verdana;
            }

            #header,
            #footer{
                position: fixed;
                left: 0;
                right: 0;
                font-size: 0.9em;
                padding-bottom: 20px;
            }

            #header{
                top: 0;
            }

            #header table,
            #footer table {
                width: 100%;
                border-collapse: collapse;
                border: none;
            }

            #header img{
                height: 60px;
                margin-top: -10px !important;
            }

            .logotop{
                height: 75px;
                float: left;
            }

            .slogantop{
                height: 75px;
                float: right;
            }

            #footer {
                bottom: 200px;
            }

            /*#footer td {
                padding: 0;
                width: 33%;
            }*/

            .page-number {
                text-align: right;
            }

            hr {
                page-break-after: always;
                border: 0;
            }
            
            table#tableInvoiceR{
                margin-bottom: 180px;
            }

            thead {
                font-weight: bold;
            }

            .invoiceSpec td:nth-child(2),
            .invoiceSpec td:nth-child(3){
                text-align: right;
            }

            .invoiceSpec {
                margin-bottom: 30px;
            }

            .h100 {
                height: 100%;
            }

            .w100 {
                width: 100%;
            }

            .w100p{
                width: 100px;
            }

            .w65p{
                width: 65px;
            }

            .w55p{
                width: 55px;
            }

            .w45p{
                width: 45px;
            }

            .w95 {
                width: 95%;
            }

            .w70 {
                width: 70%;
            }

            .w50 {
                width: 50%;
            }

            .w25{
                width: 25%;
                display: inline-block;
            }

            .w33 {
                width: 33% !important;
            }

            .w30 {
                width: 30%;
            }

            .grey{
                margin-left: 35px !important;
                margin-right: 55px !important;
            }

            .greybalk{
                left: 0 !important;
                right: 0 !important;
                margin-left: -50px !important;
                margin-right: -50px !important;
                background-color: #e9e9e9;
            }

            .padding-bottom-50 {
                padding-bottom: 50px;
            }

            .padding-15 {
                padding: 15px 0;
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


            .txt-top{
                text-align: top;
            }

            .capital{
                text-transform: uppercase !important;
                margin-bottom: 0px;
                margin-top: 0px;
                font-size: 26px;
                font-weight: 900;
            }

            .flleft{
                float: left;
            }

            .flright{
                float: right;
            }

            .textmdl{
                vertical-align: middle !important;
            }

            .lh-1-3{
                line-height: 1.3;
            }

            .lh-1-5{
                line-height: 1.5;
            }

            .lh-1-7{
                line-height: 1.7;
            }

            .contact-right{
                font-size: 15px;
                font-weight: bold;
                padding-left: 70px;
            }

            .ttop{
                vertical-align: top;
            }

            .a-right{
                text-align: right;
            }

            .a-bot{
               vertical-align: bottom;
            }

            .inline{
                display: inline-block;
            }

            .valingtop{
                vertical-align: top;
            }
        </style>
    </head>

    <body>
<?php
$a = $invoice->TotalTax21;
$b = $invoice->TotalTax6;
$string = "a + b";

$result = eval('return ' . preg_replace('/([a-zA-Z0-9])+/', '\$$1', $string) . ';');
?>
        <div id="header">
            <img class="flleft" src="<?= base_url('assets/images/business/commpro/logo.png'); ?>">
            <img class="flright" src="<?= base_url('assets/images/business/commpro/slogan.svg'); ?>">
        </div>
        
        <div id="footer">
            <p <?= $concerns != '' ? 'style="margin-top: -30px"' : null ?>><?= $concerns ?></p>
            <div class="greybalk invoiceSpec">
                <table class="w100 grey invoiceSpec padding-15">
                    <tr>
                        <th colspan="2"><b>totalen excl. BTW</b></th>
                        <th class="a-right"><b>BTW bedrag</b></th>
                        <td class="a-right">betalingsconditie: <b><?=$paymentCondition?></b></td>
                    </tr>
                    <tr>
                        <td class="ttop lh-1-7 w100p">
                            21% BTW<br>
                            9% BTW<br>
                            0% BTW<br>
                        </td>

                        <td class="ttop lh-1-7 w100p">
                            € <?= preg_replace('/\./', ',', $invoice->TotalExTax21) ?><br>
                            € <?= preg_replace('/\./', ',', $invoice->TotalExTax6) ?><br>
                            € <?= preg_replace('/\./', ',', $invoice->TotalExTax0) ?><br>
                            <strong>€ <?= preg_replace('/\./', ',', $invoice->TotalEx) ?></strong><br>
                        </td>

                        <td class="ttop lh-1-7 w100p">
                            € <?= preg_replace('/\./', ',', $invoice->TotalTax21) ?><br>
                            € <?= preg_replace('/\./', ',', $invoice->TotalTax6) ?><br>
                            <br>
                            <b>€ <?= preg_replace('/\./', ',', $result) ?></b>
                        </td>

                        <td class="a-bot a-right lh-1-3">
                            totaal te voldoen voor <b><?= date('d-m-Y', $invoice->ExpirationDate) ?></b>:<br>
                            <p class="capital">€ <?= preg_replace('/\./', ',', $invoice->TotalIn) ?></p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="w25 ttop lh-1-3">
                <?= $business->Name ?><br>
                <?= $business->StreetName ?> <?=$business->StreetNumber?><?=$business->StreetAddition?><br>
                <?=$business->ZipCode?> <?=$business->City?>
            </div>

            <div class="w25 ttop lh-1-3">
                t. <?= $business->PhoneNumber ?><br>
                e. <?= $business->Email ?><br>
                i. <?= $business->Website ?>
            </div>

            <div class="w50 lh-1-3 inline ttop a-right">
                <?php if ($business->IBAN != null): ?>
                    IBAN: <?= $business->IBAN ?><br>
                <?php endif; ?>
                <?php if ($business->BIC != null): ?>
                    BIC: <?= $business->BIC ?><br>
                <?php endif; ?>
                <?php if ($business->KVK != null): ?>
                    KvK: <?= $business->KVK ?><br>
                <?php endif; ?>
                <?php if ($business->BTW != null): ?>
                    BTW nr: <?= $business->BTW ?><br>
                <?php endif; ?>
            </div>

        </div>
        
        <div class="greybalk textmdl">
            <table class="w100 grey padding-15 valingtop">
                <tr>
                    <td class="w50 lh-1-3">
                        <p class="capital">Factuur</p>
                        klantnummer: <?= $customer->Id ?? '-' ?><br>
                        factuurnummer: <?= $invoice->InvoiceNumber ?><br>
                        factuurdatum: <?= date('d-m-Y', $invoice->InvoiceDate) ?><br>
                        uiterste betaaldatum: <?= date('d-m-Y', $invoice->ExpirationDate) ?>
                        <?php if ($invoice->ShortDescription != '') { ?>
                            <br>Betreft: <?= $invoice->ShortDescription ?>
                        <?php } ?>
                    </td>

                    <td class="w50 lh-1-5 contact-right">
                        <?php if ($customer != null) { ?>
                            <?= $customer->Name ?><br>
                            <?php if($invoice->contact != 0){?>
                                <?= getContactName($invoice->contact) ?><br>
                            <?php } else?>
                            <?= $customer->StreetName ?> <?= $customer->StreetNumber ?> <?= $customer->StreetAddition ?><br>
                            <?= $customer->ZipCode ?> <?= $customer->City ?>
                        <?php }else{ ?>
                            <?= $invoice->CompanyName ?? $invoice->FrontName.' '.$invoice->Insertion.' '.$invoice->LastName ?><br>
                            <?php if($invoice->contact != 0){?>
                                <?= getContactName($invoice->contact) ?><br>
                            <?php } else ?>
                            <?= $invoice->Address ?> <?= $invoice->AddressNumber ?> <?= $invoice->AddressAddition ?><br>
                            <?= $invoice->ZipCode ?> <?= $invoice->City ?>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <?php switch ($invoice->TimePeriod) {
            case '7 days': ?>
                <p>Deze factuur heeft betrekking op de week <?= date('W', $invoice->InvoiceDate) ?> (<?= date('d-m-Y', strtotime('Monday this week', $invoice->InvoiceDate)) ?> t/m <?= date('d-m-Y', strtotime('Sunday this week', $invoice->InvoiceDate)) ?>):</p>
            <?php break;
            case '1 month': ?>
                <p>Deze factuur heeft betrekking op de maand <?= strftime('%B', $invoice->InvoiceDate) ?> <?= date('Y', $invoice->InvoiceDate) ?> (<?= date('d-m-Y', strtotime('first day of', $invoice->InvoiceDate)) ?> t/m <?= date('d-m-Y', strtotime('last day of', $invoice->InvoiceDate)) ?>):</p>
            <?php break;
            case '3 months':
                $quarter = ceil(date('m', $invoice->InvoiceDate) / 3);
                if ($quarter == 1) { ?>
                    <p>Deze factuur heeft betrekking op het kwartaal <?= $quarter ?> van <?= date('Y', $invoice->InvoiceDate) ?> (01-01-<?= date('Y', $invoice->InvoiceDate) ?> t/m 31-03-<?= date('Y', $invoice->InvoiceDate) ?>):</p>
                <?php } elseif ($quarter == 2) { ?>
                    <p>Deze factuur heeft betrekking op het kwartaal <?= $quarter ?> van <?= date('Y', $invoice->InvoiceDate) ?> (01-04-<?= date('Y', $invoice->InvoiceDate) ?> t/m 30-06-<?= date('Y', $invoice->InvoiceDate) ?>):</p>
                <?php } elseif ($quarter == 3) { ?>
                    <p>Deze factuur heeft betrekking op het kwartaal <?= $quarter ?> van <?= date('Y', $invoice->InvoiceDate) ?> (01-07-<?= date('Y', $invoice->InvoiceDate) ?> t/m 30-09-<?= date('Y', $invoice->InvoiceDate) ?>):</p>
                <?php } elseif ($quarter == 4) { ?>
                    <p>Deze factuur heeft betrekking op het kwartaal <?= $quarter ?> van <?= date('Y', $invoice->InvoiceDate) ?> (01-10-<?= date('Y', $invoice->InvoiceDate) ?> t/m 31-12-<?= date('Y', $invoice->InvoiceDate) ?>):</p>
                <?php }
            ?>
            <?php break;
            case '1 year': ?>
                <p>Deze factuur heeft betrekking op het jaar <?= date('Y', $invoice->InvoiceDate) ?> (01-01-<?= date('Y', $invoice->InvoiceDate) ?> t/m 31-12-<?= date('Y', $invoice->InvoiceDate) ?>):</p>
            <?php break;
        } ?>

        <table id="tableInvoiceR" class="w100 lh-1-3 padding-15">
            <thead>
                <tr>
                    <td>artikelnr</td>
                    <td>aantal</td>
                    <td>omschrijving</td>
                    <td class="aling-right">prijs</td>
                    <td class="aling-right">korting%</td>
                    <td class="aling-right">totaal</td>
                </tr>
            </thead>
            <?php foreach ($invoiceRules as $invoiceRule) { ?>
                <tr>
                    <td class="valingtop"><?= $invoiceRule->ArticleC ?></td>
                    <td class="valingtop"><?= $invoiceRule->Amount ?></td>
                    <td class="valingtop"><?= $invoiceRule->Description ?>
                        <?php if ($invoiceRule->MetaData != null):
                            $metaDatas = unserialize($invoiceRule->MetaData);
                            $countMetaData = ( count($metaDatas) -1 );
                            echo " ";
                            foreach ($metaDatas as $key => $metaData): ?>
                                <?= $metaData['value'] . ( $key < $countMetaData ? ',' : null ) ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                    <td class="aling-right valingtop">€ <?= preg_replace('/\./', ',', $invoiceRule->Price)  ?></td>
                    <td class="aling-right valingtop"><?= preg_replace('/\./', ',', $invoiceRule->Discount)  ?></td>
                    <td class="aling-right valingtop">€ <?= preg_replace('/\./', ',', $invoiceRule->Total)  ?></td>
                </tr>
            <?php }
            ?>
        </table>

        <?= $invoice->Description ?>

    </body>
</html>
