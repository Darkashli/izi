<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Thanks');

include 'application/views/inc/main.php';
?>

<div class="wrapper wrapper-full-page thanks-background">
	<div class="page-header" style="background-size: cover; background-position: top center;">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 my-5">
					<a href="http://www.iziaccount.nl/" target="_blank"><img src="/assets/images/logo.png" class="img-fluid  mx-auto d-block" /></a>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 my-5">
					<div class="card">
						<div class="card-header card-header-icon card-header-primary">
							<div class="card-icon">
								<i class="material-icons">thumb_up</i>
							</div>
						</div>
						<div class="card-body text-center">
							<h4 class="card-title">Bedankt voor het bevestigen van uw offerte</h4>
                            <p>Er is een e-mail naar u mailadres wordt gestuurd, controlleer maar binnen uw mailbox.</p>
						</div>
					</div>
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

<?php
include 'application/views/inc/footer.php';
?>
