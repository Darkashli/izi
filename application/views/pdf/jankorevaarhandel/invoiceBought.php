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
                bottom: 175px;
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

            .top100{
                margin-top: 100px;
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
        </style>
    </head>

    <body>
    <?php 
        $businessId = $this->session->userdata('user')->BusinessId;
        $supplier = $this->Supplier_model->getSupplier($invoice->SupplierId, $businessId)->row();
        $invoiceRules = $this->Invoice_model->getInvoiceSupplierRule($invoice->Id, $businessId)->result();
    ?>
        <div id="header">
             <table class="w100 padding-15">
                <tr>
                    <td class="ttop w50 lh-1-3">
                        <p class="capital">Inkoopfactuur</p>
                        factuurnummer leverancier: <?= $invoice->InvoiceNumber ?><br>
                        factuurdatum: <?= date('d-m-Y', $invoice->InvoiceDate) ?><br>
                        datum betaald: <?= $invoice->PaymentDate ? date('d-m-Y', $invoice->PaymentDate) : ''; ?><br>
                        uiterste betaaldatum: <?= $invoice->PurchaseNumber ?>

                    </td>

                    <td class="w50 lh-1-5 contact-right">
                        <?php if ($supplier != null) { ?>
                            <b><?= $supplier->Name ?><br>
                            <?= $supplier->StreetName ?> <?= $supplier->StreetNumber ?> <?= $supplier->StreetAddition ?><br>
                            <?= $supplier->ZipCode ?> <?= $supplier->City ?></b><br>
                            <br>
                            <?php if($supplier->BTW !== NULL){?>
                                BTW nr: <?= $supplier->BTW ?><br>
                            <?php }?>

                            <?php if($supplier->IBAN !== NULL){?>
                                IBAN: <?= $supplier->IBAN ?><br>
                            <?php }?>

                            <?php if($supplier->KVK !== NULL){?>
                                KVK: <?= $supplier->KVK ?><br>
                            <?php }?>
                        <?php }else{ ?>
                            <?= $invoice->CompanyName ?? $invoice->FrontName.' '.$invoice->Insertion.' '.$invoice->LastName ?><br>
                            <?= $invoice->Address ?> <?= $invoice->AddressNumber ?><?= $invoice->AddressAddition ?><br>
                            <?= $invoice->ZipCode ?> <?= $invoice->City ?>
                        <?php } ?>
                    </td>
                </tr>
            </table>           
        </div>

        <table class="w100 top100 lh-1-3 padding-15">
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
                    <td><?= $invoiceRule->ArticleC ?></td>
                    <td><?= $invoiceRule->Amount ?></td>
                    <td><?= $invoiceRule->Description ?></td>
                    <td class="aling-right">€ <?= preg_replace('/\./', ',', $invoiceRule->Price)  ?></td>
                    <td class="aling-right"><?= preg_replace('/\./', ',', $invoiceRule->Discount)  ?></td>
                    <td class="aling-right">€ <?= preg_replace('/\./', ',', $invoiceRule->Total)  ?></td>
                </tr>
            <?php }
            ?>
        </table>

        <?php if ($invoice->PeriodDateFrom != 0 && $invoice->PeriodDateTo != 0) { ?>
            <p>Periode van <?= date('d-m-Y', $invoice->PeriodDateFrom) ?> tot <?= date('d-m-Y', $invoice->PeriodDateTo) ?></p>
        <?php } ?>
        <?= $invoice->Description ?>


        <div id="footer" class="greybalk">
            <table class="w100 grey invoiceSpec padding-15">
                    <tr>
                        <th colspan="2"><b>totalen excl. BTW</b></th>
                        <th class="a-right"><b>BTW bedrag</b></th>
                    </tr>
                    <tr>
                        <td class="ttop lh-1-7 w65p">
                            21% BTW<br>
                            9% BTW<br>
                            0% BTW<br>
                        </td>

                        <td class="ttop lh-1-7 w55p">
                            € <?= preg_replace('/\./', ',', $invoice->TotalExTax21) ?><br>
                            € <?= preg_replace('/\./', ',', $invoice->TotalExTax6) ?><br>
                            € <?= preg_replace('/\./', ',', $invoice->TotalExTax0) ?><br>
                            <strong>€ <?= preg_replace('/\./', ',', $invoice->TotalEx) ?></strong><br>
                        </td>
<?php 
$a = $invoice->TotalTax21;
$b = $invoice->TotalTax6;
$string = "a + b";

$result = eval('return ' . preg_replace('/([a-zA-Z0-9])+/', '\$$1', $string) . ';');
?>
                        <td class="ttop lh-1-7 w100p">
                            € <?= preg_replace('/\./', ',', $invoice->TotalTax21) ?><br>
                            € <?= preg_replace('/\./', ',', $invoice->TotalTax6) ?><br>
                            <br>
                            <b>€ <?= preg_replace('/\./', ',', $result) ?></b>
                        </td>

                        <td class="a-bot a-right lh-1-3">
                            totaal inclusief BTW<br>
                            <p>€ <?= preg_replace('/\./', ',', $invoice->TotalIn) ?></p> 
                        </td>
                    </tr>
                </table>
        </div>
    </body>
</html>
