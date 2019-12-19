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

            .grey{
                margin-left: 50px !important;
                margin-right: 50px !important;
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

            .koptext1{
                margin-bottom: 0px;
                margin-top: 0px;
                font-size: 24px;
                font-weight: 900;
                display: inline-block;
                padding-top: 20px !important;
                padding-bottom: -7px !important;
            }

            .uren{
                margin-left: 50px;
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
        <div id="header">
            
                <img class="flleft" src="<?= base_url('assets/images/business/commpro/logo.png'); ?>">
                <img class="flright" src="<?= base_url('assets/images/business/commpro/slogan.svg'); ?>">
            
        </div>
        
        <div id="footer">
            <div class="page-number"></div>
        </div>
        
        <div class="textmdl">
            <table class="w100 padding-15">
                <tr>
                    <td class="w50 lh-1-3">
                        <p class="capital">Werkbon</p>
                        klantnummer: <?= $customer->Id ?? '_' ?><br>
                        behorend bij factuurnummer: <?= $invoice->InvoiceNumber ?><br>
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

        <?php
        $ticketIds = unserialize($invoice->WorkOrder);

        $businessId = $this->session->userdata('user')->BusinessId;
        
        foreach ($ticketIds as $ticketId) {
        $ticket = $this->Tickets_model->getTicket($ticketId, $businessId)->row();
        $ticketRules = $this->Tickets_model->getTicketRules($ticketId, $businessId)->result();
        $first = getFirstTicketRule($ticketId); ?>

        <div class="greybalk">
            <div class="w100 grey">
                <p class="koptext1">Uw melding bij onze supportafdeling</p>
                <?= $ticket->CustomerNotification ?>
            </div>
        </div>

        <?php foreach ($ticketRules as $ticketRule) { ?>
            <p class="koptext1">Update ticket: <?= date('d-m-Y', $ticketRule->Date) ?> </p><p class="koptext1 uren"> <?= date('H:i', $ticketRule->StartWork) ?> - <?= date('H:i', $ticketRule->EndWork) ?></p>
            <p><b>Arbeid uitgevoerd door:</b><?= getAccountName($ticketRule->UserIdLink) ?></p>
            <p><b>Omschrijving werkzaamheden:</b></p>
            <p><?= $ticketRule->ActionUser; ?></p>
        <?php }} ?>
    </body>
</html>
