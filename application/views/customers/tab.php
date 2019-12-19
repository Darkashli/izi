<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="col">
	<ul class="nav nav-pills">
	    <li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'edit' ? "active" : ''; ?>" href="<?= base_url() ?>customers/edit/<?= $this->uri->segment(3); ?>">Algemeen</a></li>
	    <?php if ($this->session->userdata('user')->Tab_CContacts == 1) { ?><li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'contacts' ? "active" : ''; ?>" href="<?= base_url() ?>customers/contacts/<?= $this->uri->segment(3); ?>">Contactpersonen</a></li><?php } ?>
	    <?php if ($this->session->userdata('user')->Tab_CSalesOrders == 1) { ?><li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'SalesOrders' ? "active" : ''; ?>" href="<?= base_url() ?>customers/SalesOrder/<?= $this->uri->segment(3); ?>">Orders</a></li><?php } ?>
	    <?php if ($this->session->userdata('user')->Tab_CInvoice == 1) { ?><li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'invoices' ? "active" : ''; ?>" href="<?= base_url() ?>customers/invoices/<?= $this->uri->segment(3); ?>">Facturen</a></li><?php } ?>
	    <?php if ($this->session->userdata('user')->Tab_CQuotations == 1 && checkModule('ModuleQuotation')) { ?><li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'quotations' ? 'active' : ''; ?>" href="<?= base_url() ?>customers/quotations/<?= $this->uri->segment(3); ?>">Offertes</a></li><?php }?>
	    <?php if (checkModule('ModuleTickets')) { ?><li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'work' ? "active" : ''; ?>" href="<?= base_url() ?>customers/work/<?= $this->uri->segment(3); ?>">Tickets</a></li><?php } ?>
	    <?php if (checkModule('ModulePriceAgreement')) { ?><li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'priceagreement' ? 'active' : ''; ?>" href="<?= base_url() ?>customers/priceagreement/<?= $this->uri->segment(3); ?>">Prijsafspraken</a></li><?php } ?>
	    <?php if (checkModule('ModuleRepeatingInvoice')) { ?><li class="nav-item"><a class="nav-link <?= SUBMENUPAGE == 'repeatingInvoice' ? 'active' : ''; ?>" href="<?= base_url() ?>customers/repeatingInvoice/<?= $this->uri->segment(3); ?>">Periodieke facturen</a></li><?php } ?>
	</ul>
</div>
