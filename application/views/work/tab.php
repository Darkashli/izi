<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col">
	<ul class="nav nav-pills">
		<li class="nav-item"><a class="nav-link <?=SUBMENUPAGE == 'edit' ? "active" : ''; ?>" href="<?=base_url() ?>work/update/<?= $this->uri->segment(3) ?>">Basisgegevens</a></li>
		<li class="nav-item"><a class="nav-link <?=SUBMENUPAGE == 'progress' ? "active" : ''; ?>" href="<?=base_url() ?>work/progress/<?= $this->uri->segment(3) ?>">Voortgang</a></li>
		<li class="nav-item"><a class="nav-link <?=SUBMENUPAGE == 'product' ? "active" : ''; ?>" href="<?=base_url() ?>work/product/<?= $this->uri->segment(3) ?>">Geleverde producten</a></li>
		<!-- <li class="nav-item"><a class="nav-link <?=SUBMENUPAGE == 'attachments' ? "active" : ''; ?>" href="<?=base_url() ?>work/attachments/<?= $this->uri->segment(3) ?>">Bijlagen</a></li> -->
	</ul>
</div>
