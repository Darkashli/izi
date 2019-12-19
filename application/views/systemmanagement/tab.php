<div class="col">
    <ul class="nav nav-pills">
	    <li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'network' ? "active" : ''; ?>" href="<?= base_url() ?>SystemManagement/index/<?= $this->uri->segment(3) ?>">Netwerkgegevens</a></li>
	    <li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'internet' ? "active" : ''; ?>" href="<?= base_url() ?>SystemManagement/internetData/<?= $this->uri->segment(3) ?>">Internetgegevens</a></li>
	    <li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'hardware' ? "active" : ''; ?>" href="<?= base_url() ?>SystemManagement/hardware/<?= $this->uri->segment(3) ?>">Hardware</a></li>
	    <li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'software' ? "active" : ''; ?>" href="<?= base_url() ?>SystemManagement/software/<?= $this->uri->segment(3) ?>">Software</a></li>
	    <li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'groups' ? "active" : ''; ?>" href="<?= base_url() ?>SystemManagement/group/<?= $this->uri->segment(3) ?>">Groepen</a></li>
	    <li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'users' ? "active" : ''; ?>" href="<?= base_url() ?>SystemManagement/user/<?= $this->uri->segment(3) ?>">Gebruikers</a></li>
	    <li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'shares' ? "active" : ''; ?>" href="<?= base_url() ?>SystemManagement/share/<?= $this->uri->segment(3) ?>">Shares & Rechten</a></li>
	    <li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'login' ? "active" : ''; ?>" href="<?= base_url() ?>SystemManagement/logonScript/<?= $this->uri->segment(3) ?>">Login scripts</a></li>
	</ul>
</div>
