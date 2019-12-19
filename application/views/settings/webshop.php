<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Webshop (Woocommerce)');
define('PAGE', 'settings');

define('SUBMENUPAGE', 'webshop');
define('SUBMENU', $this->load->view('settings/tab', array(), true));
include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
	<?php if (hasWebshop()): ?>
		<div class="col-auto ml-auto">
			<a class="btn btn-info" href="<?= base_url('webshop/syncProducts') ?>"><i class="fas fa-sync-alt"></i> Webshop producten synchroniseren</a>
		</div>
	<?php endif; ?>
</div>

<form method="post">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">add_shopping_cart</i>
					</div>
					<h4 class="card-title"><?= TITEL ?></h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-xl-9">
							<div class="row">
								<div class="col-md-6 form-group label-floating">
									<label class="control-label">Omschrijving</label>
									<input class="form-control" name="description" value="<?= $webshop->Description ?>"/>
								</div>
								<div class="col-md-6 form-group label-floating">
									<label class="control-label">Webshop URL <small>*</small></label>
									<input class="form-control" name="url" value="<?= $webshop->Url ?>" required />
								</div>
								<div class="col-md-6 form-group label-floating">
									<label class="control-label">API Key <small>*</small></label>
									<input class="form-control" name="api_key" value="<?= $webshop->ApiKey ?>" required />
								</div>
								<div class="col-md-6 form-group label-floating">
									<label class="control-label">Secret <small>*</small></label>
									<input class="form-control" type="password" name="secret" value="<?= $webshop->Secret; ?>" required />
								</div>
							</div>
						</div>
						<div class="col-xl-3">
							<div class="row">
								<div class="col-12 togglebutton">
									<label>
										<input type="checkbox" name="active" <?= $webshop->Active == 1 ? 'checked' : null ?>>
										Actief
									</label>
								</div>
								<div class="col-12 mt-3">
									<div class="row">
										<div class="col-12 togglebutton">
											<label>
												<input type="checkbox" id="incoming_orders" name="incoming_orders" <?= $webshop->OrderFormat != null ? 'checked' : null ?> onchange="toggleIncomingOrders()">
												Bestellingen laten binnenkomen...
											</label>
										</div>
										<div class="col-12 form-check form-check-radio">
											<label class="form-check-label">
												<input type="radio" name="order_format" value="order" class="form-check-input" <?= $webshop->OrderFormat == 'order' ? 'checked' : null ?> required>
												...als een order
												<span class="circle">
													<span class="check"></span>
												</span>
											</label>
										</div>
										<div class="col-12 form-check form-check-radio">
											<label class="form-check-label">
												<input type="radio" name="order_format" value="invoice" class="form-check-input" <?= $webshop->OrderFormat == 'invoice' ? 'checked' : null ?> required>
												...als een factuur
												<span class="circle">
													<span class="check"></span>
												</span>
											</label>
										</div>
										<div class="col-12 form-check form-check-radio">
											<label class="form-check-label">
												<input type="radio" name="order_format" value="quotation" class="form-check-input" <?= $webshop->OrderFormat == 'quotation' ? 'checked' : null ?> required>
												...als een offerte
												<span class="circle">
													<span class="check"></span>
												</span>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-4">
					<button type="submit" class="btn btn-block btn-success">Opslaan en sluiten</button>
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">
	
	$(document).ready(function(){
		toggleIncomingOrders();
	});
	
	function toggleIncomingOrders() {
		var checked = $("#incoming_orders").prop("checked");
		if (checked) {
			$('input[name="order_format"]').prop("disabled", false);
		}
		else {
			$('input[name="order_format"]').prop("disabled", true);
		}
	}
	
</script>

<?php include 'application/views/inc/footer.php'; ?>
