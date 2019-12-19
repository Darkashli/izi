<div class="col">
    <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'edit' ? "active" : ''; ?>" href="<?= base_url(); ?>supplier/edit/<?= $this->uri->segment(3); ?>">Algemeen</a></li>
        <li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'contacts' ? "active" : ''; ?>" href="<?= base_url(); ?>supplier/contacts/<?= $this->uri->segment(3); ?>">Contactpersonen</a></li>
        <li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'purchaseorders' ? "active" : ''; ?>" href="<?= base_url(); ?>supplier/purchaseorders/<?= $this->uri->segment(3); ?>">Orders</a></li>
        <li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'invoices' ? "active" : ''; ?>" href="<?= base_url(); ?>supplier/invoices/<?= $this->uri->segment(3); ?>">Facturen</a></li>
    </ul>
</div>
