<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Offerte');
define('PAGE', 'quotation');

include 'application/views/inc/header_empty.php';
?>

<form method="POST" enctype="multipart/form-data">
	<div class="card offerte-body">
		<div class="card-header card-header-icon card-header-primary">
			<div class="card-icon">
				<i class="material-icons">format_list_numbered</i>
			</div>
				<h4 class="card-title">Offertedetails</h4>
		</div>
	<div class="card-body">
		<div class="row mt-4">
			<div class="col-4">
				<img class="" src="<?= base_url('assets/images/business/commpro/logo.png') ?>">
			</div>
			<div class="col-8 text-right">
				<h1>Offerte</h1>
				<h3>N°. <?= $quotation->QuotationNumber ?></h3>
			</div>
		</div>

		<p class="mt-5"><?= $quotation->CustomerName ?></p>
		<?php if ($quotation->ContactFirstName != null): ?>
			<p class="m-0"><b>
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
		<p class="m-0"><?= $contactAddress ?> <?= $customerHouseNumber ?></p>
		<p class="m-0"><?= $quotation->CustomerZipCode.' '.$quotation->CustomerCity ?></p>

		<?php if (!in_array(strtolower($quotation->CustomerCountry), $country)): ?>
			<p class="m-0">(<?= $quotation->CustomerCountry ?>)</p>
		<?php endif ?>

		<p class="pt-4"><?= $business->City ?>, <?= $createdDate ?>
		<br>
		Offerte betreft: <?= $quotation->Subject ?></p>

		<?php if ($quotation->ContactFirstName != null): ?>
			<p class="mt-4 mb-0">
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
		<p class="m-0">U ontvangt deze offerte naar aanleiding van <?= strtolower($quotation->Reason) ?>.</p>

		<p class="mt-4"><b>Totaalbedrag offerte:</b></p>
		<p class="m-0">Het totaalbedrag voor de eenmalige kosten bedraagt: <?= getCosts($quotation)['costs']?> </p>
		<p class="m-0">Het totaalbedrag voor de terugkomende kosten per maand bedraagt: <?= getCosts($quotation)['recurring']?> </p>
		<p class="m-0">(Verantwoording van de opbouw van de prijzen vindt u in de bijlagen)</p>

		<p class="mt-4"><b>Condities van deze offerte:</b></p>
		<ul>
			<li>Alle prijzen zijn in Euro, netto exclusief BTW.</li>
			<li>Geldigheid offerte: <?= $quotation->ValidDays ?> dagen.</li>
			<li>Levertijd: <?= $quotation->DeliveryTime ?></li>
			<?php if($paymentCondition != null): ?>
			<li> <?php echo $paymentCondition->Name; ?> <?= $quotation->PaymentTerm ?> dagen</li>
			<?php endif ?>
		</ul>

		<br>

		<p>Met vriendelijke groet,</p>
		<p class="m-0"><?= $quotation->CreatorName ?></p>
		<p class="m-0">Commpro Automatisering</p>

		<div class="page_break mt-4"></div>

		<div class="rules">
			<?php if ($quotation->WorkDescription != null): ?>
				<p class="header">Omschrijving</p>
					<table class="w100 quotationDescription">
						<tbody>
							<tr>
								<td class="ttop m-0"><?= $quotation->WorkDescription ?></td>
								<?php if ($quotation->WorkAmount > 0){ ?>
									<td class="w45p">&nbsp;</td>
									<td class="price align-right a-bot"><strong>€ <?= preg_replace('/\./', ',', $quotation->WorkAmount) ?> </strong></td>
								<?php } ?>
							</tr>
						</tbody>
					</table>
			<?php endif; ?>

			<?php if ($discriptionLength > 999): ?>
					<div class="page_break"></div>
			<?php endif; ?>

			<?php if (count($quotationRulesP) > 1 || (count($quotationRulesP) == 1 && $quotationRulesP[0]->Amount != 0)):?>
				<p class="mt-4"><b>Levering producten:</b></p>
				<table class="w100 productTables ttop">
					<thead>
						<tr>
							<th class=""><u>Aantal</u></th>
							<th><u>Omschrijving</u></th>
							<th class="align-left"><u>Prijs p/s</u></th>
							<th class="align-left"><u>Korting%</u></th>
							<th class="align-left"><u>Prijs</u></th>
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
								<td class=""><?= $rule->Amount ?></td>
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

								<td class="price align-right pr-15">€ <?= number_format($rule->SalesPrice, 2, ',', '.') ?></td>
								<td class="price align-left pr-15">€ <?=  number_format($rule->Discount, 2, ',', '.')  ?></td>

								<td class="price align-right"><strong>€ <?= number_format(($rule->Amount * $rule->SalesPrice), 2, ',', '.') ?></strong></td>
							</tr>
							<?php if ($product != null && !empty($product->longDescription)): ?>
								<tr>
									<td>&nbsp;</td>
									<td><?= $product->LongDescription ?></td>
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
				<p class="mt-4"><b>Terugkerende kosten per maand:</b></p>
				<?php if ($recurringTableType == 'table'): ?>
					<table class="w100 productTables ttop">
						<thead>
							<tr>
								<th class="amount">Aantal</th>
								<th>Omschrijving</th>
								<th class="align-left">Prijs p/s</th>
								<th class="align-left">Korting%</th>
								<th class="align-left">Prijs</th>
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

									<td class="price align-right pr-15">€ <?= number_format($rule->SalesPrice, 2, ',', '.') ?></td>
									<td class="price align-right pr-15">€ <?=  number_format($rule->Discount, 2, ',', '.')  ?></td>

									<td class="price align-right"><strong>€ <?= number_format(($rule->Amount * $rule->SalesPrice), 2, ',', '.') ?></strong></td>
								</tr>
								<?php if ($product != null && !empty($product->longDescription)): ?>
									<tr>
										<td></td>
										<td><?= $product->LongDescription ?></td>
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
									<td class="price align-right"><strong>€ <?= number_format(($rule->Amount * $rule->SalesPrice), 2, ',', '.') ?></strong></td>
								</tr>
								<?php if ($product != null && !empty($product->longDescription)): ?>
									<tr>
										<td><?= $product->LongDescription ?></td>
									</tr>
								<?php endif ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
				<div class="margin-x-3 mt-4">
					<?= $quotation->RecurringDescription ?>
				</div>
			<?php endif; ?>
			<?php if (!empty($quotation->ProjectDescription)): ?>
				<div class="page_break"></div>
				<p><?= $quotation->ProjectDescription ?></p>
			<?php endif; ?>
		</div>

		<hr class="offerte-split">

				<div class="row mt-2">
					<div class="col-md-6 font-weight-bold">
						<p class="m-0">Naam: <?= $quotation->ContactFirstName ?> <?= $quotation->ContactLastName ?></p>
						<p class="m-0">Datum: <?= date('d-m-Y', strtotime($quotation->CreatedDate)) ?></p>
						<p class="m-0">Handtekening zetten: </p>
					</div>

					<div class="col-md-6 mt-2">
							<div id="signature-pad" class="signature-pad text-center y-4 ">
								<div class="signature-pad--body" id="signature-pad--body">
									<canvas></canvas>
								</div>
								<div class="signature-pad--footer">
									<button type="button" class="btn btn-default btn-sm" data-action="clear">Wissen</button>
									<a id="" href="<?= base_url("quotation/mailQuotationAfterSign/$quotation->Id/{$this->uri->segment(4)}") ?>" class="btn btn-primary btn-sm"  name="signature" data-action="save">Akkoord</a>
								</div>
						    </div>
					</div>
				</div>

		<div class="mt-5 text-center">CommPro Automatisering | Nijverheidsweg 6a | 3381 LM Giessenburg<br>
		t. 0184 - 65 41 27 | e. info@commpro.nl | i. www.commpro.nl</div>

		</div>
	</div>
