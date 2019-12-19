<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Magazijn');
define('PAGE', 'settings');

include 'application/views/inc/header.php';
?>

<form method="post" action="<?= base_url(); ?>settings/<?= $this->uri->segment(2); ?>/<?= $this->uri->segment(3); ?>">
	<div class="row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">layers</i>
					</div>
					<h4 class="card-title">Magazijn</h4>
				</div>
				<div class="card-body">

					<div class="row">
						<div class="col-md-6 col-xs-6 form-group label-floating">
							<label class="control-label">Naam <small>*</small></label>
							<input class="form-control" name="name" value="<?= $warehouse->Name; ?>" required />
						</div>

						<div class="col-md-6 col-xs-6 form-group label-floating">
							<label class="control-label">Omschrijving</label>
							<input class="form-control" name="description" value="<?= $warehouse->Description; ?>"/>
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
