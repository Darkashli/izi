<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Productgroepen');
define('PAGE', 'settings');

include 'application/views/inc/header.php';
?>

<form method="post">
	<div class="row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">layers</i>
					</div>
					<h4 class="card-title">Aard van werkzaamheden</h4>
				</div>
				<div class="card-body">

					<div class="row">

						<div class="col-md-6 col-xs-6 form-group label-floating">
							<label class="control-label">Omschrijving <small>*</small></label>
							<input class="form-control" name="description" value="<?= $natureofwork->Description; ?>" required />
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

</form>
