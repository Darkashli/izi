<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Boekjaren');
define('PAGE', 'settings');
define('SUBMENUPAGE', 'years');
define('SUBMENU', $this->load->view('settings/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
</div>


<div class="card">
	<div class="card-header card-header-icon card-header-primary">
		<div class="card-icon">
			<i class="material-icons">calendar_today</i>
		</div>
		<h4 class="card-title">Boekjaren</h4>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover" id="yearTable" width="100%">
				<thead>
					<tr>
						<td>Boekjaar</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($years as $year): ?>
						<tr>
							<td><?= $year->Year ?></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<form method="post">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">add</i>
					</div>
					<h4 class="card-title">Nieuw boekjaar toevoegen</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 form-group label-floating">
							<label class="control-label">Jaar <small>*</small></label>
							<input type="text" name="year" class="form-control year-picker" required>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="w-100"></div>
		<div class="col-md-4">
			<button type="submit" class="btn btn-block btn-success">Opslaan</button>
		</div>
	</div>
</form>

<script>

	$(document).ready(function () {
		
		$('#yearTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			}
		});
		
		$('.year-picker').datetimepicker({
			locale: 'nl',
			format: 'YYYY',
			useCurrent: true,
			icons: datetimepickerIcons
		});

	});
		
</script>

<?php include 'application/views/inc/footer.php'; ?>
