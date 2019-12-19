<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Overzicht geconverteerde offertes');
define('PAGE', 'quotation');

include 'application/views/inc/header.php';
?>

<div class="card">
	<div class="card-header card-header-icon card-header-primary">
		<div class="card-icon">
			<i class="material-icons">format_list_numbered</i>
		</div>
		<h4 class="card-title"><?= TITEL ?></h4>
	</div>
	<div class="card-body">
		<?php if (
			empty($invoice) &&
			empty($invoiceRepeating) &&
			empty($salesOrder)
		): ?>
			<p>Er zijn geen offertes omgezet.</p>
		<?php else: ?>
			<?php if (!empty($invoice)): ?>
				<p>De volgende factuur is aangemaakt: <a target="_blank" href="<?= base_url("invoices/editInvoice/$invoice->Id") ?>"><?= base_url("invoices/editInvoice/$invoice->Id") ?></a></p>
			<?php endif; ?>
			<?php if (!empty($invoiceRepeating)): ?>
				<p>De volgende periodieke factuur is aangemaakt: <a target="_blank" href="<?= base_url("customers/updateRepeatingInvoice/$invoiceRepeating->Id") ?>"><?= base_url("customers/updateRepeatingInvoice/$invoiceRepeating->Id") ?></a></p>
			<?php endif; ?>
			<?php if (!empty($salesOrder)): ?>
				<p>De volgende verkoopfactuur is aangemaakt: <a target="_blank" href="<?= base_url("SalesOrders/editorder/$salesOrder->Id") ?>"><?= base_url("SalesOrders/editorder/$salesOrder->Id") ?></a></p>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>

<?php include 'application/views/inc/footer.php'; ?>
