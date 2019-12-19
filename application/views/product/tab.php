<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col">
	<ul class="nav nav-pills">
		<li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'default' ? "active" : null ?>" href="<?= base_url('product/edit/'.$this->uri->segment(3)) ?>">Algemeen</a></li>
		<li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'history' ? "active" : null ?>" href="<?= base_url('product/history/'.$this->uri->segment(3)) ?>">Historie</a></li>
		<li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'images' ? "active" : null ?>" href="<?= base_url('product/images/'.$this->uri->segment(3)) ?>">Afbeeldingen</a></li>
	</ul>
</div>
