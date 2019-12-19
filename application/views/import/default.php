<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Bank import');
define('PAGE', 'import');

include 'application/views/inc/header.php';
?>

<div class="row">

	<div class="col-md-6 col-xxl-3">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<img src="<?= base_url('assets/images/icons/ing-logo.svg') ?>" alt="ING logo">
				</div>
				<h4 class="card-title">Import bankafschrift ING</h4>
			</div>
			<div class="card-body">
				<form method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-12 dragdrop">
							<input type="file" name="bankfile"  accept=".XML, .xml" required>
						</div>
					</div>
					<button type="submit" class="btn btn-block btn-success">Verwerk bestand</button>
				</form>
			</div>
		</div>
	</div>

	<div class="col-md-6 col-xxl-3">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="fas fa-warehouse"></i>
				</div>
				<h4 class="card-title">Import Voorraadlijst</h4>
			</div>
			<div class="card-body">
				<form method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-12 dragdrop">
							<input type="file" name="stock" accept=".csv, .CSV" required />
						</div>
					</div>
					<button class="btn btn-block btn-success">Verwerk bestand</button>
				</form>
			</div>
		</div>
	</div>

	<div class="col-md-6 col-xxl-3">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<img src="<?= base_url('assets/images/icons/blokker-logo.svg') ?>" alt="ING logo">
				</div>
				<h4 class="card-title">Import verkooporders Blokker</h4>
			</div>
			<div class="card-body">
				<form method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-12 dragdrop">
							<input type="file" name="blokker_salesorder_file" accept=".csv, .CSV"required>
						</div>
					</div>
					<button class="btn btn-block btn-success">Verwerk bestand</button>
				</form>
			</div>
		</div>
	</div>
	
	<div class="col-md-6 col-xxl-3">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<img src="<?= base_url('assets/images/icons/bol-com-logo.svg') ?>" alt="ING logo">
				</div>
				<h4 class="card-title">Import verkooporders Bol.com</h4>
			</div>
			<div class="card-body">
				<form method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-12 dragdrop">
							<input type="file" name="bolcom_salesorder_file" accept=".csv, .CSV" required />
						</div>
					</div>
					<button class="btn btn-block btn-success">Verwerk bestand</button>
				</form>
			</div>
		</div>
	</div>
	
	<div class="col-md-6 col-xxl-3">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<img src="<?= base_url('assets/images/icons/import.svg') ?>" alt="import">
				</div>
				<h4 class="card-title">Import verkooporders (Algemeen)</h4>
			</div>
			<div class="card-body">
				<form method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-12 dragdrop">
							<input type="file" name="general_salesorder_file" accept=".csv, .CSV" required />
						</div>
					</div>
					<button class="btn btn-block btn-success">Verwerk bestand</button>
				</form>
			</div>
		</div>
	</div>

</div>

<?php include 'application/views/inc/footer.php'; ?>

<script type="text/javascript">

	$(document).ready(function () {
	
		<?php if (isset($_SESSION['printCsvSalesorder'])) { ?>
			location.href = '<?= base_url("SalesOrders/salesOrderPDF/".$_SESSION['printCsvSalesorder']) ?>';
		<?php } ?>

	});
	
</script>
