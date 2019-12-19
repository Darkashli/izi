<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Test');
define('PAGE', 'test');

include 'application/views/inc/header.php';

?>

<form method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">subject</i>
					</div>
					<h4 class="card-title">Drag drop test</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6 dragdrop">
							<input type="file" name="myFile">
						</div>
						<div class="col-md-6 dragdrop">
							<input type="file" name="myFile2">
						</div>
						<div class="col-12">
							<input type="file" name="myFile3">
						</div>
						<div class="col-12">
							<input type="reset">
						</div>
						<div class="col-12">
							<input type="submit">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<?php include 'application/views/inc/footer.php'; ?>
