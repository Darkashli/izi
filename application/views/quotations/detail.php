<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Facturen');
define('PAGE', 'customer');

include 'application/views/inc/header.php';
?>

<form method="post">
	<div class="card">
		<div class="card-header card-header-icon card-header-primary">
			<div class="card-icon">
				<i class="material-icons">format_list_numbered</i>
			</div>
			<h4 class="card-title">Offertedetails</h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-7">
					<h4>Omschrijving</h4>
					<div class="row">
						<div class="col-6"><?= $quotation->WorkDescription ?></div>
						<div class="col-6 text-right">€ <?= preg_replace('/\./', ',', $quotation->WorkAmount) ?></div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="row">
						<div class="col-xl-6 ml-auto">
							<a id="checkEmailSent" href="<?= base_url("quotation/mailQuotation/$quotation->Id") ?>" class="btn btn-primary btn-block btn-sm">Verstuur offerte per mail</a>
						</div>
						<div class="w-100"></div>
						<div class="col-xl-6 ml-auto form-group">
							<label class="control-label">Status</label>
							<select class="form-control" id="status" name="status" onchange="checkClosed()">
								<?php foreach ($statusses as $statusKey => $status): ?>
									<option value="<?= $statusKey ?>" <?= $quotation->Status == $statusKey ? 'selected' : null ?>><?= $status ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="w-100"></div>
						<div id="reject" class="col-xl-6 ml-auto form-check <?= $quotation->Status == $latestStatus->Key ? null : 'hidden' ?>">
							<label class="form-check-label">
								<input type="hidden" name="rejected" value="0">
								<input class="form-check-input" type="checkbox" name="rejected" value="1" <?= $quotation->Rejected ? 'checked' : null ?>>
								Afgewezen
								<span class="form-check-sign">
									<span class="check"></span>
								</span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<h4>Levering producten</h4>
			<?php if ($quotationRulesP != null): ?>
				<div class="table-responsive mt-4">
					<table class="table table-striped" width="100%">
						<thead>
							<tr>
								<td class="w10">Aantal</td>
								<td class="w70">Omschrijving</td>
								<td class="w10">Prijs p/s</td>
								<td class="w10">Totaal</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($quotationRulesP as $rule): ?>
								<tr>
									<td><?= $rule->Amount ?></td>
									<td><?= $rule->ArticleDescription ?></td>
									<td><?= number_format($rule->SalesPrice, 2, ',', '.') ?></td>
									<td><?= number_format(($rule->Amount * $rule->SalesPrice), 2, ',', '.') ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else: ?>
				<p class="text-center">Geen resultaten aanwezig</p>
			<?php endif; ?>
			<h4>Terugkerende kosten</h4>
			<?php if ($quotationRulesR != null): ?>
				<div class="table-responsive mt-4">
					<table class="table table-striped" width="100%">
						<thead>
							<tr>
								<td class="w10">Aantal</td>
								<td class="w70">Omschrijving</td>
								<td class="w10">Prijs p/s</td>
								<td class="w10">Totaal</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($quotationRulesR as $rule): ?>
								<tr>
									<td><?= $rule->Amount ?></td>
									<td><?= $rule->ArticleDescription ?></td>
									<td><?= number_format($rule->SalesPrice, 2, ',', '.') ?></td>
									<td><?= number_format(($rule->Amount * $rule->SalesPrice), 2, ',', '.') ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else: ?>
				<p class="text-center">Geen resultaten aanwezig</p>
			<?php endif; ?>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-md-4">
			<button type="submit" class="btn btn-success btn-block">Opslaan</button>
		</div>
	</div>
</form>

<script type="text/javascript">
var latestStatus = <?= $latestStatus->Key ?>;

$(document).ready(function () {

	$('#checkEmailSent').click( function() {
		event.preventDefault();
		var defaultUrl = $(this).attr('href');
		var status = '<?= $quotation->Status ?>';

		if (status !== 'created')
		{
			swal({
				title: 'Weet u het zeker?',
				text: 'U heeft deze offerte reeds verzonden per mail. Weet u zeker dat u deze offerte nogmaals wilt versturen?',
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ja, zend nogmaals!'
			}).then(function (result) {
				if (result.value) {
					window.location.href = defaultUrl;
				}
			}).catch(swal.noop)
		}
		else {
			window.location.href = defaultUrl;
		}
	})

});

function checkClosed() {
	var status = $("#status").val();

	if (status == latestStatus) {
		$("#reject").slideDown();
	}
	else {
		$("#reject").slideUp();
	}
}

</script>

<?php include 'application/views/inc/footer.php'; ?>
