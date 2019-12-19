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
				margin: 2.5cm 0.8cm 0 0.8cm;
				font-size: 14px;
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

			#header h1{
				margin-top: -10px !important;
				margin-bottom: 0 !important;
				font-size: 26px !important;
				font-weight: 900 !important;
			}

			#header h3{
				margin-top: 0px !important;
				font-size: 17px !important;
				font-weight: 300 !important;
				color: #555;
			}

			/* Add padding to prevent overlapping the signature area */
			.rules{
				padding-bottom: 110px;
			}

			p.m-0{
				margin-top: 0px !important;
				margin-bottom: 0px;
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
				bottom: 0;
				display: block;
				font-size: 0.6em;
			}

			.sign{
				position: absolute;
				left: 0;
				right: 0;
				bottom: 0;
				padding: 15px 5px;
				margin-bottom: 30px;
				width: 100%;
				border: 1px solid black;
			}

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

			thead
			{
				font-size: 13px;
				font-weight: normal !important;
				border-bottom: 1px solid black;
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

			.margin-x-3{
				margin-left: 3px;
				margin-right: 3px;
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

			.align-left {
				text-align: left;
			}

			.align-right {
				text-align: right;
			}

			.padding-right-30{
				padding-right: 30px;
			}


			.txt-top{
				vertical-align: top;
			}

			.capital{
				text-transform: uppercase !important;
				text-align: right !important;
				float: right !important;
				margin-bottom: 0px !important;
				margin-top: 0px !important;
			}

			.header{
				text-decoration: underline;
				font-weight: bold;
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

			.lh-1-0{
				line-height: 1.0;
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

			.ttop,
			.ttop td,
			.ttop th{
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

			.page_break{
				page-break-before: always;
			}

			.price{
				width: 55px;
			}
			.symbol{
				width: 15px;
			}

			.pr-15{
				padding-right: 15px;
			}

			.amount{
				width: 50px;
			}
			#footer .page{
				float: right;
			}
			#footer .page:after{
				content: counter(page);
			}

			table.productTables tbody tr:first-child td{
				padding-top: 10px;
			}

			table.quotationDescription td p{
				margin-top: 0 !important;
			}

			table.quotationDescription td p:last-child{
				margin-bottom: 0 !important;
			}

			.sign-line-left p:after{
				content: ' ';
				display: block;
				margin-left: 60px;
				margin-right: 10px;
				border-bottom: 0.5px dotted black;
			}

			.sign-line-right p,img{
				color: white !important;
				object-fit: contain;
				max-width: 80%;
				height: 150px;
			}

			.sign-line-right p,img:after{
				content: ' ';
				display: block;
				margin-right: 10px;
			}
		</style>
	</head>
	<body>
		<div id="header">
			<div class="w50 inline">
				<img class="flleft" src="<?= base_url('assets/images/business/commpro/logo.png') ?>">
			</div>
			<div class="w50 inline capital ttop">
				<h1>Offerte</h1>
				<h3>N°. <?= $quotation->QuotationNumber ?></h3>
			</div>
		</div>
		<div id="footer">
			<p class="page">Pagina </p>
			<div class="ttop lh-1-3">CommPro Automatisering | Nijverheidsweg 6a | 3381 LM Giessenburg<br>
			t. 0184 - 65 41 27 | e. info@commpro.nl | i. www.commpro.nl</div>
		</div>
		<br>
		<br>
		<p class="lh-1-3 m-0"><?= $quotation->CustomerName ?></p>
		<?php if ($quotation->ContactFirstName != null): ?>
			<p class="lh-1-3 m-0"><b>
				<?php switch ($quotation->ContactSex) {
					case 'male': ?>
						t.a.v. de heer <?= $name['formal'] ?>
						<?php break;
					case 'female': ?>
						t.a.v. mevrouw <?= $name['formal'] ?>
						<?php break;
					default: ?>
						t.a.v. de heer/mevrouw <?= $name['formal'] ?>
						<?php break;
				} ?>
			</b></p>
		<?php endif; ?>
		<p class="lh-1-3 m-0"><?= $contactAddress ?></p>
		<p class="lh-1-3 m-0"><?= $quotation->CustomerZipCode.' '.$quotation->CustomerCity ?></p>
		<?php if (!in_array(strtolower($quotation->CustomerCountry), $country)): ?>
			<p class="lh-1-3 m-0">(<?= $quotation->CustomerCountry ?>)</p>
		<?php endif ?>
		<br>
		<br>
		<p class="lh-1-7 m-0"><?= $business->City ?>, <?= $createdDate ?></p>
		<p class="lh-1-7 m-0">Offerte betreft: <?= $quotation->Subject ?></p>
		<br>
		<br>
		<?php if ($quotation->ContactFirstName != null): ?>
			<p class="lh-1-7 m-0">
				<?php
					if ($quotation->ContactSalutation != 'informal') {
						echo "Geachte ";
						switch ($quotation->ContactSex) {
							case 'male':
								echo "mijnheer";
								break;
							case 'female':
								echo "mevrouw";
								break;
							default:
								echo "mijnheer/mevrouw";
								break;
						}
						echo ' '.$name['formal'].', ';
					}
					echo $quotation->ContactSalutation != 'formal' ? 'Beste '.$name['informal'].',' : null;
				?>
			</p>
		<?php endif; ?>
		<p class="lh-1-7 m-0">U ontvangt deze offerte naar aanleiding van <?= strtolower($quotation->Reason) ?>.</p>
		<br>
		<p class="header">Totaalbedrag offerte</p>
		<p class="lh-1-7 m-0">Het totaalbedrag voor de eenmalige kosten bedraagt: <?= getCosts($quotation)['costs']?> </p>
		<p class="lh-1-7 m-0">Het totaalbedrag voor de terugkomende kosten per maand bedraagt: <?= getCosts($quotation)['recurring']?> </p>
		<p class="lh-1-7 m-0">(Verantwoording van de opbouw van de prijzen vindt u in de bijlagen)</p>
		<br>
		<p class="header">Condities van deze offerte</p>
		<ul>
			<li>Alle prijzen zijn in Euro, netto exclusief BTW.</li>
			<li>Geldigheid offerte: <?= $quotation->ValidDays ?> dagen.</li>
			<li>Levertijd: <?= $quotation->DeliveryTime ?></li>
			<li><?= $paymentCondition->Name ?> <?= $quotation->PaymentTerm ?> dagen</li>
		</ul>
		<br>
		<p>Met vriendelijke groet,</p>
		<p class="l-h-7 m-0"><?= $quotation->CreatorName ?></p>
		<p class="l-h-7 m-0">Commpro Automatisering</p>
		<div class="page_break"></div>
		<div class="rules">
			<?php if ($quotation->WorkDescription != null): ?>
				<p class="header">Omschrijving</p>
				<table class="w100 quotationDescription">
					<tbody>
						<tr>
							<td class="ttop m-0"><?= $quotation->WorkDescription ?></td>
							<?php if ($quotation->WorkAmount > 0){ ?>
								<td class="w45p">&nbsp;</td>
								<td class="symbol align-left a-bot"><strong>€</strong></td>
								<td class="price align-right a-bot"><strong> <?= preg_replace('/\./', ',', $quotation->WorkAmount) ?> </strong></td>
							<?php } ?>
						</tr>
					</tbody>
				</table>
			<?php endif; ?>
			<?php if ($discriptionLength > 999): ?>
					<div class="page_break"></div>
			<?php endif; ?>
			<?php if (count($quotationRulesP) > 1 || (count($quotationRulesP) == 1 && $quotationRulesP[0]->Amount != 0)):?>
				<p class="header">Levering producten</p>
				<table class="w100 productTables ttop">
					<thead>
						<tr>
							<th class="amount">Aantal</th>
							<th>Omschrijving</th>
							<th class="symbol align-left" colspan="2">Prijs p/s</th>
							<th class="symbol align-left">Korting%</th>
							<th class="symbol align-left" colspan="2">Prijs</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($quotationRulesP as $rule):
							if ($rule->Amount <= 0) {
								continue;
							}
							$product = getProduct($rule->ArticleC);
						?>
							<tr>
								<td class="amount"><?= $rule->Amount ?></td>
								<td><?= $rule->ArticleDescription ?>
									<?php if ($rule->MetaData != null):
										$metaDatas = unserialize($rule->MetaData);
										$countMetaData = ( count($metaDatas) -1 );
										echo " ";
										foreach ($metaDatas as $key => $metaData): ?>
											<?= $metaData['value'] . ( $key < $countMetaData ? ',' : null ) ?>
										<?php endforeach; ?>
									<?php endif; ?>
								</td>
								<td class="symbol align-left">€</td>
								<td class="price align-right pr-15"><?= number_format($rule->SalesPrice, 2, ',', '.') ?></td>
								<td class="price align-right pr-15"><?=  number_format($rule->Discount, 2, ',', '.')  ?></td>
								<td class="symbol align-left"><strong>€</strong></td>
								<td class="price align-right"><strong><?= number_format(($rule->Amount * $rule->SalesPrice), 2, ',', '.') ?></strong></td>
							</tr>
							<?php if ($product != null && !empty($product->longDescription)): ?>
								<tr>
									<td>&nbsp;</td>
									<td colspan="5"><?= $product->LongDescription ?></td>
								</tr>
							<?php endif ?>
						<?php endforeach; ?>
					</tbody>
				</table>
				<div class="margin-x-3">
					<?= $quotation->ProductDescription ?>
				</div>
			<?php endif; ?>
			<br>
			<?php if (count($quotationRulesR) > 1 || (count($quotationRulesR) == 1 && $quotationRulesR[0]->Amount != 0)):?>
				<p class="header">Terugkerende kosten per maand</p>
				<?php if ($recurringTableType == 'table'): ?>
					<table class="w100 productTables ttop">
						<thead>
							<tr>
								<th class="amount">Aantal</th>
								<th>Omschrijving</th>
								<th class="symbol align-left" colspan="2">Prijs p/s</th>
								<th class="symbol align-left">Korting%</th>
								<th class="symbol align-left" colspan="2">Prijs</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($quotationRulesR as $rule):
								if ($rule->Amount <= 0) {
									continue;
								}
								$product = getProduct($rule->ArticleC);
							?>
								<tr>
									<td class="amount"><?= $rule->Amount ?></td>
									<td><?= $rule->ArticleDescription ?></td>
									<td class="align-left">€</td>
									<td class="price align-right pr-15"><?= number_format($rule->SalesPrice, 2, ',', '.') ?></td>
									<td class="price align-right pr-15"><?=  number_format($rule->Discount, 2, ',', '.')  ?></td>
									<td class="symbol align-left"><strong>€</strong></td>
									<td class="price align-right"><strong><?= number_format(($rule->Amount * $rule->SalesPrice), 2, ',', '.') ?></strong></td>
								</tr>
								<?php if ($product != null && !empty($product->longDescription)): ?>
									<tr>
										<td></td>
										<td colspan="5"><?= $product->LongDescription ?></td>
									</tr>
								<?php endif ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php elseif ($recurringTableType == 'inline'): ?>
					<table class="w100">
						<tbody>
							<?php foreach ($quotationRulesR as $rule):
								if ($rule->Amount <= 0) {
									continue;
								}
								$product = getProduct($rule->ArticleC);
							?>
								<tr>
									<td><?= $rule->ArticleDescription ?></td>
									<td class="symbol align-left"><strong>€</strong></td>
									<td class="price align-right"><strong><?= number_format(($rule->Amount * $rule->SalesPrice), 2, ',', '.') ?></strong></td>
								</tr>
								<?php if ($product != null && !empty($product->longDescription)): ?>
									<tr>
										<td colspan="3"><?= $product->LongDescription ?></td>
									</tr>
								<?php endif ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
				<div class="margin-x-3">
					<?= $quotation->RecurringDescription ?>
				</div>
			<?php endif; ?>
			<?php if (!empty($quotation->ProjectDescription)): ?>
				<div class="page_break"></div>
				<p><?= $quotation->ProjectDescription ?></p>
			<?php endif; ?>
		</div>
		<div class="sign">
			<div class="w50 inline ttop">
				<div class="sign-line-left"><p>Naam: <?= $quotation->CustomerName ?></p></div>
				<div class="sign-line-left"><p>Datum: <?= date('d-m-Y', strtotime($quotation->CreatedDate)) ?></p></div>
			</div>
			<div class="w50 inline ttop">
				<div><p>Handtekening voor akkoord:</p></div>
				<?php if ($quotation->Signature == NULL): ?>
					<div class="sign-line-right"><p>&nbsp;</p></div>
				<?php else: ?>
					<div class="sign-line-right"><img src="<?= base_url("/uploads/$business->DirectoryPrefix/business/signature/$quotation->Id/$quotation->Signature") ?>"/></div>
				<?php endif; ?>
			</div>
		</div>
	</body>
</html>
