<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Contactpersonen');
if ($this->uri->segment(2) != 'createcontact') {
	define('CUSTOMER', $this->uri->segment(3));
	define('SUBTITEL', getCustomerName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
}
define('PAGE', 'customer');

include 'application/views/inc/header.php';
?>
<form method="post" action="<?= base_url(); ?>supplier/<?= $this->uri->segment(2); ?>/<?= $this->uri->segment(3); ?>">
	<div class="row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">person</i>
					</div>
					<h4 class="card-title">Contactpersoon</h4>
				</div>
				<div class="card-body">

					<div class="row">
						<div class="col-md-8 col-sm-6 form-group label-floating">
							<label class="control-label">Voornaam <small>*</small></label>
							<input name="firstname" class="form-control" required value="<?= $contact->FirstName; ?>" />
						</div>

						<div class="col-md-4 col-sm-6 form-group label-floating">
							<label class="control-label">Tussenvoegsel</label>
							<input name="insertion" class="form-control" value="<?= $contact->Insertion; ?>" />
						</div>

						<div class="col-md-12 col-sm-12 form-group label-floating">
							<label class="control-label">Achternaam <small>*</small></label>
							<input name="lastname" class="form-control" required value="<?= $contact->LastName; ?>" />
						</div>

						<div class="col-md-6 col-sm-6 form-group label-floating">
							<label class="control-label">E-mail adres <small>*</small></label>
							<input name="email" class="form-control" required value="<?= $contact->Email; ?>" />
						</div>

						<div class="col-md-6 col-sm-6 form-group label-floating">
							<label class="control-label">Telefoonnummer</label>
							<input name="phonenumber" class="form-control" value="<?= $contact->PhoneNumber; ?>" />
						</div>

						<div class="col-md-6 col-sm-6 form-group label-floating">
							<label class="control-label">Mobiel</label>
							<input name="phonemobile" class="form-control" value="<?= $contact->PhoneMobile; ?>" />
						</div>

						<div class="col-md-6 col-sm-6 form-group label-floating">
							<label class="control-label">Functie</label>
							<input name="function" class="form-control" value="<?= $contact->Function; ?>" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-md-4">
			<button type="submit" class="btn btn-success btn-block">Opslaan en sluiten</button>
		</div>
		<?php if ($this->uri->segment(2) == "updatecontact") { ?>
			<div class="col-md-4">
				<a href="<?= base_url(); ?>supplier/deletecontact/<?= $this->uri->segment(3); ?>" class="btn btn-danger btn-block" id="removeRuleButton">Verwijderen</a>
			</div>
		<?php } ?>
	</div>
</form>

<script type="text/javascript">

	$(document).ready(function () {
		$('#removeRuleButton').on('click', function (e) {
			e.preventDefault(); // Prevent the href from redirecting directly
			var linkURL = $(this).attr("href");
			swal({
				title: 'Weet u het zeker?',
				text: 'Deze actie kan niet ongedaan worden gemaakt!',
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ja, verwijder dit!'
			}).then((result) => {
				if (result.value) {
					window.location.href = linkURL;
				}
			});
		});
	});


</script>
