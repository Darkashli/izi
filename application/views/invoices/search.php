<div class="modal-body">
	<div class="col-md-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-hover" id="invoiceTable" width="100%">
				<thead>
					<tr>
						<td>Factuur id</td>
						<td>Factuurnummer</td>
						<td>Datum</td>
						<td>Totaal (inc. btw)</td>
						<td>Betaald</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($invoices as $invoice) { ?>
						<tr onclick="selectInvoiceBank(this, <?= $this->uri->segment(5) ?>, <?= ($this->uri->segment(6) == 'addNew') ? "'".$this->uri->segment(6)."'" : $this->uri->segment(6) ?>)">
							<td><?= $invoice->Id; ?></td>
							<td><?= $invoice->InvoiceNumber; ?></td>
							<td data-order="<?= date('YmdHis', $invoice->PaymentDate) ?>"><?= date('d-m-Y', $invoice->InvoiceDate); ?></td>
							<td>&euro; <?= number_format($invoice->TotalIn, '2', ',', '.'); ?></td>
							<td data-order="<?= $invoice->PaymentDate ? '1 '.date('YmdHis', $invoice->PaymentDate) : '0' ?>"><?= $invoice->PaymentDate ? '(<b>reeds betaald</b> op '.date('d-m-Y', $invoice->PaymentDate).')' : '(nog niet betaald)' ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">

	function selectInvoiceBank(row, ruleNumber, ruleInvoicenumber) {
		var cells = row.cells;
		console.log(cells);
		console.log('InvoiceId: ' + cells[0].innerHTML);
		console.log('InvoiceNumber: ' + cells[1].innerHTML);
		parent.setPredictedInvoice(ruleNumber, ruleInvoicenumber, cells[1].innerHTML, cells[0].innerHTML, cells[2].innerHTML, cells[3].innerHTML);
	}

	$(document).ready(function () {

		$('#invoiceTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"order": [[1, "asc"]]
		});

	});
</script>
