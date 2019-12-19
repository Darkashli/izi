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

            table tfoot {
                font-weight: bold;
            }

        </style>
    </head>

    <body>
        <div id="header">
            <table>
                <tr>
                    <td>Overzicht activiteiten van <?= getAccountName($userId); ?> in periode van <?= $from; ?> tot en met <?= $to; ?></td>
                </tr>
            </table>
        </div>

        <table class="w100"> 
            <thead>
                <tr>
                    <td>Datum</td>
                    <td>Tijd</td>
                    <td>Klantnaam</td>
                    <td>Omschrijving werkzaamheden</td>
                    <td>Status</td>
                    <td>Gefactureerd</td>
                    <td>Gearchiveerd</td>
                    <td>Zonder actie</td>
                    <td>Totaal</td>
                </tr>
            </thead>

            <?php
            $totalInvoiced = 0;
            $totalArchived = 0;
            $totalOther = 0;

            foreach ($activities as $activity) {
                $status = getStatus($activity->Status);
                ?>
                <tr>
                    <td><?= date('d-m-Y', $activity->Date); ?></td>
                    <td><?= date('H:i', $activity->StartWork); ?> - <?= date('H:i', $activity->EndWork); ?></td>
                    <td><?= getCustomerName($activity->CustomerId); ?></td>
                    <td><?= trim_text($activity->ActionUser, 50, true, true); ?></td>
                    <td><?= $status->Description; ?></td>
                    <td><?php
                        if ($activity->TicketStatus == 1) {
                            echo $activity->TotalWork;
                            $totalInvoiced += $activity->TotalWork;
                        }
                        ?></td>
                    <td><?php
                        if ($activity->TicketStatus == 2) {
                            echo $activity->TotalWork;
                            $totalArchived += $activity->TotalWork;
                        }
                        ?></td>
                    <td><?php
                        if ($activity->TicketStatus == 0) {
                            echo $activity->TotalWork;
                            $totalOther += $activity->TotalWork;
                        }
                        ?></td>
                    <td><?= $activity->TotalWork; ?></td>
                </tr>
            <?php } ?>
            <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><?= $totalInvoiced; ?></td>
                    <td><?= $totalArchived; ?></td>
                    <td><?= $totalOther; ?></td>
                    <td><?= $totalInvoiced + $totalArchived + $totalOther; ?> </td>
                </tr>
            </tfoot>
        </table>

    </body>
</html>