<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Resultaten');
define('PAGE', 'results');


include 'application/views/inc/header.php';

/* Set locale to Dutch */
setlocale(LC_ALL, 'nl_NL');
?>

<?php if ($bookyears == null): ?>
	<p class="alert alert-warning">Je hebt nog geen boekjaren toegevoegd in de instellingen</p>
<?php endif; ?>

<form method="get">
	<div class="row">
		<div class="col-auto ml-auto">
			<?= $year; ?>
		</div>
		<div class="col-auto">
			<button class="btn btn-success mt-2" type="submit" name="export_csv" value="true">Genereer CSV</button>
		</div>
	</div>
</form>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">insert_chart</i>
				</div>
				<h4 class="card-title">Overzicht in- en verkoop totalen per maand grafiek</h4>
			</div>
			<div class="card-body">
				<div class="ct-chart"></div>
				<div class="row">
					<div class="col-md"><i class="status-block mr-1" style="background-color: #00bcd4"></i>Verkoop (excl. BTW)<br></div>
					<div class="col-md"><i class="status-block mr-1" style="background-color: #f44336"></i>Inkoop (excl. BTW)</div>
					<div class="col-md"><i class="status-block mr-1" style="background-color: #4caf50"></i>Betalingen (incl. BTW)</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">insert_chart</i>
				</div>
				<h4 class="card-title">Overzicht in- en verkoop totalen per maand tabel</h4>
			</div>
			<div class="card-body">
				
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="resultsTable" width="100%">
						<thead>
							<tr>
								<th>Maand</th>
								<th class="text-right">Verkoop (excl. BTW)</th>
								<th class="text-right">Inkoop (excl. BTW)</th>
								<th class="text-right">Betalingen (incl. BTW)</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$totalSold = 0;
							$totalBought = 0;
							$totalPayment = 0;
							for ($i = 1; $i < 13; $i++) {
								$totalSold = $totalSold + (${"month" . $i . "Sold"}->TotalEx ?? 0);
								$totalBought = $totalBought + (${"month" . $i . "Bought"}->TotalEx ?? 0);
								$totalPayment = $totalPayment + (${"month" . $i . "SoldPayment"}->Amount ?? 0);
							?>
								<tr>
									<td data-order="<?= $i ?>"><?= ucfirst(strftime('%B', strtotime('01-' . $i . '-2015'))) ?></td>
									<td class="text-right"><?= isset(${"month" . $i . "Sold"}->TotalEx) ? number_format(${"month" . $i . "Sold"}->TotalEx, 2, '.', '') : '0.00' ?></td>
									<td class="text-right"><?= isset(${"month" . $i . "Bought"}->TotalEx) ? number_format(${"month" . $i . "Bought"}->TotalEx, 2, '.', '') : '0.00' ?></td>
									<td class="text-right"><?= isset(${"month" . $i . "SoldPayment"}->Amount) ? number_format(${"month" . $i . "SoldPayment"}->Amount, 2, '.', '') : '0.00' ?></td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th>Totaal</th>
								<th class="text-right"><?= number_format($totalSold, 2, '.', '') ?></th>
								<th class="text-right"><?= number_format($totalBought, 2, '.', '') ?></th>
								<th class="text-right"><?= number_format($totalPayment, 2, '.', '') ?></th>
							</tr>
						</tfoot>
					</table>
				</div>
				
			</div>
		</div>
	</div>
</div>

<?php
$data = "";
$labels = "";
$sold = "[";
$bought = "[";
$soldPayment = "[";

for ($i = 1; $i < 13; $i++) {

	$labels .= "'" . ucfirst(strftime('%B', strtotime('01-' . $i . '-2015'))) . "', ";


	$data .= "{m: '" . strftime('%B', strtotime('01-' . $i . '-2015')) . "', ";
	$data .= "s: ";
	if (isset(${"month" . $i . "Sold"}->TotalEx)) {
		$data .= ${"month" . $i . "Sold"}->TotalEx;
		$sold .= number_format(${"month" . $i . "Sold"}->TotalEx, 2, '.', '') . ", ";
	} else {
		$data .= "0";
		$sold .= "0, ";
	}
	$data .= ", ";
	$data .= "b: ";
	if (isset(${"month" . $i . "Bought"}->TotalEx)) {
		$data .= ${"month" . $i . "Bought"}->TotalEx;
		$bought .= number_format(${"month" . $i . "Bought"}->TotalEx, 2, '.', '') . ", ";
	} else {
		$data .= "0";
		$bought .= "0, ";
	}
	$data .="}, ";
	$data .= "p: ";
	if (isset(${"month" . $i . "SoldPayment"}->Amount)) {
		$data .= ${"month" . $i . "SoldPayment"}->Amount;
		$soldPayment .= number_format(${"month" . $i . "SoldPayment"}->Amount, 2, '.', '') . ", ";
	} else {
		$data .= "0";
		$soldPayment .= "0, ";
	}
	$data .="}, ";
}
$sold .= "]";
$bought .= "]";
$soldPayment .= "]";

$data = substr($data, 0, -1);

?>

<script type="text/javascript">

	var data = {
		labels: [<?= $labels; ?>],
		series: [
<?= $sold; ?>,
<?= $bought; ?>,
<?= $soldPayment; ?>

		]
	};

	var options = {
		fullWidth: true,
		height: "320",
		plugins: [
			Chartist.plugins.tooltip()
		],
		axisX: {
			showGrid: false
		}
	};

	var responsiveOptions = [
		['screen and (min-width: 641px) and (max-width: 1024px)', {
			seriesBarDistance: 10,
			axisX: {
				labelInterpolationFnc: function (value) {
					return value;
				}
			}
		}],
		['screen and (max-width: 991px)', {
			axisX: {
			  labelInterpolationFnc: function (value) {
				return value.slice(0, 3);
			  }
			}
		}],
		['screen and (max-width: 640px)', {
			seriesBarDistance: 5,
			axisX: {
				labelInterpolationFnc: function (value) {
					return value[0];
				}
			}
		}]
	];

	var chart = new Chartist.Bar('.ct-chart', data, options, responsiveOptions);

	// Count each element of a chart that has been drawed.
	var seq = 0;
	chart.on('created', function() {
	  seq = 0;
	});
	// Animate chart bars.
	chart.on('draw', function(object){
		if (object.type === 'bar') {
			object.element.animate({
				y2: {
					begin: seq++ * 100,
					dur: 1000,
					from: object.y1,
					to: object.y2,
					easing: 'easeOutQuart'
				}
			})
		}
	});
	
	$(document).ready(function () {
		$('#resultsTable').DataTable({
			"language": {
				"url": "/assets/language/Dutch.json"
			},
			"bLengthChange": false,
			"bPaginate": false,
			"bInfo": false
		});
	});
	
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js" integrity="sha256-GcknncGKzlKm69d+sp+k3A2NyQE+jnu43aBl6rrDN2I=" crossorigin="anonymous"></script>

<?php include 'application/views/inc/footer.php'; ?>
