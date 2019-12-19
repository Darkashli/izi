<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Gebruiker toevoegen');
define('PAGE', 'settings');

include 'application/views/inc/header.php';
?>

<form method="post" action="<?= base_url(); ?>settings/<?= $this->uri->segment(2); ?>/<?= $this->uri->segment(3); ?>">
	<div class="row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">assignment_ind</i>
					</div>
					<h4 class="card-title">Gebruiker</h4>
				</div>
				<div class="card-body">
					<?php
					if (!empty($user->Username)) {
						?>
							<div class="float-right togglebutton">
								<label>
									<input type="checkbox" name="active" <?= $user->Activated == 0 ? null : 'checked' ?>>
									Gebruiker is actief
								</label>
							</div>
						<?php
					}
					?>
					<div class="clearfix"></div>
					<div class="row">
						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Gebruikersnaam <small>*</small></label>
							<input class="form-control" name="username" <?= empty($user->Username) ? '' : 'readonly'; ?> value="<?= $user->Username; ?>" required />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">E-mail adres <small>*</small></label>
							<input name="email" class="form-control" required value="<?= $user->Email; ?>" required />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Wachtwoord</label>
							<input name="password" type="password" class="form-control" <?= empty($user->Username) ? 'required' : ''; ?> autocomplete="new-password" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Wachtwoord (Herhaal)</label>
							<input name="password2" type="password" class="form-control" <?= empty($user->Username) ? 'required' : ''; ?> autocomplete="new-password" />
						</div>
					</div>
				</div>
			</div>

			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">assignment_ind</i>
					</div>
					<h4 class="card-title">Persoonsgegevens</h4>
				</div>
				<div class="card-body">

					<div class="row">
						<div class="col-md-7 col-xs-12 form-group label-floating">
							<label class="control-label">Voornaam <small>*</small></label>
							<input name="firstname" class="form-control" required value="<?= $user->FirstName; ?>" required />
						</div>

						<div class="col-md-5 col-xs-12 form-group label-floating">
							<label class="control-label">Tussenvoegsel</label>
							<input name="insertion" class="form-control" value="<?= $user->Insertion; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Achternaam <small>*</small></label>
							<input name="lastname" class="form-control" required value="<?= $user->LastName; ?>" required />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Telefoonnummer</label>
							<input name="phonenumber" class="form-control" value="<?= $user->Mobile; ?>" />
						</div>
					</div>
				</div>
			</div>

			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">build</i>
					</div>
					<h4 class="card-title">Rechten</h4>
				</div>
				<div class="card-body">

					<div class="row">
						<ul class="list-group userrights w-100">
							<li class="list-group-item form-check active">
								<label class="form-check-label">
									<input title="toggle all" type="checkbox" class="form-check-input all">
									Klant beheer
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</li>
							<li class="list-group-item form-check">
								<input type="hidden" name="CustomerManagement" value="0" />
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="CustomerManagement" value="1" <?= ($user->CustomerManagement) ? 'checked' : ''; ?>>
									Beheer klanten
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</li>
							<li class="list-group-item form-check">
								<input type="hidden" name="CContacts" value="0" />
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="CContacts" value="1" <?= ($user->Tab_CContacts) ? 'checked' : ''; ?>>
									Beheer contactpersonen
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</li>
							<li class="list-group-item form-check">
								<input type="hidden" name="CSalesorders" value="0" />
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="CSalesorders" value="1" <?= ($user->Tab_CSalesOrders) ? 'checked' : ''; ?>>
									Beheer verkooporders
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</li>
							<li class="list-group-item form-check">
								<input type="hidden" name="CPurchaseorders" value="0" />
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="CPurchaseorders" value="1" <?= ($user->Tab_CPurchaseOrders) ? 'checked' : ''; ?>>
									Beheer inkooporders
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</li>
							<li class="list-group-item form-check">
								<input type="hidden" name="CInvoice" value="0" />
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="CInvoice" value="1" <?= ($user->Tab_CInvoice) ? 'checked' : ''; ?>>
									Beheer facturen
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</li>
							<li class="list-group-item form-check">
								<input type="hidden" name="CQuotations" value="0" />
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="CQuotations" value="1" <?= ($user->Tab_CQuotations) ? 'checked' : ''; ?>>
									Beheer offertes
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</li>
							<li class="list-group-item form-check">
								<input type="hidden" name="CWork" value="0" />
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="CWork" value="1" <?= ($user->Tab_CWork) ? 'checked' : ''; ?>>
									Beheer tickets
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</li>
							<li class="list-group-item form-check">
								<input type="hidden" name="CPriceAgr" value="0" />
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="CPriceAgr" value="1" <?= ($user->Tab_CPriceAgr) ? 'checked' : ''; ?>>
									Beheer prijsafspraken
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</li>
							<li class="list-group-item form-check">
								<input type="hidden" name="CRepeatingInv" value="0" />
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="CRepeatingInv" value="1" <?= ($user->Tab_CRepeatingInv) ? 'checked' : ''; ?>>
									Beheer periodieke facturen
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</li>
						</ul>
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

<script type="text/javascript">

	/* toggle all checkboxes in group */
	$('.all').click(function (e) {
		e.stopPropagation();
		var $this = $(this);
		if ($this.is(":checked")) {
			$this.parents('.list-group').find("[type=checkbox]").prop("checked", true);
		} else {
			$this.parents('.list-group').find("[type=checkbox]").prop("checked", false);
			$this.prop("checked", false);
		}
	});

	$('[type=checkbox]').click(function (e) {
		e.stopPropagation();
	});

	/* toggle checkbox when list group item is clicked */
	$('.list-group a').click(function (e) {

		e.stopPropagation();

		var $this = $(this).find("[type=checkbox]");
		if ($this.is(":checked")) {
			$this.prop("checked", false);
		} else {
			$this.prop("checked", true);
		}

		if ($this.hasClass("all")) {
			$this.trigger('click');
		}
	});
</script>
