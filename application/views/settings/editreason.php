<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Aanleiding');
define('PAGE', 'settings');
define('SUBMENUPAGE', 'quotations');
define('SUBMENU', $this->load->view('settings/tab', array(), true));

include 'application/views/inc/header.php';
?>

<form method="POST">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">dialpad</i>
					</div>

					<h4 class="card-title">Aanleiding</h4>
				</div>
			
				<div class="card-body">
					<div class="col-12 form-group label-floating">
						<label class="control-label">Omschrijving</label>
						<input class="form-control" name="description" value="<?= $reason->Description; ?>" />
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
</form>

<?php include 'application/views/inc/footer.php'; ?>