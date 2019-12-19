<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Offerte');
define('PAGE', 'quotation');

if ($customer != null) {
	define('CUSTOMER', $customer->Id);
	define('SUBTITEL', getCustomerName($customer->Id) . ' (' . $customer->Id . ')');
}

include 'application/views/inc/header.php';
?>

<div class="wizard-container">
	<div class="card card-wizard active" data-color="green" id="wizardProfile">
		<div class="card-header card-header-icon card-header-primary wizard-card-header">
			<div class="card-icon">
				<i class="material-icons">layers</i>
			</div>
			<h4 class="card-title">
				Stel offerte samen
			</h4>
		</div>

		<form method="POST" enctype="multipart/form-data">
			<div class="wizard-navigation">
				<ul class="nav nav-pills">
					<?php if ($customer == null): ?>
						<li class="nav-item"><a class="nav-link active" href="#tab0" data-toggle="tab" role="tab">Klantgegevens</a></li>
					<?php endif; ?>
					<li class="nav-item"><a class="nav-link <?= $customer != null ? 'active' : null ?>" href="#tab1" data-toggle="tab" role="tab">Algemeen</a></li>
					<li class="nav-item"><a class="nav-link" href="#tab2" data-toggle="tab" role="tab">Werkzaamheden</a></li>
					<li class="nav-item"><a class="nav-link" href="#tab3" data-toggle="tab" role="tab">Producten</a></li>
					<?php if (checkModule('ModuleRepeatingInvoice')): ?>
						<li class="nav-item"><a class="nav-link" href="#tab4" data-toggle="tab" role="tab">Terugkerende kosten</a></li>
					<?php endif; ?>
					<li class="nav-item"><a class="nav-link" href="#tab5" data-toggle="tab" role="tab">Omschrijving project</a></li>
					<li class="nav-item"><a class="nav-link" href="#tab6" data-toggle="tab" role="tab">Condities</a></li>
					<li class="nav-item"><a class="nav-link" href="#tab7" data-toggle="tab" role="tab">Huidige situatie en advies</a></li>
				</ul>
			</div>

			<div class="card-body">
				<div class="tab-content">
					<?php if ($customer == null): ?>
						<div class="tab-pane active" id="tab0">
							<?php $this->load->view('quotations/tab-anonymous-customer') ?>
						</div>
					<?php endif; ?>
					<div class="tab-pane <?= $customer != null ? 'active' : null ?>" id="tab1">
						<?php $this->load->view('quotations/tab-general') ?>
					</div>
					<div class="tab-pane" id="tab2">
						<?php $this->load->view('quotations/tab-work') ?>
					</div>
					<div class="tab-pane" id="tab3">
						<?php $this->load->view('quotations/tab-product') ?>
					</div>
					<?php if (checkModule('ModuleRepeatingInvoice')): ?>
						<div class="tab-pane" id="tab4">
							<?php $this->load->view('quotations/tab-repeating') ?>
						</div>
					<?php endif; ?>
					<div class="tab-pane" id="tab5">
						<?php $this->load->view('quotations/tab-description') ?>
					</div>
					<div class="tab-pane" id="tab6">
						<?php $this->load->view('quotations/tab-conditions') ?>
					</div>
					<div class="tab-pane" id="tab7">
						<?php $this->load->view('quotations/tab-status') ?>
					</div>
				</div>
			</div>

			<div class="card-footer">
				<div class="mr-auto">
					<button type="button" class="btn btn-previous btn-fill btn-default btn-wd disabled" name="previous" value="Previous">Terug</button>
				</div>
				<div class="ml-auto">
					<button type="button" class="btn btn-next btn-fill btn-primary btn-wd" name="next" value="Next">Volgende</button>
					<button type="submit" class="btn btn-finish btn-fill btn-success btn-wd" style="display: none;">Opslaan</button>
				</div>
				<div class="clearfix"></div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
	var customerId = <?= $customer->Id ?? 0 ?>;

	$(document).ready(function()
	{
		wizard.initMaterialWizard();
		$('.date-picker').datetimepicker({
			locale: 'nl-NL',
			format: 'L',
			icons: datetimepickerIcons
		});
	});
</script>
<?php include 'application/views/inc/footer.php'; ?>
