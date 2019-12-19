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
                margin: 1.5cm 0 0 0;
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
                border-bottom: 0.1pt solid #aaa;
            }

            #footer {
                bottom: 0;
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
                height: 810px;
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
            <table>
                <tr>
                    <td>Overzicht inkoopfacturen van periode van <?= $from; ?> tot en met <?= $to; ?></td>
                </tr>
            </table>
        </div>

        <table class="w100"> 
            <?php
            $businessId = $this->session->userdata('user')->BusinessId;

            foreach ($invoices as $invoice) {
                $supplier = $this->Supplier_model->getSupplier($invoice->SupplierId, $businessId)->row();

                $invoiceRules = $this->Invoice_model->getInvoiceSupplierRule($invoice->Id, $businessId)->result();
                ?>


                <tr>
                    <?php if ($supplier != null) { ?>
                        <td colspan="4">Leverancier: <?= $supplier->Name; ?>, <?= $supplier->StreetName; ?>, <?= $supplier->ZipCode; ?> <?= $supplier->City; ?></td>
                    <?php }else{ ?>
                        <td colspan="4">Leverancier: <?= $invoice->CompanyName ?? $invoice->FrontName.' '.$invoice->Insertion.' '.$invoice->LastName; ?>, <?= $invoice->Address; ?>, <?= $invoice->ZipCode; ?> <?= $invoice->City; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td colspan="4">
                        <?php if ($supplier != null) { ?>
                            BTW: <?= $supplier->BTW; ?>, IBAN: <?= $supplier->IBAN; ?>, KVK: <?= $supplier->KVK; ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>Factuurdatum: <?= date('d-m-Y', $invoice->InvoiceDate); ?></td>
                    <td>Betaaldatum: <?= $invoice->PaymentDate ? date('d-m-Y', $invoice->PaymentDate) : ''; ?></td>
                    <td>Factuurnummer: <?= $invoice->InvoiceNumber; ?></td>
                    <td>Inkoopnummer: <?= $invoice->PurchaseNumber; ?></td>
                </tr>
                <tr>
                    <td>Aantal</td>
                    <td>Omschrijving</td>
                    <td>Prijs</td>
                    <td>Totaal prijs</td>
                </tr>
                <?php foreach ($invoiceRules as $invoiceRule) { ?>
                    <tr>
                        <td><?= $invoiceRule->Amount; ?></td>
                        <td><?= $invoiceRule->Description; ?></td>
                        <td><?= $invoiceRule->Price; ?></td>
                        <td><?= $invoiceRule->Total; ?></td>
                    </tr>
                <?php }
                ?>

                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">Totaal BTW 21%</td>
                    <td><?= $invoice->TotalTax21; ?></td>

                    <td>Totaal excl. BTW</td>
                    <td><?= $invoice->TotalEx; ?></td>
                </tr>
                <tr>
                    <td colspan="2">Totaal BTW 9%</td>
                    <td><?= $invoice->TotalTax6; ?></td>

                    <td>Totaal incl. BTW</td>
                    <td><?= $invoice->TotalIn; ?></td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>


            <?php } ?>
        </table>

    </body>
</html>
