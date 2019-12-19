<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Omzetten naar factuur');
define('PAGE', 'quotation');

include 'application/views/inc/header.php';
?>

<form method="post">
	<div class="row">
		<div class="col-12">
			<ul class="nav nav-pills mb-3" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="invoice-tab" data-toggle="tab" href="#invoice" aria-controls="invoice" aria-selected="true">Factuur</a>
				</li>
				<?php if ($customer != null && checkModule('ModuleRepeatingInvoice')): ?>
					<li class="nav-item">
						<a class="nav-link" id="repeatinginvoice-tab" data-toggle="tab" href="#repeatinginvoice" aria-controls="repeatinginvoice" aria-selected="false">Periodieke factuur</a>
					</li>
				<?php endif; ?>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade show active" id="invoice" role="tabpanel" aria-labeledby="invoice-tab">
					<div class="row">
						<div class="col-12 togglebutton">
							<label>
								Factuur maken
								<input type="hidden" name="create_invoice" value="0">
								<input type="checkbox" name="create_invoice" value="1" class="checkbox" id="toggle_require_invoice" onclick="toggleRequireInvoice()" checked>
							</label>
						</div>
						<?php if ($customer == null) { ?>
							<div class="col-12">
								<div class="card">
									<div class="card-header card-header-icon card-header-primary">
										<div class="card-icon">
											<i class="material-icons">subject</i>
										</div>
										<h4 class="card-title">Klantgegevens</h4>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-12 form-group label-floating">
												<label class="control-label">Klantnaam</label>
												<input type="text" name="company_nameP" class="form-control" value="<?= $quotation->CustomerName ?>" />
											</div>
											<div class="col-md-4 form-group label-floating">
												<label class="control-label">Voornaam</label>
												<input type="text" name="front_nameP" class="form-control" value="<?= $quotation->ContactFirstName ?>" />
											</div>
											<div class="col-md-4 form-group label-floating">
												<label class="control-label">Tussenvoegsel</label>
												<input type="text" name="insertionP" class="form-control" value="<?= $quotation->ContactInsertion ?>" />
											</div>
											<div class="col-md-4 form-group label-floating">
												<label class="control-label">Achternaam</label>
												<input type="text" name="last_nameP" class="form-control" value="<?= $quotation->ContactLastName ?>" />
											</div>
										</div>
										<div class="row">
											<div class="col-md-4 form-group label-floating">
												<label class="control-label">Straat / Postbus</label>
												<input type="text" name="addressP" class="form-control" value="<?= $quotation->CustomerStreet ?>" />
											</div>
											<div class="col-md-2 form-group label-floating">
												<label class="control-label">Huisnummer</label>
												<input type="number" name="address_numberP" class="form-control" value="<?= $quotation->CustomerHouseNumber ?>" />
											</div>
											<div class="col-md-2 form-group label-floating">
												<label class="control-label">Toevoeging</label>
												<input type="text" name="address_additionP" class="form-control" value="<?= $quotation->CustomerHouseNumberAddition ?>" />
											</div>
										</div>
										<div class="row">
											<div class="col-md-3 form-group label-floating">
												<label class="control-label">Postcode</label>
												<input type="text" name="zip_codeP" class="form-control" value="<?= $quotation->CustomerZipCode ?>" />
											</div>
											<div class="col-md-5 form-group label-floating">
												<label class="control-label">Woonplaats</label>
												<input type="text" name="cityP" class="form-control" value="<?= $quotation->CustomerCity ?>" />
											</div>
											<div class="col-md-4 form-group label-floating">
												<label class="control-label">Land</label>
												<input type="text" name="countryP" class="form-control" value="<?= $quotation->CustomerCountry ?>" />
											</div>
										</div>
										<div class="row">
											<div class="col-12 form-group label-floating">
												<label class="control-label">E-mailadres <small>*</small></label>
												<input type="text" name="mail_addressP" class="form-control" value="<?= $quotation->CustomerMailaddress ?>" required />
											</div>
										</div>
										<div class="row">
											<div class="col-md-6 col-sm-12 form-group">
												<label class="control-label">Betalingsconditie <small>*</small></label>
												<select class="form-control"name="paymentconditionP">
													<?php foreach ($paymentConditions as $paymentCondition): ?>
														<option value="<?= $paymentCondition->Name ?>" <?= $quotation->PaymentConditionId == $paymentCondition->Id ? 'selected' : null ?>><?= $paymentCondition->Name ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-md-6 col-sm-12 form-group label-floating toggle-condition">
												<label class="control-label">Betaaltermijn (dagen) <small>*</small></label>
												<input name="termofpaymentP" class="form-control" type="number" value="<?= $quotation->PaymentTerm ?>" required/>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
						<div class="col-12">
							<div class="card">
								<div class="card-header card-header-icon card-header-primary">
									<div class="card-icon">
										<i class="material-icons">content_paste</i>
									</div>
									<h4 class="card-title">Factuurgegevens</h4>
								</div>
								<div class="card-body">
									<div class="row mt-2">
										<div class="col-lg col-md-6 input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="material-icons">date_range</i>
												</span>
											</div>
											<div class="form-group label-floating">
												<label class="control-label">Factuurdatum <small>*</small></label>
												<input class="form-control date-picker" type="text" name="invoicedateP" value="<?= date('d-m-Y'); ?>" required />
											</div>
										</div>
				
										<?php if ($customer != null): ?>
											<div class="col-lg col-md-6 col-sm-12 form-group label-floating">
												<label class="control-label">T.A.V.</label>
												<?= $contactP; ?>
											</div>
										<?php endif; ?>
				
										<div class="col-lg col-md-6 col-sm-12 form-group label-floating">
											<label class="control-label">Termijn (dagen) <small>*</small></label>
											<input value="<?= $customer != null ? $customer->TermOfPayment : $quotation->PaymentTerm; ?>" name="termofpaymentP" class="form-control" type="number" required />
										</div>
				
										<div class="col-lg col-md-6 col-sm-12">
											<label>&nbsp;</label>
											<div class="togglebutton">
												<label>
													<input type="hidden" name="calculatePurchaseP" value="0" />
													<input type="checkbox" name="calculatePurchaseP" value="1" class="checkbox" id="calculatePurchaseP" onclick="changePurchaseP()" />
													Vanuit inkoopprijs
												</label>
											</div>
										</div>
				
										<div class="col-lg col-md-6 col-xs-12 form-group label-floating">
											<label class="control-label">Betreft</label>
											<input name="short_descriptionP" type="text" class="form-control" value="<?= $quotation->Subject ?>" />
										</div>
				
									</div>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="card">
								<div class="card-header card-header-icon card-header-primary">
									<div class="card-icon">
										<i class="material-icons">subject</i>
									</div>
									<h4 class="card-title">Factuurregels</h4>
								</div>
								<div class="card-body">
									<div class="row mt-4">
										<div class="col-md-12 col-xs-12 col-lg-12">
											<table class="table table-borderless" id="tableInvoiceP">
												<thead>
													<tr>
														<td class="w16">Artikelnummer</td>
														<td class="w10">EAN code</td>
														<td class="w33">Artikelomschrijving</td>
														<td class="w7">Aantal</td>
														<td class="w10" id="priceT">Verkoopprijs</td>
														<td class="w7" id="discountT">Korting (%)</td>
														<td>BTW</td>
														<td class="w10">Totaal</td>
														<td>&nbsp;</td>
													</tr>
												</thead>
												<tbody>
													<?php if (!empty($invoiceRules)): ?>
														<?php
														$iP = 1;
														foreach ($invoiceRules as $invoiceRule) {
															?>
															<tr id="invoiceRowP<?= $iP; ?>">
																<td class="align-top">
																	<input name="articlenumberP<?= $iP; ?>" id="articlenumberP" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumberP(this, <?= $iP ?>)" value="<?= $invoiceRule->ArticleC; ?>" required />
																	<ul class="list-group suggestions-listgroup clickable"></ul>
																</td>
																<td class="align-top">
																	<input name="ean_codeP<?= $iP ?>" id="ean_codeP" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCodeP(this, <?= $iP ?>)" value="<?= $invoiceRule->EanCode; ?>" />
																	<ul class="list-group suggestions-listgroup clickable"></ul>
																</td>
																<td class="align-top">
																	<input name="articledescriptionP<?= $iP; ?>" class="form-control" type="text" autocomplete="off" onkeyup="searchDescriptionP(this, <?= $iP ?>)" value="<?= $invoiceRule->Description; ?>" />
																	<ul class="list-group suggestions-listgroup clickable"></ul>
																	<?php if ($invoiceRule->MetaData != null):
																		$metaDatas = unserialize($invoiceRule->MetaData);
																		$countMetaData = ( count($metaDatas) -1 );
																		foreach ($metaDatas as $key => $metaData): ?>
																			<?= $metaData['value'] . ( $key < $countMetaData ? ',' : null ) ?>
																		<?php endforeach; ?>
																		<input type="hidden" name="meta_dataP<?= $iP ?>" value="<?= html_escape($invoiceRule->MetaData) ?>">
																	<?php endif; ?>
																</td>
																<td class="align-top"><input name="amountP<?= $iP; ?>" class="form-control" type="number" value="<?= $invoiceRule->Amount; ?>" onchange="calculateRowP(<?= $iP; ?>)" step="any"  /></td>
																<td class="align-top"><input name="salespriceP<?= $iP; ?>" class="form-control" type="number" value="<?= $invoiceRule->Price; ?>" onchange="calculateRowP(<?= $iP; ?>)" step="any" /></td>
																<td class="align-top"><input name="discountP<?= $iP; ?>" class="form-control" type="number" value="<?= $invoiceRule->Discount; ?>" onchange="calculateRowP(<?= $iP; ?>)" /></td>
																<td class="align-top">
																	<div class="form-group">
																		<select name="taxP<?= $iP; ?>" class="form-control" onchange="calculateRowP(<?= $iP; ?>)">
																			<option <?= $invoiceRule->Tax == 21 ? 'selected' : ''; ?> value="21">21%</option>
																			<option <?= $invoiceRule->Tax == 9 ? 'selected' : ''; ?> value="9">9%</option>
																			<option <?= $invoiceRule->Tax == 0 ? 'selected' : ''; ?> value="0">0%</option>
																		</select>
																	</div>
																</td>
																<td class="align-top"><input name="totalP<?= $iP; ?>" class="form-control" type="number" value="0" readonly /> <input id="numberP" name="numberP[]" type="hidden" readonly value="<?= $iP; ?>" /></td>
																<td class="align-center td-actions text-center">
																	<button type="button" onclick="removeRowP(<?= $iP ?>)" class="btn btn-danger btn-round btn-fab btn-fab-mini">
																		<i class="material-icons">close</i>
																	</button>
																</td>
															</tr>
															<?php
															$iP++;
														}
														?>
													<?php else: ?>
														<tr id="invoiceRowP1">
															<td class="align-top">
																<input name="articlenumberP1" id="articlenumberP" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumberP(this, 1)" required />
																<ul class="list-group suggestions-listgroup clickable"></ul>
															</td>
															<td class="align-top">
																<input name="ean_codeP1" id="ean_codeP" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCodeP(this, 1)" />
																<ul class="list-group suggestions-listgroup clickable"></ul>
															</td>
															<td class="align-top">
																<input name="articledescriptionP1" class="form-control" type="text" autocomplete="off" onkeyup="searchDescriptionP(this, 1)" />
																<ul class="list-group suggestions-listgroup clickable"></ul>
															</td>
															<td class="align-top"><input name="amountP1" class="form-control" type="number" value="0" onchange="calculateRowP(1)" step="any"  /></td>
															<td class="align-top"><input name="salespriceP1" class="form-control" type="number" value="0.00" onchange="calculateRowP(1)" step="any" /></td>
															<td class="align-top"><input name="discountP1" class="form-control" type="number" value="0" onchange="calculateRowP(1)" /></td>
															<td class="align-top">
																<div class="form-group">
																	<select name="taxP1" class="form-control" onchange="calculateRowP(1)">
																		<option value="21">21%</option>
																		<option value="9">9%</option>
																		<option value="0">0%</option>
																	</select>
																</div>
															</td>
															<td class="align-top"><input name="totalP1" class="form-control" type="number" value="0.00" readonly /> <input id="numberP" name="numberP[]" type="hidden" readonly value="1" /></td>
															<td class="align-center td-actions text-center">
																<button type="button" onclick="removeRowP(1)" class="btn btn-danger btn-round btn-fab btn-fab-mini">
																	<i class="material-icons">close</i>
																</button>
															</td>
														</tr>
													<?php $iP = 2; endif; ?>
												</tbody>
												<tfoot>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td class="td-actions text-center">
														<button type="button" rel="tooltip" onclick="addRowP()" class="btn btn-success btn-round btn-fab btn-fab-mini">
															<i class="material-icons">add</i>
														</button>
													</td>
												</tfoot>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="card">
								<div class="card-header card-header-icon card-header-primary">
									<div class="card-icon">
										<i class="material-icons">chat</i>
									</div>
									<h4 class="card-title">Opmerking</h4>
								</div>
								<div class="card-body">
									<div class="row mt-2">
										<div class="col-12">
											<textarea name="descriptionP" class="editortools" rows="10"><?= $quotation->ProductDescription ?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="card">
								<div class="card-header card-header-icon card-header-primary">
									<div class="card-icon">
										<i class="material-icons">euro_symbol</i>
									</div>
									<h4 class="card-title">Totaal</h4>
								</div>
								<div class="card-body">
									<div class="row mt-2">
										<div class="col-lg-3 col-md-6">
											<div class="form-group label-floating">
												<label>Totaal excl. BTW</label>
												<div class="pt-2" id="subtotal">0.00</div>
											</div>
										</div>
				
										<div class="col-lg-3 col-md-6">
											<div class="form-group label-floating">
												<label>Totaal 9% BTW</label>
												<div class="pt-2" id="subtax6">0.00</div>
											</div>
										</div>
				
										<div class="col-lg-3 col-md-6">
											<div class="form-group label-floating">
												<label>Totaal 21% BTW</label>
												<div class="pt-2" id="subtax21">0.00</div>
											</div>
										</div>
				
										<div class="col-lg-3 col-md-6">
											<div class="form-group label-floating">
												<label>Totaal incl. BTW</label>
												<div class="pt-2" id="subtotalIn">0.00</div>
											</div>
										</div>
				
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if ($customer != null && checkModule('ModuleRepeatingInvoice')): ?>
					<div class="tab-pane fade" id="repeatinginvoice" role="tabpanel" aria-labeledby="repeatinginvoice-tab">
						<div class="row">
							<div class="col-12 togglebutton">
								<label>
									Periodieke factuur maken
									<input type="hidden" name="create_invoice_repeating" value="0">
									<input type="checkbox" name="create_invoice_repeating" value="1" class="checkbox" id="toggle_require_repeating" onclick="toggleRequierRepeating()" checked>
								</label>
							</div>
							<div class="col-12">
								<div class="card">
									<div class="card-header card-header-icon card-header-primary">
										<div class="card-icon">
											<i class="material-icons">local_offer</i>
										</div>
										<h4 class="card-title">Facturen</h4>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-lg-3 col-md-6 col-sm-12 input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">
														<i class="fas fa-calendar"></i>
													</span>
												</div>
												<div class="form-group label-floating">
													<label class="control-label">Factuurdatum <small>*</small></label>
													<input class="form-control date-picker" type="text" name="invoicedateR" value="<?= date('d-m-Y'); ?>" required />
												</div>
											</div>
											<div class="col-lg-3 col-md-6 col-sm-12 form-group label-floating">
												<label class="control-label">Periode</label>
												<?= $periodR; ?>
											</div>
											<div class="col-lg-3 col-md-6 col-sm-12 form-group label-floating">
												<label class="control-label">T.A.V.</label>
												<?= $contactR; ?>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-6 col-sm-12 form-group">
												<label>Betalingsconditie</label>
												<select class="form-control"name="paymentconditionR">
													<?php foreach ($paymentConditions as $paymentCondition): ?>
														<option value="<?= $paymentCondition->Name ?>"><?= $paymentCondition->Name ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-lg-3 col-md-6 col-sm-12 form-group label-floating">
												<label class="control-label">Termijn (dagen) <small>*</small></label>
												<input name="termofpaymentR" class="form-control" type="number" value="<?= $customer->TermOfPayment; ?>" required min="1"/>
											</div>
											<div class="col-lg-12 col-md-12 col-sm-12 form-group label-floating">
												<label class="control-label">Factuur beschrijving</label>
												<input class="form-control" name="invoicedescriptionR" value="<?= $quotation->Subject ?>" required />
											</div>
										</div>
										<table class="table table-borderless" id="tableInvoiceR" width="100%">
											<thead>
												<tr>
													<td class="w16">Artikelnummer</td>
													<td class="w30">Artikelomschrijving</td>
													<td>Aantal</td>
													<td id="priceT">Verkoopprijs</td>
													<td id="discountT">Korting (%)</td>
													<td class="w10">BTW</td>
													<td>Totaal</td>
													<td>&nbsp;</td>
												</tr>
											</thead>
											<tbody>
												<?php if (!empty($repeatingInvoiceRules)): ?>
													<?php
													$iR = 1;
													foreach ($repeatingInvoiceRules as $repeatingInvoiceRule) { ?>
														<tr id="invoiceRowR<?= $iR; ?>">
															<td>
																<input name="articlenumberR<?= $iR; ?>" id="articlenumberR" class="form-control" type="text" data-provide="typeahead" autocomplete="off" value="<?= $repeatingInvoiceRule->ArticleNumber; ?>" onkeyup="searchArticlenumberR(this, <?= $iR ?>)" <?= ($iR == 1) ? 'required' : NULL ?> />
																<ul class="list-group suggestions-listgroup clickable"></ul>
															</td>
															<td>
																<div id="isNotDomainR<?= $iR; ?>" class="<?= isset($repeatingInvoiceRule->Domain) && $repeatingInvoiceRule->Domain != 0 ? 'hidden' : null ?>">
																	<input id="articledescriptionR<?= $iR; ?>" name="articledescriptionR<?= $iR; ?>" class="form-control" type="text" autocomplete="off" value="<?= $repeatingInvoiceRule->ArticleDescription; ?>" onkeyup="searchDescriptionR(this, <?= $iR ?>)" <?= isset($repeatingInvoiceRule->Domain) && $repeatingInvoiceRule->Domain != 0 ? 'disabled' : null ?> />
																	<ul class="list-group suggestions-listgroup clickable"></ul>
																</div>
																<div id="isDomainR1" class="<?= !isset($repeatingInvoiceRule->Domain) || $repeatingInvoiceRule->Domain == 0 ? 'hidden' : null ?>">
																	<select class="form-control" name="domainR<?= $iR; ?>" <?= !isset($repeatingInvoiceRule->Domain) || $repeatingInvoiceRule->Domain == 0 ? 'disabled' : null ?>>
																		<option <?= !isset($repeatingInvoiceRule->Domain) || $repeatingInvoiceRule->Domain == 0 ? 'selected' : null ?>>- Selecteer een optie -</option>
																		<?php foreach ($domains as $domain):
																			if (getLatestInvoiceDate($domain->Id) !== false && (isset($repeatingInvoiceRule->Domain) && $domain->Id != $repeatingInvoiceRule->Domain)) {
																				continue;
																			}
																			?>
																			<option value="<?= $domain->Id ?>" <?= isset($repeatingInvoiceRule->Domain) && $domain->Id == $repeatingInvoiceRule->Domain ? 'selected' : null ?>><?= $domain->Name ?></option>
																		<?php endforeach; ?>
																	</select>
																	<input type="hidden" name="articledescriptionR<?= $iR; ?>" id="articledescription_domainR<?= $iR; ?>" value="<?= $repeatingInvoiceRule->ArticleDescription ?>" <?= !isset($repeatingInvoiceRule->Domain) || $repeatingInvoiceRule->Domain == 0 ? 'disabled' : null ?>>
																</div>
															</td>
															<td><input name="amountR<?= $iR; ?>" class="form-control" type="number" onchange="calculateRowR(<?= $iR; ?>)" step="any"  value="<?= $repeatingInvoiceRule->Amount; ?>" /></td>
															<td><input name="salespriceR<?= $iR; ?>" class="form-control" type="number" onchange="calculateRowR(<?= $iR; ?>)" step="any" value="<?= number_format($repeatingInvoiceRule->SalesPrice ? $repeatingInvoiceRule->SalesPrice : 0, 2, '.', ''); ?>" /></td>
															<td><input name="discountR<?= $iR; ?>" class="form-control" type="number" onchange="calculateRowR(<?= $iR; ?>)"  value="0" /></td>
															<td><?= form_dropdown('taxR' . $iR, unserialize(TAXDROPDOWN), $repeatingInvoiceRule->Tax, CLASSDROPDOWN . 'onchange="calculateRowR(' . $iR . ')"'); ?></td>
															<td><input name="totalR<?= $iR; ?>" class="form-control" type="number" value="0" readonly /> <input name="numberR[]" type="hidden" readonly value="<?= $iR; ?>" /></td>
															<td class="td-actions text-center">
																<button type="button" onclick="removeRowR(<?= $iR; ?>)" class="btn btn-danger btn-round btn-fab btn-fab-mini"><i class="material-icons">close</i></button>
															</td>
														</tr>
														<?php
														$iR++;
													} ?>
												<?php else: ?>
													<tr id="invoiceRowR1">
														<td>
															<input name="articlenumberR1" id="articlenumberR" class="form-control" type="text" data-provide="typeahead" autocomplete="off" onkeyup="searchArticlenumberR(this, 1)" required />
															<ul class="list-group suggestions-listgroup clickable"></ul>
														</td>
														<td>
															<div id="isNotDomainR1">
																<input id="articledescriptionR1" name="articledescriptionR1" class="form-control" type="text" autocomplete="off" onkeyup="searchDescriptionR(this, 1)" />
																<ul class="list-group suggestions-listgroup clickable"></ul>
															</div>
															<div id="isDomainR1" class="hidden">
																<select class="form-control" name="domainR1">
																	<option>- Selecteer een optie -</option>
																	<?php foreach ($domains as $domain):
																		if (getLatestInvoiceDate($domain->Id) !== false) {
																			continue;
																		}
																		?>
																		<option value="<?= $domain->Id ?>"><?= $domain->Name ?></option>
																	<?php endforeach; ?>
																</select>
																<input type="hidden" name="articledescriptionR1" id="articledescription_domainR1" disabled>
															</div>
														</td>
														<td><input name="amountR1" class="form-control" type="number" value="0" onchange="calculateRowR(1)" step="any"  /></td>
														<td><input name="salespriceR1" class="form-control" type="number" value="0.00" onchange="calculateRowR(1)" step="any" /></td>
														<td><input name="discountR1" class="form-control" type="number" value="0" onchange="calculateRowR(1)" /></td>
														<td>
															<div class="form-group">
																<select name="taxR1" class="form-control" onchange="calculateRowR(1)">
																	<option value="21">21%</option>
																	<option value="9">9%</option>
																	<option value="0">0%</option>
																</select>
															</div>
														</td>
														<td><input name="totalR1" class="form-control" type="number" value="0.00" readonly /> <input name="numberR[]" type="hidden" readonly value="1" /></td>
														<td class="td-actions text-center">
															<button type="button" onclick="removeRowR(1)" class="btn btn-danger btn-round btn-fab btn-fab-mini"><i class="material-icons">close</i></button>
														</td>
													</tr>
												<?php $iR = 2; endif; ?>
											</tbody>
											<tfoot>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td class="td-actions text-center">
													<button type="button" onclick="addRowR()" class="btn btn-success btn-round btn-fab btn-fab-mini"><i class="material-icons">add</i></button>
												</td>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-md-4">
			<button class="btn btn-block btn-success" type="submit">Opslaan</button>
		</div>
	</div>
</form>

<script type="text/javascript">
	var calculatePurchaseP = 0;
	var currentRowP = <?= $iP ?>;
	<?php if ($customer != null): ?>
		var currentRowR = <?= $iR ?>;
	<?php endif; ?>
	var articles = {};
	var customerId = <?= $this->uri->segment(3) ?>;

	$(document).ready(function () {
		
		for (var i = 1; i < currentRowP; i++) {
			calculateRowP(i);
		}
		
		<?php if ($customer != null): ?>
			for (var i = 1; i < currentRowR; i++) {
				calculateRowR(i);
			}
		<?php endif; ?>
		
		$('.date-picker').datetimepicker({
			locale: 'nl',
			format: 'L',
			useCurrent: false,
			icons: datetimepickerIcons
		});
		
	});
	
	$(document).keydown(function(e){
		if (e.which == 107) { // "+" key.
			if ($("#tableInvoiceP input").is(':focus')) {
				e.preventDefault();
				$('.suggestions-listgroup').html('');
				addRowP();
				$('input[name="articlenumberP'+(currentRowP - 1)+'"]').focus();
			}
			<?php if ($customer != null): ?>
				if ($("#tableInvoiceR input").is(':focus')) {
					e.preventDefault();
					$('.suggestions-listgroup').html('');
					addRowR();
					$('input[name="articlenumberR'+(currentRowR - 1)+'"]').focus();
				}
			<?php endif; ?>
		}
 	});

	function changePurchaseP() {
		if (calculatePurchaseP == 1) {
			document.getElementById('discountT').innerHTML = "Korting (%)";
			document.getElementById('priceT').innerHTML = "Verkoopprijs";
			calculatePurchaseP = 0;
		} else {
			document.getElementById('discountT').innerHTML = "Marge (%)";
			document.getElementById('priceT').innerHTML = "Inkoopprijs";
			calculatePurchaseP = 1;
		}
	}

	function stopRKey(evt) {
		var evt = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type == "text")) {
			return false;
		}
	}

	document.onkeypress = stopRKey;

	function calculateRowP(i) {
		document.getElementById('calculatePurchaseP').setAttribute("disabled", "true");
		total = 0;
		if (calculatePurchaseP == 1) {
			if (document.getElementsByName('amountP' + i)[0].value > 0) {
				total = (document.getElementsByName('amountP' + i)[0].value * (document.getElementsByName('salespriceP' + i)[0].value / (1 - (document.getElementsByName('discountP' + i)[0].value) / 100)));
			}
			if (document.getElementsByName('salespriceP' + i)[0].value > 0) {
				total = (document.getElementsByName('amountP' + i)[0].value * (document.getElementsByName('salespriceP' + i)[0].value / (1 - (document.getElementsByName('discountP' + i)[0].value) / 100)));
			}
		} else {

			if (document.getElementsByName('amountP' + i)[0].value > 0) {
				total = (document.getElementsByName('amountP' + i)[0].value * document.getElementsByName('salespriceP' + i)[0].value) * (1 - (document.getElementsByName('discountP' + i)[0].value / 100));
			}
			if (document.getElementsByName('salespriceP' + i)[0].value > 0) {
				total = parseFloat((document.getElementsByName('amountP' + i)[0].value * document.getElementsByName('salespriceP' + i)[0].value) * (1 - (document.getElementsByName('discountP' + i)[0].value / 100)));
			}
		}
		document.getElementsByName('totalP' + i)[0].value = total.toFixed(2);

		calculateSubTotal();
	}
	
	<?php if ($customer != null): ?>
		function calculateRowR(i) {
			total = 0;
			
			if (document.getElementsByName('amountR' + i)[0].value > 0) {
				total = (document.getElementsByName('amountR' + i)[0].value * document.getElementsByName('salespriceR' + i)[0].value) * (1 - (document.getElementsByName('discountR' + i)[0].value / 100));
			}
			if (document.getElementsByName('salespriceR' + i)[0].value > 0) {
				total = parseFloat((document.getElementsByName('amountR' + i)[0].value * document.getElementsByName('salespriceR' + i)[0].value) * (1 - (document.getElementsByName('discountR' + i)[0].value / 100)));
			}
			
			document.getElementsByName('totalR' + i)[0].value = total.toFixed(2);
		}
	<?php endif; ?>
	
	function removeRowP(i) {
		$('#invoiceRowP' + i).remove();
	}
	
	<?php if ($customer != null): ?>
		function removeRowR(i) {
			$('#invoiceRowR' + i).remove();
		}
	<?php endif; ?>

	function addRowP() {
		var next = currentRowP + 1;
		var myRow = '<tr id="invoiceRowP' + currentRowP + '">';
		myRow += '<td class="align-top"><div class="form-group"><input name="articlenumberP' + currentRowP + '" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumberP(this, ' + currentRowP + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="ean_codeP' + currentRowP + '" class="form-control" type="text" autocomplete="off" onkeyup="searchEanCodeP(this, ' + currentRowP + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="articledescriptionP' + currentRowP + '" class="form-control" type="text" autocomplete="off" onkeyup="searchDescriptionP(this, ' + currentRowP + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="amountP' + currentRowP + '" class="form-control" type="number" value="0" onchange="calculateRowP(' + currentRowP + ')" step="any" /></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="salespriceP' + currentRowP + '" class="form-control" type="number" value="0.00" onchange="calculateRowP(' + currentRowP + ')" step="any" /></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="discountP' + currentRowP + '" class="form-control" type="number" value="0" onchange="calculateRowP(' + currentRowP + ')" /></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><select name="taxP' + currentRowP + '" class="form-control" onchange="calculateRowP(' + currentRowP + ')" ></div>';
		myRow += '<option value="21">21%</option>';
		myRow += '<option value="9">9%</option>';
		myRow += '<option value="0">0%</option>';
		myRow += '</select></div></td>';
		myRow += '<td class="align-top"><div class="form-group"><input name="totalP' + currentRowP + '" class="form-control" type="number" value="0.00" readonly /> <input id="numberP" name="numberP[]" type="hidden" readonly value="' + currentRowP + '" /></td>';
		myRow += '<td class="td-actions text-center"><button type="button" onclick="removeRowP(' + currentRowP + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i><div class="ripple-container"></div></button></td>';
		myRow += '</tr>';
		$("#tableInvoiceP tbody").append(myRow);
		currentRowP++;
	}
	
	<?php if ($customer != null): ?>
		function addRowR() {
			var myRow = '<tr id="invoiceRowR' + currentRowR + '">';
			myRow += '<td class="align-top"><div class="form-group"><input name="articlenumberR' + currentRowR + '" class="form-control" type="text" autocomplete="off" onkeyup="searchArticlenumberR(this, ' + currentRowR + ')" /><ul class="list-group suggestions-listgroup clickable"></ul></div></td>';
			myRow += '<td class="align-top"><div id="isNotDomainR'+currentRowR+'"><div class="form-group"><input name="articledescriptionR' + currentRowR + '" id="articledescriptionR'+currentRowR+'" class="form-control" type="text" autocomplete="off" onkeyup="searchDescriptionR(this, ' + currentRowR + ')" /></div><ul class="list-group suggestions-listgroup clickable"></ul></div><div id="isDomainR'+currentRowR+'" class="hidden"><div class="form-group"><select class="form-control" name="domainR'+currentRowR+'"><option>- Selecteer een optie -</option>';
			<?php foreach ($domains as $domain):
				if (getLatestInvoiceDate($domain->Id) !== false && (isset($invoiceRule->Domain) && $domain->Id != $invoiceRule->Domain)) {
					continue;
				}
				?>
				myRow += '<option value="<?= $domain->Id ?>"><?= $domain->Name ?></option>';
				<?php endforeach; ?>
			myRow += '</select></div><input type="hidden" name="articledescriptionR'+currentRowR+'" id="articledescription_domainR'+currentRowR+'" disabled></div></td>';
			myRow += '<td class="align-top"><div class="form-group"><input name="amountR' + currentRowR + '" class="form-control" type="number" value="0" onchange="calculateRowR(' + currentRowR + ')" step="any" /></td>';
			myRow += '<td class="align-top"><div class="form-group"><input name="salespriceR' + currentRowR + '" class="form-control" type="number" value="0.00" onchange="calculateRowR(' + currentRowR + ')" step="any" /></div></td>';
			myRow += '<td class="align-top"><div class="form-group"><input name="discountR' + currentRowR + '" class="form-control" type="number" value="0.00" onchange="calculateRowR(' + currentRowR + ')" /></div></td>';
			myRow += '<td class="align-top"><div class="form-group"><select name="taxR' + currentRowR + '" class="form-control"onchange="calculateRowR(' + currentRowR + ')" >';
			myRow += '<option value="21">21%</option>';
			myRow += '<option value="9">9%</option>';
			myRow += '<option value="0">0%</option>';
			myRow += '</select></div></td>';
			myRow += '<td class="align-top"><div class="form-group"><input name="totalR' + currentRowR + '" class="form-control" type="number" value="0.00" readonly /> <input name="numberR[]" type="hidden" readonly value="' + currentRowR + '" /></div></td>';
			myRow += '<td class="align-middle td-actions text-center"><button type="button" onclick="removeRowR(' + currentRowR + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini" id="remove"><i class="material-icons">close</i></button></td>';
			myRow += '</tr>';
			$("#tableInvoiceR tbody").append(myRow);
			currentRowR++;
		}
	<?php endif; ?>

	function calculateSubTotal() {
		subtotal = 0;
		subtotalIn = 0;
		tax6 = 0;
		tax21 = 0;

		$('#tableInvoiceP tbody').find('tr').each(function (i, el) {
			var rowNumber = $(el).find("#numberP")[0].value;
			var total = parseFloat(document.getElementsByName('totalP' + rowNumber)[0].value);
			var tax = document.getElementsByName('taxP' + rowNumber)[0].value;
			var subTax = 0;

			switch (tax) {
				case '9':
					subTax = total * (9 / 100);
					tax6 += subTax;
					break;
				case '21':
					subTax = total * (21 / 100);
					tax21 += subTax;
					break;
				default:
					break;
			}

			subtotal += total;
			subtotalIn += subTax;
			subtotalIn += total;

		});

		document.getElementById("subtotal").innerHTML = subtotal.toFixed(2);
		document.getElementById("subtotalIn").innerHTML = subtotalIn.toFixed(2);
		document.getElementById("subtax6").innerHTML = tax6.toFixed(2);
		document.getElementById("subtax21").innerHTML = tax21.toFixed(2);
	}
	
	function searchArticlenumberP(element, row){
		var value = $(element).val().trim();

		$.ajax({
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/ArticleNumber/0'
		}).done(function(msg){
			articles = JSON.parse(msg);
			var html = '';

			$.each(articles.products, function(index, value){
				html += '<li class="list-group-item list-group-item-action" onclick="clickSuggestionListP('+row+', '+index+')"><span class="font-weight-bold mr-1">'+value.ArticleNumber+'</span> | '+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</li>';
			});

			$(element).closest("td").find("ul.suggestions-listgroup").html(html);
		});
	}

	function searchEanCodeP(element, row){
		var value = $(element).val().trim();

		$.ajax({
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/EanCode/0'
		}).done(function(msg){
			articles = JSON.parse(msg);
			var html = '';

			$.each(articles.products, function(index, value){
				html += '<li class="list-group-item list-group-item-action" onclick="clickSuggestionListP('+row+', '+index+')"><span class="font-weight-bold mr-1">'+value.EanCode+'</span> | '+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</li>';
			});

			$(element).closest("td").find("ul.suggestions-listgroup").html(html);
		});
	}

	function searchDescriptionP(element, row){
		var value = $(element).val().trim();

		$.ajax({
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/Description/0'
		}).done(function(msg){
			articles = JSON.parse(msg);
			var html = '';

			$.each(articles.products, function(index, value){
				html += '<li class="list-group-item list-group-item-action" onclick="clickSuggestionListP('+row+', '+index+')">'+value.ArticleNumber+' | <span class="font-weight-bold ml-1">'+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</span></li>';
			});

			$(element).closest("td").find("ul.suggestions-listgroup").html(html);
		});
	}
	
	<?php if ($customer != null): ?>
		function searchArticlenumberR(element, row){
			var value = $(element).val().trim();
			
			$.ajax({
				url: '<?= site_url() ?>/product/searchProducts/'+value+'/ArticleNumber/0'
				
			}).done(function(msg){
				articles = JSON.parse(msg);
				var html = '';
				
				$.each(articles.products, function(index, value){
					html += '<li class="list-group-item list-group-item-action" onclick="clickSuggestionListR('+row+', '+index+')"><span class="font-weight-bold mr-1">'+value.ArticleNumber+'</span> | '+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</li>';
				});
				
				$(element).closest("td").find("ul.suggestions-listgroup").html(html);
			});
		}
		
		function searchEanCodeR(element, row){
			var value = $(element).val().trim();
			
			$.ajax({
				url: '<?= site_url() ?>/product/searchProducts/'+value+'/EanCode/0'
			}).done(function(msg){
				articles = JSON.parse(msg);
				var html = '';
				
				$.each(articles.products, function(index, value){
					html += '<li class="list-group-item list-group-item-action" onclick="clickSuggestionListR('+row+', '+index+')"><span class="font-weight-bold mr-1">'+value.EanCode+'</span> | '+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</li>';
				});
				
				$(element).closest("td").find("ul.suggestions-listgroup").html(html);
			});
		}
		
		function searchDescriptionR(element, row){
			var value = $(element).val().trim();
			
			$.ajax({
				url: '<?= site_url() ?>/product/searchProducts/'+value+'/Description/0'
			}).done(function(msg){
				articles = JSON.parse(msg);
				var html = '';
				
				$.each(articles.products, function(index, value){
					html += '<li class="list-group-item list-group-item-action" onclick="clickSuggestionListR('+row+', '+index+')">'+value.ArticleNumber+' | <span class="font-weight-bold ml-1">'+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</span></li>';
				});
				
				$(element).closest("td").find("ul.suggestions-listgroup").html(html);
			});
		}
	<?php endif; ?>

	function clickSuggestionListP(row, index){
		$('[name="articlenumberP'+row+'"]').val(articles['products'][index]['ArticleNumber']);
		$('[name="ean_codeP'+row+'"]').val(articles['products'][index]['EanCode']);
		$('[name="articledescriptionP'+row+'"]').val(articles['products'][index]['Description']);
		$('[name="salespriceP'+row+'"]').val(articles['products'][index]['SalesPrice']);
		$('[name="taxP'+row+'"]').val(articles['products'][index]['BTW']);
		calculateRowP(row);
		
		<?php if ($customer != null): ?>
			// Get priceagreements.
			$.ajax({
				url: '<?= site_url() ?>/customers/searchPriceagreement/'+customerId+'/'+articles['products'][index]['ArticleNumber']
			}).done(function(msg){
				dataP = JSON.parse(msg);
				if (dataP['error']) {
					console.log(dataP['error']);
				}
				else if (dataP['priceagreement'] != null) {
					$('[name="salespriceP'+row+'"]').val(dataP['priceagreement']['Price']);
					$('[name="discountP'+row+'"]').val(dataP['priceagreement']['Discount']);
					calculateRowP(row);
				}
			});
		<?php endif; ?>
	}
	
	<?php if ($customer != null): ?>
		function clickSuggestionListR(row, index){
			$('[name="articlenumberR'+row+'"]').val(articles['products'][index]['ArticleNumber']);
			$('[name="articledescriptionR'+row+'"]').val(articles['products'][index]['Description']);
			$('[name="salespriceR'+row+'"]').val(articles['products'][index]['SalesPrice']);
			$('[name="taxR'+row+'"]').val(articles['products'][index]['BTW']);
			if (articles['products'][index]['ProductKind'] == 3) { // Product is domain
				$("#isDomainR"+row).show();
				$("#isNotDomainR"+row).hide();
				$('[name="domainR'+row+'"]').prop("disabled", false);
				$("#articledescriptionR1"+row).prop("disabled", true);
				$("#articledescription_domainR"+row).prop("disabled", false);
			}
			else {
				$("#isDomainR"+row).hide();
				$("#isNotDomainR"+row).show();
				$('[name="domainR'+row+'"] option:not([value])').prop("selected", true);
				$('[name="domainR'+row+'"]').prop("disabled", true);
				$("#articledescriptionR1"+row).prop("disabled", false);
				$("#articledescription_domainR"+row).prop("disabled", true);
			}
			calculateRowR(row);
		}
	<?php endif; ?>
	
	function toggleRequireInvoice() {
		var val = $("#toggle_require_invoice").prop('checked');
		if (val == true) {
			$('input[name="mail_addressP"]').prop('required', true);
			$('input[name="termofpaymentP"]').prop('required', true);
			$('input[name="invoicedateP"]').prop('required', true);
			$('input[name^="articlenumberP"]').prop('required', true);
		}
		else {
			$('input[name="mail_addressP"]').prop('required', false);
			$('input[name="termofpaymentP"]').prop('required', false);
			$('input[name="invoicedateP"]').prop('required', false);
			$('input[name^="articlenumberP"]').prop('required', false);
		}
	}
	
	function toggleRequierRepeating() {
		var val = $("#toggle_require_repeating").prop('checked');
		if (val == true) {
			$('input[name="invoicedateR"]').prop('required', true);
			$('input[name="termofpaymentR"]').prop('required', true);
			$('input[name="invoicedescriptionR"]').prop('required', true);
			$('input[name="articlenumberR1"]').prop('required', true);
		}
		else {
			$('input[name="invoicedateR"]').prop('required', false);
			$('input[name="termofpaymentR"]').prop('required', false);
			$('input[name="invoicedescriptionR"]').prop('required', false);
			$('input[name="articlenumberR1"]').prop('required', false);
		}
	}

</script>

<?php include 'application/views/inc/footer.php'; ?>
