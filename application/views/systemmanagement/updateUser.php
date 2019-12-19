<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Systeembeheer');
define('PAGE', 'systemmanagement');

include 'application/views/inc/header.php';
?>
<form method="post" action="<?= base_url(); ?>SystemManagement/<?= $this->uri->segment(2); ?>/<?= $this->uri->segment(3); ?>">
	<div class="row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">group</i>
					</div>
					<h4 class="card-title">Gebruiker</h4>
				</div>
				<div class="card-body">
					<div class="row">

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Voornaam <small>*</small></label>
							<input type="text" name="firstname" class="form-control" required value="<?= $user->FirstName; ?>" value="<?= $user->FirstName; ?>"/>
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Tussenvoegsel</label>
							<input type="text" name="insertion" class="form-control" value="<?= $user->Insertion; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Achternaam <small>*</small></label>
							<input type="text" name="lastname" class="form-control" required value="<?= $user->LastName; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">E-mail adres <small>*</small></label>
							<input type="text" name="email" class="form-control" required value="<?= $user->Email; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Gebruikersnaam</label>
							<input type="text" name="username" class="form-control" value="<?= $user->Username; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Wachtwoord</label>
							<input type="text" name="password" class="form-control" value="<?= $user->Password; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Computernaam</label>
							<input type="text" name="computername" class="form-control" value="<?= $user->ComputerName; ?>" />
						</div>

						<!-- <div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label w100">Lid van groep(en)</label>
							<?= $groups; ?>
						</div> -->

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Office365 / Exchange online gebruikernaam</label>
							<input type="text" name="exchangeusername" class="form-control" value="<?= $user->ExchangeUsername; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Office365 / Exchange online wachtwoord</label>
							<input type="text" name="exchangepassword" class="form-control" value="<?= $user->ExchangePassword; ?>" />
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group">
							<label>Lid van groep(en)</label>
							<select multiple name="groups[]" class="form-control" title="Maak uw keuze">
								<?php foreach ($groups as $group) { ?>
									<?php
										if (in_array($group->Id, $activeGroups)) {
											$active = 'selected';
										}
										else{
											$active = '';
										}
									?>
									<option value="<?= $group->Id ?>" <?= $active ?>><?= $group->Name ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="col-lg-6 col-xl-6 col-md-6 col-xs-12 form-group">
							<label>Recht tot inbox(en)</label>
							<select multiple name="users[]" class="form-control" title="Maak uw keuze">
								<?php foreach ($users as $userValue) { ?>
									<?php
										if (in_array($userValue->Id, $activeUsers)) {
											$active = 'selected';
										}
										else{
											$active = '';
										}
										if ($userValue->FirstName != '' && !empty($userValue->FirstName) && $userValue->LastName != '' && !empty($userValue->LastName) && $userValue->Insertion != '' && !empty($userValue->Insertion))
										{
											$name = $userValue->FirstName . ' ' . $userValue->Insertion . ' ' . $userValue->LastName;
										}

										elseif ($userValue->FirstName != '' && !empty($userValue->FirstName) && $userValue->LastName != '' && !empty($userValue->LastName) && $userValue->Insertion == '' && empty($userValue->Insertion))
										{
											$name = $userValue->FirstName . ' ' . $userValue->LastName;
										}

										elseif ($userValue->FirstName == '' && empty($userValue->FirstName) && $userValue->LastName != '' && !empty($userValue->LastName) && $userValue->Insertion == '' && empty($userValue->Insertion))
										{
											$name = $userValue->LastName;
										}

										elseif ($userValue->FirstName != '' && !empty($userValue->FirstName) && $userValue->LastName == '' && empty($userValue->LastName) && $userValue->Insertion == '' && empty($userValue->Insertion))
										{
											$name = $userValue->FirstName;
										}
									?>
									<option value="<?= $userValue->Id ?>" <?= $active ?>><?= $name ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="col-12">
							<label>Opmerkingen</label>
							<textarea id="comments" name="comments" class="editortools" rows="10"><?= $user->Comments; ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row justify-content-center">
		<?php if ($this->uri->segment(2) == "updateUser") { ?>
			<div class="col-md-4">
				<a href="<?= base_url(); ?>SystemManagement/deleteUser/<?= $this->uri->segment(3); ?>" class="btn btn-danger btn-block" id="removeRuleButton">Verwijderen</a>
			</div>
		<?php } ?>
		<div class="col-md-4">
			<button type="submit" class="btn btn-success btn-block">Opslaan en sluiten</button>
		</div>
	</div>

</form>

<?php include 'application/views/inc/footer.php'; ?>

<script>
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
