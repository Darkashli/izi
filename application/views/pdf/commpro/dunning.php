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
                bottom: 40px;
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

            thead {
                font-weight: bold;
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
        </style>
    </head>

    <body>
<?php 

?>
        <div id="header">
                <img class="flleft" src="<?= base_url('assets/images/business/commpro/logo.png') ?>">
                <img class="flright" src="<?= base_url('assets/images/business/commpro/slogan.svg'); ?>">
        </div>
        
        <div id="footer">
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
                IBAN: <?= $business->IBAN ?><br>
                BIC: <?= $business->BIC ?><br>
                KvK: <?= $business->KVK ?><br>
                BTW nr: <?= $business->BTW ?><br>
            </div>

        </div>

        <div class="textmdl">
            <table class="w100 padding-15">
                <tr>
                    <td class="w50 lh-1-3">
                        <p class="capital">Aanmaning</p>
                        Datum aanmaning: <?= date('d-m-Y') ?>
                        <?php if ( isset($customer->Id) ): ?>
                            <br>
                            Klantnummer: <?= $customer->Id ?>
                        <?php endif; ?>
                    </td>

                    <td class="w50 lh-1-5 contact-right">
                        <?= $customer->Name ?><br>
                        <?= $customer->StreetName ?> <?= $customer->StreetNumber ?> <?= $customer->StreetAddition ?><br>
                        <?= $customer->ZipCode ?> <?= $customer->City ?>
                    </td>
                </tr>
            </table>
        </div>
        

        <div class="w100 lh-1-3 padding-15">
            <p>Beste klant,</p>
            <p>Hierbij treft u een overzicht van de volgens onze administratie nog openstaande posten. Wij
                verzoeken u dit overzicht te controleren en de vervallen facturen aan ons over te maken.</p>

            <p>Komen er facturen op dit overzicht voor, die volgens u niet correct zijn, dan verzoeken wij u contact
            op te nemen met ons.</p>
        </div>

        <div class="greybalk">
            <table class="w100 grey padding-15">
                <thead>
                <tr>
                    <td>factuurdatum</td>
                    <td>vervaldatum</td>
                    <td>factuurnummer</td>
                    <td class="aling-right">factuurbedrag</td>
                    <td class="aling-right">Reeds betaald</td>
                    <td class="aling-right">Nog te betalen</td>
                    <td class="aling-right">vervallen</td>
                </tr>
            </thead>

            <?php
            $total = 0;
            $totalToPay = 0;
            foreach ($invoiceIds as $invoiceId) {
                $invoice = $this->Customers_invoicesmodel->getInvoice($invoiceId, $business->Id)->row();
                $payments = $this->Customers_invoicesmodel->getPayments($invoiceId, $business->Id)->result();

                $paid = '0';

                foreach ($payments as $payment)
                {
                    $paid = $paid + $payment->Amount;
                }

                $totalAmount = $invoice->TotalIn;
                $toPay = $totalAmount - $paid;

                $totalPrint = number_format($totalAmount, 2, ',', '');
                $paidPrint = number_format($paid, 2, ',', '');
                $toPayPrint = number_format($toPay, 2, ',', '');
                ?>

                <tr>
                    <td><?= date('d-m-Y', $invoice->InvoiceDate); ?></td>
                    <td><?= date('d-m-Y', $invoice->ExpirationDate); ?></td>
                    <td><?= $invoice->InvoiceNumber; ?></td>
                    <td class="aling-right">€ <?= $totalPrint ?></td>
                    <td class="aling-right">€ <?= $paidPrint ?></td>
                    <td class="aling-right">€ <?= $toPayPrint ?></td>
                    <td class="aling-right"><?= (round((strtotime(date('d-m-Y')) - strtotime(date('d-m-Y', $invoice->ExpirationDate))) / 60 / 60 / 24, 0, PHP_ROUND_HALF_UP) < 0) ? '0 dagen' : round((strtotime(date('d-m-Y')) - strtotime(date('d-m-Y', $invoice->ExpirationDate))) / 60 / 60 / 24) . ' dagen'; ?></td>
                </tr>
                <?php
                $total += $toPay;
                if (round((strtotime(date('d-m-Y')) - strtotime(date('d-m-Y', $invoice->ExpirationDate))) / 60 / 60 / 24, 0, PHP_ROUND_HALF_UP) < 0) {
                    
                } else {
                    $totalToPay += $toPay;
                }
            }
            ?>

            <tr>
                <td colspan="7">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="7">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="5">Saldo totaal</td>
                <td class="aling-right">€ <?= number_format($total, 2, ',', '')?></td>
            </tr>
            <tr>
                <td colspan="5">Totaal vervallen facturen</td>
                <td class="aling-right"><b>€ <?= number_format($totalToPay, 2, ',', '') ?></b></td>
            </tr>
            <tr>
                <td colspan="7">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="7">&nbsp;</td>
            </tr>
            </table>
        </div>

        <p>Wij verzoeken u de vervallen bedragen binnen 8 dagen aan ons over te maken op IBAN nummer: <b><?= $business->IBAN; ?></b>.</p>

        <p>Voorkom te late betaling. Mocht uw betaling reeds onderweg zijn dan kan deze brief als niet
            verzonden beschouwd worden.</p>
        <br>
        <p>Met vriendelijke groet,</p>
        <p>CommPro Automatisering</p>
        
    </body>
</html>
