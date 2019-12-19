<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Geïmporteerde orders');
define('PAGE', 'customer');

include 'application/views/inc/header.php';
?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">local_offer</i>
				</div>
				<h4 class="card-title"><?= TITEL ?></h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover w-100">
						<thead>
							<tr>
								<td>Ordernummer</td>
								<td>Orderdatum</td>
								<td>Aantal bestelregels</td>
								<td>&nbsp;</td>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($orders as $order) { ?>
							<tr data-href="<?= base_url("SalesOrders/editorder/$order->Id") ?>">
								<td><?= $order->OrderNumber ?></td>
								<td><?= date('d-m-Y', strtotime($order->OrderDate)); ?></td>
								<td><?= getCountSalesorderRules($order->Id) ?></td>
								<td class="td-actions text-right">
									<a href="<?= base_url('SalesOrders/editorder/'.$order->Id) ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Aanpassen">
										<i class="material-icons">edit</i>
									</a>
									<a href="<?= base_url('SalesOrders/salesorderpdf/'.$order->Id) ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Download pakbon">
										<i class="material-icons">picture_as_pdf</i>
									</a>
									<button type="button" onclick="promoteWarning(<?= $order->Id ?>)" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Zet om naar factuur">
										<i class="material-icons">file_upload</i>
									</button>
									<a href="<?= base_url('SalesOrders/salesordercsv/'.$order->Id) ?>" class="btn btn-info btn-round btn-fab btn-fab-mini" title="Exporteer als CSV">
										<i class="material-icons">import_export</i>
									</a>
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
