<?php include "main.php"; ?>

<div class="wrapper">

    <div class="sidebar" data-color="green" data-background-color="black" data-image="<?= base_url('assets/images/menu-bkg.jpg') ?>">
        <div class="logo">
            <a href="<?= base_url() ?>" class="simple-text logo-mini">
                <img src="<?= base_url('assets/images/logo-mini.png') ?>">
            </a>
            <a href="<?= base_url() ?>" class="simple-text logo-normal">
                <img src="<?= base_url('assets/images/logo-just-text.png') ?>">
            </a>
        </div>
        <div class="sidebar-wrapper">
            <ul class="nav">
                <li class="nav-item <?= PAGE == 'customer' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= base_url(); ?>customers">
                        <i class="material-icons">people</i>
                        <p>Klanten</p>
                    </a>
                </li>
                <li class="nav-item <?= PAGE == 'supplier' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= base_url(); ?>supplier">
                        <i class="material-icons">business</i>
                        <p>Leveranciers</p>
                    </a>
                </li>
                <?php if (checkModule('ModuleTransporters')) { ?>
                    <li class="nav-item <?= PAGE == 'transporters' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= base_url(); ?>transporters">
                            <i class="material-icons">send</i>
                            <p>Vervoerders</p>
                        </a>
                    </li>
                <?php } ?>
                <?php if (checkModule('ModuleSellers')) { ?>
                    <li class="nav-item <?= PAGE == 'sellers' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= base_url(); ?>sellers">
                            <i class="material-icons">work</i>
                            <p>Verkoopkanalen</p>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item <?= PAGE == 'product' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= base_url(); ?>product">
                        <i class="material-icons">layers</i>
                        <p>Producten</p>
                    </a>
                </li>
                <li class="nav-item <?= PAGE == 'salesorder' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= base_url(); ?>customers/index/salesorder">
                        <i class="material-icons">call_made</i>
                        <p>Verkooporders</p>
                    </a>
                </li>
                <li class="nav-item <?= PAGE == 'purchaseorder' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= base_url(); ?>supplier/index/purchaseorder">
                        <i class="material-icons">call_received</i>
                        <p>Inkooporders</p>
                    </a>
                </li>
                <li class="nav-item <?= PAGE == 'invoice' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= base_url(); ?>customers/index/invoice">
                        <i class="material-icons">file_upload</i>
                        <p>Verkoopfactuur</p>
                    </a>
                </li>
                <li class="nav-item <?= PAGE == 'invoiceS' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= base_url(); ?>supplier/index/invoice">
                        <i class="material-icons">file_download</i>
                        <p>Inkoopfactuur</p>
                    </a>
                </li>
                <?php if (checkModule('ModuleQuotation')) { ?>
                    <li class="nav-item <?= PAGE == 'quotation' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= base_url(); ?>customers/index/quotation">
                            <i class="material-icons">description</i>
                            <p>Offertes</p>
                        </a>
                    </li>
                <?php } ?>
                <?php if (checkModule('ModuleTickets')) { ?>
                    <li class="nav-item <?= PAGE == 'work' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= base_url(); ?>customers/index/work">
                            <i class="material-icons">local_offer</i>
                            <p>Tickets</p>
                        </a>
                    </li>
                    <li class="nav-item <?= PAGE == 'projects' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= base_url(); ?>customers/index/projects">
                            <i class="material-icons">graphic_eq</i>
                            <p>Projecten</p>
                        </a>
                    </li>
                <?php } ?>
                <?php if (checkModule('ModuleSystemManagement')) { ?>
                    <li class="nav-item <?= PAGE == 'systemmanagement' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= base_url(); ?>customers/index/systemmanagement">
                            <i class="material-icons">build</i>
                            <p>Systeembeheer</p>
                        </a>
                    </li>
                <?php } ?>
                <?php if (checkModule('ModuleWebsite')) { ?>
                    <li class="nav-item <?= PAGE == 'website' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= base_url(); ?>customers/index/website">
                            <i class="material-icons">devices</i>
                            <p>Websites</p>
                        </a>
                    </li>
                <?php } ?>
                <?php if (checkModule('ModuleRepeatingInvoice')) { ?>
                    <!-- <li class="nav-item <?= PAGE == 'domains' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= base_url(); ?>domains">
                            <i class="material-icons">dvr</i>
                            <p>Domeinnamen</p>
                        </a>
                    </li> -->
                <?php } ?>
                <li class="nav-item <?= PAGE == 'import' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= base_url(); ?>import">
                        <i class="material-icons">import_export</i>
                        <p>Import</p>
                    </a>
                </li>
                <li class="nav-item <?= PAGE == 'overviews' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= base_url(); ?>overviews">
                        <i class="material-icons">playlist_add_check</i>
                        <p>Overzichten</p>
                    </a>
                </li>
                <li class="nav-item <?= PAGE == 'results' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= base_url('results') ?>">
                        <i class="material-icons">trending_up</i>
                        <p>Resultaten</p>
                    </a>
                </li>
            </ul>

        </div>
    </div>


    <div class="main-panel">

        <nav class="navbar navbar-toggleable-md navbar-expand-md navbar-transparent">

            <form class="navbar-form"></form>

            <div class="navbar-minimize">
                <button id="minimizeSidebar" class="btn btn-white btn-round btn-fab sidebar-minimizer">
                    <i class="material-icons visible-on-sidebar-regular">&#xE896;</i>
                    <i class="material-icons visible-on-sidebar-mini">&#xE5D4;</i>
                </button>
            </div>

            <button type="button" class="btn btn-link btn-just-icon navbar-toggler" data-toggle="collapse" aria-expanded="false">
                <i class="material-icons">&#xE5D2;</i>
            </button>

            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="navbar-nav ml-auto">

                    <?php if (defined('SUBTITEL') && defined('CUSTOMER')) { ?>
                        <li class="nav-item dropdown ml-auto">
                            <a class="nav-link dropdown-toggle" href="#" id="customerDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?= SUBTITEL ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="customerDropdown">
                                <a class="dropdown-item" href="<?= base_url('customers/edit/' . CUSTOMER); ?>">Algemeen</a>
                                <?php if ($this->session->userdata('user')->Tab_CContacts == 1) { ?><a class="dropdown-item" href="<?= base_url('customers/contacts/' . CUSTOMER); ?>">Contactpersonen</a><?php } ?>
                                <?php if ($this->session->userdata('user')->Tab_CSalesOrders == 1) { ?><a class="dropdown-item" href="<?= base_url('customers/SalesOrder/' . CUSTOMER); ?>">Orders</a><?php } ?>
                                <?php if ($this->session->userdata('user')->Tab_CInvoice == 1) { ?><a class="dropdown-item" href="<?= base_url('customers/invoices/' . CUSTOMER); ?>">Facturen</a><?php } ?>
                                <?php if ($this->session->userdata('user')->Tab_CQuotations == 1) { ?><a class="dropdown-item" href="<?= base_url('customers/quotations/' . CUSTOMER); ?>">Offertes</a><?php } ?>
                                <?php if (checkModule('ModuleTickets')) { ?><a class="dropdown-item" href="<?= base_url('customers/work/' . CUSTOMER); ?>">Tickets</a><?php } ?>
                                <?php if (checkModule('ModulePriceAgreement')) { ?><a class="dropdown-item" href="<?= base_url('customers/priceagreement/' . CUSTOMER); ?>">Prijsafspraken</a><?php } ?>
                                <?php if (checkModule('ModuleRepeatingInvoice')) { ?><a class="dropdown-item" href="<?= base_url('customers/repeatingInvoice/' . CUSTOMER); ?>">Periodieke facturen</a><?php } ?>
                                <?php if (checkModule('ModuleSystemManagement')) { ?><a class="dropdown-item" href="<?= base_url('SystemManagement/index/' . CUSTOMER); ?>">Systeembeheer</a><?php } ?>
                                <?php if (checkModule('ModuleWebsite')) { ?><a class="dropdown-item" href="<?= base_url('Website/index/' . CUSTOMER); ?>">Websites</a><?php } ?>
                            </div>
                        </li>
                    <?php } elseif (defined('SUBTITEL')) { ?>
                        <span class="navbar-text ml-auto"><?= defined('SUBTITEL') ? SUBTITEL : ''; ?></span>
                    <?php } ?>

                    <li class="nav-item dropdown ml-auto show">
                        <a href="#" class="nav-link dropdown-toggle" id="userDropdown" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="true">

                            <i class="material-icons">notifications</i>
                                <span class="notification"><?php echo count(getUnreadQuotation()) ?></span>
                            <p class="hidden-lg hidden-md">
                                Notifications
                                <b class="caret"></b>
                            </p>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right email_notification_icon" aria-labelledby="userDropdown">
                            <?php if (count(getUnreadQuotation()) > 0): ?>
                                 <?php foreach (getUnreadQuotation() as $quotation) { ?>
                                    <li>
                                        <a href="<?= base_url(); ?>quotation/signedDetail/<?php echo $quotation->Id ?>"><i class="material-icons">info</i>Er is een offerte getekend <?php echo $quotation->QuotationNumber?></a>
                                    </li>
                                <?php } ?>
                                <?php else: ?>
                                    <li>
                                        <a href="#"><i class="material-icons">info</i><?php echo "Er zijn geen berichten in uw mailbox" ?></a>
                                    </li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <li class="nav-item dropdown ml-auto">
                        <a  href="#" class="nav-link dropdown-toggle" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">account_circle</i>
                            <p class="d-md-none">Gebruiker dropdown</p>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <h6 class="dropdown-header">Ingelogd als: <?= $_SESSION['user']->Username ?><br>
                            <?= getBusiness($this->session->userdata('user')->BusinessId)->Name ?></h6>
                            <div class="dropdown-divider d-none d-md-block"></div>
                            <?php if (checkPerm(10)) { ?>
                                <a class="dropdown-item" href="<?= base_url(); ?>settings"><i class="material-icons">settings</i> Instellingen</a>
                            <?php } ?>
                            <a class="dropdown-item" href="<?= base_url(); ?>logout"><i class="material-icons">&#xE879;</i> Uitloggen</a>
                            <div class="dropdown-divider d-md-none"></div>
                        </div>
                    </li>

                </ul>
            </div>
        </nav>

        <div class="content">
            <div class="container">
