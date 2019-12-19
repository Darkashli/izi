<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col">
	<ul class="nav nav-pills">
		<li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'settings' ? "active" : ''; ?>" href="<?= base_url() ?>settings/index">Algemene instellingen</a></li>
		<li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'users' ? "active" : ''; ?>" href="<?= base_url() ?>settings/users">Gebruikers</a></li>
		<li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'productgroups' ? "active" : ''; ?>" href="<?= base_url() ?>settings/productgroups">Productgroepen</a></li>
		<li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'natureofwork' ? "active" : ''; ?>" href="<?= base_url() ?>settings/natureofwork">Aard van werkzaamheden</a></li>
		<li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'warehouses' ? "active" : ''; ?>" href="<?= base_url() ?>settings/warehouse">Magazijnen</a></li>
		<li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'paymentconditions' ? "active" : ''; ?>" href="<?= base_url() ?>settings/paymentconditions">Betaalcondities</a></li>
		<li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'quotations' ? "active" : ''; ?>" href="<?= base_url() ?>settings/quotations">Offertes</a></li>
		<li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'statusses' ? "active" : ''; ?>" href="<?= base_url() ?>settings/status">Statussen</a></li>
		<li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'business' ? "active" : ''; ?>" href="<?= base_url() ?>settings/business">Bedrijven</a></li>
		<li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'webshop' ? "active" : ''; ?>" href="<?= base_url() ?>settings/webshop">Webshop</a></li>
		<li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'years' ? "active" : ''; ?>" href="<?= base_url() ?>settings/year">Boekjaren</a></li>
	</ul>
</div>
