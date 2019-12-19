<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Login');

include 'application/views/inc/main.php';
?>

<div class="wrapper wrapper-full-page">
	<div class="page-header" style="background-size: cover; background-position: top center;">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 my-5">
					<a href="http://www.iziaccount.nl/" target="_blank"><img src="/assets/images/logo.png" class="img-fluid" /></a>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 my-5">
					<form method="post">
						<div class="card">
							<div class="card-header card-header-icon card-header-primary">
								<div class="card-icon">
									<i class="material-icons">fingerprint</i>
								</div>
								<h4 class="card-title">Inloggen</h4>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-12">
										<div class="form-group label-floating">
											<label class="control-label">Gebruikersnaam</label>
											<input class="form-control" name="username" id="username" required autofocus />
										</div>
									</div>
									<div class="col-12">
										<div class="form-group label-floating">
											<label class="control-label">Wachtwoord</label>
											<input class="form-control" name="password" id="password" type="password" required />
										</div>
									</div>
									<div class="col-12 mt-5">
										<button type="submit" class="btn btn-primary btn-block">Login</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="container-fluid fixed-bottom copyright">
	<div class="container">
		<div class="row">
			<div class="col-12 text-center">
				<p>&copy 2014 - <?= date("Y") ?> | <a href="https://www.commpro.nl" target="_blank">CommPro Automatisering</a></p>
			</div>
		</div>
	</div>
</div>

		
<script type="text/javascript">
	var number = Math.floor(Math.random() * 6) + 1;
	$('.page-header').css('background-image', "url('/assets/images/login" + number + ".jpg')");

	$(window).on('load', function ()
	{
		if ($("#username").is(":-webkit-autofill"))
		{
			$("#username").closest("div.form-group").removeClass("is-empty");
		}
		if ($("#password").is(":-webkit-autofill"))
		{
			$("#password").closest("div.form-group").removeClass("is-empty");
		}
	});
	
</script>

<?php
include 'application/views/inc/footer.php';
?>