</form>


<!-- Signature Pad -->
<script src="/assets/js/signature_pad/signature_pad.js"></script>

<script>
	var wrapper = document.getElementById("signature-pad");
	var clearButton = wrapper.querySelector("[data-action=clear]");
	var saveButton = wrapper.querySelector("[data-action=save]");
	var canvas = wrapper.querySelector("canvas");
	var signaturePad = new SignaturePad(canvas);

	// Adjust canvas coordinate space taking into account pixel ratio,
	// to make it look crisp on mobile devices.
	// This also causes canvas to be cleared.
	function resizeCanvas() {
  	// When zoomed out to less than 100%, for some very strange reason,
  	// some browsers report devicePixelRatio as less than 1
  	// and only part of the canvas is cleared then.
  	var ratio =  Math.max(window.devicePixelRatio || 1, 1);

  	// This part causes the canvas to be cleared
  	canvas.width = canvas.offsetWidth * ratio;
  	canvas.height = canvas.offsetHeight * ratio;
  	canvas.getContext("2d").scale(ratio, ratio);

	// This library does not listen for canvas changes, so after the canvas is automatically
	// cleared by the browser, SignaturePad#isEmpty might still return false, even though the
	// canvas looks empty, because the internal data of this library wasn't cleared. To make sure
	// that the state of this library is consistent with visual state of the canvas, you
	// have to clear it manually.
  	signaturePad.clear();
	}

	// On mobile devices it might make more sense to listen to orientation change,
	// rather than window resize events.
	window.onresize = resizeCanvas;
	resizeCanvas();

	// function download(dataURL, filename) {
	//   var blob = dataURLToBlob(dataURL);
	//   var url = window.URL.createObjectURL(blob);

	//   var a = document.createElement("a");
	//   a.style = "display: none";
	//   a.href = url;
	//   a.download = filename;

	//   document.body.appendChild(a);
	//   a.click();

	//   window.URL.revokeObjectURL(url);
	// }

	// One could simply use Canvas#toBlob method instead, but it's just to show
	// that it can be done using result of SignaturePad#toDataURL.
	function dataURLToBlob(dataURL) {
	// Code taken from https://github.com/ebidel/filer.js
	var parts = dataURL.split(';base64,');
	var contentType = parts[0].split(":")[1];
	var raw = window.atob(parts[1]);
	var rawLength = raw.length;
	var uInt8Array = new Uint8Array(rawLength);

	for (var i = 0; i < rawLength; ++i) {
		uInt8Array[i] = raw.charCodeAt(i);
	}

  	return new Blob([uInt8Array], { type: contentType });
	}

	clearButton.addEventListener("click", function (event) {
	signaturePad.clear();
	});

	saveButton.addEventListener("click", function (event) {

	if (signaturePad.isEmpty()) {
		event.preventDefault();
		swal.fire({
		title: 'Let op!',
		text: 'Zet eerst uw handtakening.',
		type: 'warning',

		});
	} else {
		$.post("/quotation/sign/<?= $this->uri->segment(3); ?>/<?= $this->uri->segment(4); ?>", {URL:signaturePad.toDataURL()}, function (data) {
		})
	}
	});
</script>

<?php include 'application/views/inc/footer_empty.php'; ?>
