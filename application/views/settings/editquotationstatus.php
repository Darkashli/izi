<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Offerte status');
define('PAGE', 'settings');

include 'application/views/inc/header.php';
?>

<form method="post">
	<div class="card">
		<div class="card-header card-header-icon card-header-primary">
			<div class="card-icon">
				<i class="material-icons">layers</i>
			</div>
			<h4 class="card-title">Status</h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-6 form-group label-floating">
					<label class="control-label">Omschrijving <small>*</small></label>
					<input class="form-control" name="description" value="<?= $status->Description; ?>" required />
				</div>
				<div class="col-md-6 form-group label-floating">
					<label class="control-label">Na <small>*</small></label>
					<select class="form-control" name="after" required>
						<option value="begin" <?= $status->SortingOrder == 1 ? 'selected' : null ?>>- Aan het begin -</option>
						<?php foreach ($statusses as $status2):
							if (isset($status->Id) && $status2->Id == $status->Id) { continue; }
						?>
							<option value="<?= $status2->Id ?>" <?= $status2->SortingOrder == $status->SortingOrder - 1 ? 'selected' : null ?> ><?= $status2->Description ?></option>
						<?php endforeach; ?>
					</select>
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
