<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Geïmporteerde offertes');
define('PAGE', 'webshop');

include 'application/views/inc/header.php';
?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">description</i>
				</div>
				<h4 class="card-title"><?= TITEL ?></h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover w-100">
						<thead>
							<tr>
								<td>Offertenummer</td>
								<td>Betreft</td>
								<td>Bedrag éénmalig</td>
								<td>Bedrag terugkeerend</td>
								<td>Status</td>
								<td>Acties</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($quotations as $quotation) { ?>
								<tr>
									<td><?= $quotation->QuotationNumber; ?></td>
									<td><?= $quotation->Subject; ?></td>
									<td><?= preg_replace('/\./', ',', getCosts($quotation)['costs']) ?></td>
									<td><?= preg_replace('/\./', ',', getCosts($quotation)['recurring']) ?></td>
									<td><?= getQuotationStatus($quotation->Status, $quotation->BusinessId) ?></td>
									<td class="td-actions text-right">
										<a href="<?= base_url("quotation/update/$quotation->Id") ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Bewerken"><i class="material-icons">edit</i></a>
										<a href="<?= base_url("quotation/downloadQuotation/$quotation->Id") ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Download offerte"><i class="material-icons">file_download</i></a>
										<a href="<?= base_url("quotation/convertToInvoice/$quotation->Id") ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Zet om naar factuur"><i class="material-icons">file_upload</i></a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'application/views/inc/footer.php'; ?>
