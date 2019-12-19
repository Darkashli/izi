<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<META HTTP-EQUIV="Content-Type" content="text/html; charset=utf-8"/>
		<style>

			html, body {
				height: 100%;
			}

			body {
				margin: 1.5cm 0 0 0;
				text-align: justify;
				font-size: 15px;
				font-family: tahoma, Helvetica, Arial, Verdana;
			}

			#header,
			#footer {
				position: fixed;
				left: 0;
				right: 0;
				color: #aaa;
				font-size: 0.9em;
			}

			#header {
				top: 0;
				border-bottom: 0.1pt solid #aaa;
			}

			#footer {
				bottom: 0;
				border-top: 0.1pt solid #aaa;
			}

			#header table,
			#footer table {
				width: 100%;
				border: none;
			}

			#header td,
			#footer td {
				padding: 0;
				width: 33%;
			}

			#header img {
				margin-top: -10px;
			}
			
			table{
				border-collapse: collapse;
			}
			
			td{
				height: 30px;
				line-height: 30px;
			}

			thead {
				font-weight: bold;
			}

			.w100 {
				width: 100%;
			}

			.text-right {
				text-align: right;
			}
			
			.table-primary,
			.table-primary > th,
			.table-primary > td {
				background-color: #E3EBF6;
			}

		</style>
	</head>

	<body>
		<div id="header">
			<table>
				<tr>
					<td>Overzicht BTW van periode van <?= $from; ?> tot en met <?= $to; ?></td>
				</tr>
			</table>
		</div>

		<table class="w100">
			<thead>
				<tr>
					<td colspan="4" class="text-right"><small>Bedrag waarover omzetbelasting wordt berekend</small></td>
					<td colspan="2" class="text-right"><small>Omzetbelasting</small></td>
				</tr>
				<tr class="table-primary">
					<td>1</td>
					<td colspan="5">Prestaties binnenland</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>1a</td>
					<td>Leveringen/diensten belast met btw hoog</td>
					<td class="text-right">€</td>
					<td class="text-right"><?= $btw['highBtwRules']['exSalesTax'] ?></td>
					<td class="text-right">€</td>
					<td class="text-right"><?= $btw['highBtwRules']['salesTax'] ?></td>
				</tr>
				<tr>
					<td>1b</td>
					<td>Leveringen/diensten belast met 9%</td>
					<td class="text-right">€</td>
					<td class="text-right"><?= $btw['lowBtwRules']['exSalesTax'] ?></td>
					<td class="text-right">€</td>
					<td class="text-right"><?= $btw['lowBtwRules']['salesTax'] ?></td>
				</tr>
				<tr>
					<td>1c</td>
					<td>Leveringen/diensten belast met overige tarieven, behalve 0%</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
				</tr>
				<tr>
					<td>1d</td>
					<td>Privégebruik</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
				</tr>
				<tr>
					<td>1e</td>
					<td>Leveringen/diensten belast met 0% of niet bij u belast</td>
					<td class="text-right">€</td>
					<td class="text-right"><?= $btw['noBtwRules']['exSalesTax'] ?></td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
				</tr>
			</tbody>
			<thead>
				<tr class="table-primary">
					<td>2</td>
					<td colspan="5">Verleggingsregelingen binnenland</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>2a</td>
					<td>Leveringen/diensten waarbij de omzetbelasting naar u is vastgelegd</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
				</tr>
			</tbody>
			<thead>
				<tr class="table-primary">
					<td>3</td>
					<td colspan="5">Prestaties naar of in het buitenland</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>3a</td>
					<td>Leveringen naar buiten de EU (uitvoer)</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
				</tr>
				<tr>
					<td>3b</td>
					<td>Leveringen naatr of diensten in landen binnen de EU</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
				</tr>
				<tr>
					<td>3c</td>
					<td>Installatie/afstandsverkopen binnen de EU</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
				</tr>
			</tbody>
			<thead>
				<tr class="table-primary">
					<td>4</td>
					<td colspan="5">Prestaties vanuit het buitenland aan u verricht</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>4a</td>
					<td>Leveringen/diensten uit landen buiten de EU</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
				</tr>
				<tr>
					<td>4b</td>
					<td>Leveringen/diensten uit landen binnen de EU</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
					<td class="text-right">€</td>
					<td class="text-right">0</td>
				</tr>
			</tbody>
			<thead>
				<tr class="table-primary">
					<td>5</td>
					<td colspan="5">Voorbelasting, kleineondernemersregeling en totaal</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>5a</td>
					<td>Leveringen naar buiten de EU (uitvoer)</td>
					<td class="text-right" colspan="3">€</td>
					<td class="text-right">0</td>
				</tr>
				<tr>
					<td>5b</td>
					<td>Voorbelasting</td>
					<td class="text-right" colspan="3">€</td>
					<td class="text-right">0</td>
				</tr>
				<tr>
					<td>5c</td>
					<td>Subtotaal (rubiek 5a min 5b)</td>
					<td class="text-right" colspan="3">€</td>
					<td class="text-right"><?= $btw['total'] ?></td>
				</tr>
				<tr>
					<td>5d</td>
					<td>Vermindering volgens de kleineondernemersregeling</td>
					<td class="text-right" colspan="3">€</td>
					<td class="text-right">0</td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<th>Totaal</th>
					<th class="text-right" colspan="3">€</th>
					<th class="text-right"><?= $btw['total'] ?></th>
				</tr>
			</tbody>
		</table>

	</body>
</html>
