<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <META HTTP-EQUIV="Content-Type" content="text/html; charset=utf-8"/>
        <style>

            @page {
                margin: 1cm;
                counter-increment: page;
            }

            html, body {
                height: 100%;
            }

            body {
                margin: 2.5cm 0;
                text-align: justify;
                font-size: 15px;
                font-family: tahoma, Helvetica, Arial, Verdana;
            }

            /*#header,
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
            }*/

            .page-number {
                text-align: right;
            }

            .page-number:before {
                content: "Pagina " counter(page);
            }

            hr {
                page-break-after: always;
                border: 0;
            }

            .border-btm {
                border-bottom: 0.1pt solid #7B7B7B;
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
                float: left;
                position: relative;
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

            .height-300 {
                height: 300px;
                max-height: 300px;
            }

            .img-center {
                margin: 0 auto;
            }

            #main,
            #foto_tabel{
                width: 700px;
                min-height: 300px;
                margin: 0 auto;
                position: relative;
            }
            #foto_tabel{
                margin-top: 20px;
            }
            .text_label{
                width: 50%;
            }

            .text_field{
                width: 50%;
            }

            #foto {
                width: 46%;
                padding: 2%;
                position: relative;
            }

            #foto img {
                vertical-align: top;
            }

            #foto p {
                min-height: 50px;
                height: 50px;
                width: 260px;
            }

            .page-break:first-child {
                page-break-before: auto!important;
            }
            
            .page-break {
                page-break-before: always;
            }
            
            .page-avoid {
                page-break-inside: avoid;
            }
            
        </style>
    </head>

    <body>
        <div id="header">
            <table class="w100">
                <tr>
                    <td><img src="<?= base_url('assets/images/business/trischarbv/logo.jpg') ?>"></td>
                </tr>
            </table>
        </div>

        <div id="footer">
            <div class="page-number"></div>
        </div>

        <table class="w100 invoiceAdres">
            <tr>
                <td class="w50"><p><b>WERKBON</b></p></td>
                <td class="w50"><p>Werkzaamheden verricht voor:</p>
                    <?= $customer->Name; ?><br />
                    <?= $customer->StreetName ?> <?= $customer->StreetNumber ?><?= $customer->StreetAddition; ?><br />
                    <?= $customer->ZipCode ?> <?= $customer->City; ?>
                </td>
            </tr>
        </table>

        <?php
        $ticketIds = unserialize($invoice->WorkOrder);

        $businessId = $this->session->userdata('user')->BusinessId;

        foreach ($ticketIds as $ticketId) {
            echo '<div class="page-break">';
            $ticket = $this->Tickets_model->getTicket($ticketId, $businessId)->row();

            $ticketRules = $this->Tickets_model->getTicketRules($ticketId, $businessId)->result();

            $first = getFirstTicketRule($ticketId);
            ?>
            <div class="w100">
                <p class="border-btm"><b>Melding klant:</b></p>
                <?= $ticket->CustomerNotification; ?>    <br /><br />


            </div>

            <?php foreach ($ticketRules as $ticketRule) { ?>
                <div class="w100 page-avoid">
                    <p class="border-btm"><b>Datum: <?= date('d-m-Y', $ticketRule->Date); ?></b></p>
                    <p><b>Arbeid uitgevoerd door:</b> <?= getAccountName($ticketRule->UserIdLink); ?></p>
                    <p><b>Omschrijving werkzaamheden:</b><br />
                        <?= $ticketRule->ActionUser; ?> </p>
                    <p>Begintijd: <?= date('H:i', $ticketRule->StartWork); ?> Eindtijd: <?= date('H:i', $ticketRule->EndWork); ?> Totaal: <?= $ticketRule->TotalWork; ?> uur</p><br /><br />
                </div>

                <?php
            }
            
            echo "</div>";
        }
        ?>



    </body>
</html>
