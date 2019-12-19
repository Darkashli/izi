<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Projecten');
define('PAGE', 'projects');

include 'application/views/inc/header.php';

$phaseNum = 1;
$projectStatus = getProjectStatus($project->Id, $firstStatus->Id, $latestStatus->Id);
$projectWorkedHours = 0;
$projectPrognosisPercentage = 0;
$projectWorkedHours = getTotalWorkProject($project->Id);
$projectResult = $projectWorkedHours - $project->Prognosis;

foreach ($projectPhases as $phase):
	$tickets = getProjectphaseTickets($phase->Id);
	$phaseStatus = getPhaseStatus($phase->Id, $firstStatus->Id, $latestStatus->Id);
	$phaseTotalWorkedHours = getTotalWorkPhase($phase->Id);
	$phaseResult = $phaseTotalWorkedHours - $phase->Prognosis;
	$phaseProjectPercentage = $project->Prognosis != 0 ? 100 / $project->Prognosis * $phase->Prognosis : 0;
?>
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">flag</i>
					</div>
					<h4 class="card-title">Fase <?= $phaseNum.' '.$phase->Name ?> (<?= round($phaseProjectPercentage) ?>% van totale project)</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<td class="w10">Ticketnummer</td>
									<td class="w10">Ticketomschrijving</td>
									<td class="w10">Laatst gekoppeld aan</td>
									<td class="w10">Prognose</td>
									<td class="w10">Percentage van deze fase</td>
									<td class="w10">Status</td>
									<td class="w10">Subtotaal</td>
									<td class="w10">Totaal</td>
									<td class="w10">Resultaat</td>
								</tr>
							</thead>
							<tbody>
								<?php $phasePrognosisPercentage = 0; foreach ($tickets as $ticket):
									$ticketStatus = getStatus($ticket->Status);
									$ticketPhasePrognosisPercentage = ($ticket->Prognosis / $phase->Prognosis) * 100;
									$ticketProjectPrognosisPercentage = ($ticket->Prognosis / $project->Prognosis) * 100;
									$ticketTotalWorked = getSumTotalwork($ticket->TicketId);
									$ticketResult = $ticketTotalWorked - $ticket->Prognosis;
									$phasePrognosisPercentage += $ticket->Status == $latestStatus->Id ? $ticketPhasePrognosisPercentage : 0;
									$projectPrognosisPercentage += $ticket->Status == $latestStatus->Id ? $ticketProjectPrognosisPercentage : 0;
								?>
									<tr data-href="<?= base_url() . 'work/update/' . $ticket->TicketId; ?>">
										<td><?= $ticket->Number ?></td>
										<td><?= $ticket->Description ?></td>
										<td><?= getAccountName($ticket->UserIdLink) ?></td>
										<td><?= $ticket->Prognosis ?></td>
										<td><?= round($ticketPhasePrognosisPercentage) ?>%</td>
										<td><?= getStatusBlock($ticketStatus->Id) ?> <?= $ticketStatus->Description ?></td>
										<td><?= number_format($ticketTotalWorked, 2, ',', '.') ?></td>
										<td><?= $ticket->Status == $latestStatus->Id ? number_format($ticketTotalWorked, 2, ',', '.') : 'n.n.b.' ?></td>
										<td>
											<?php if ($ticket->Status == $latestStatus->Id): ?>
												<?php if ($ticketResult > 0): ?>
													<span class="bg-danger text-white"><?= number_format($ticketResult, 2, ',', '.') ?></span>
												<?php else: ?>
													<span class="bg-success text-white"><?= number_format($ticketResult, 2, ',', '.') ?></span>
												<?php endif; ?>
											<?php else: ?>
												n.n.b.
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col">Status&nbsp;van&nbsp;deze&nbsp;fase: <?= str_replace(' ', '&nbsp;', $phaseStatus) ?></div>
						<div class="col"><?= number_format($phase->Prognosis, 2, ',', '.') ?> uren begroot voor deze fase</div>
						<div class="col"><?= number_format($phaseTotalWorkedHours, 2, ',', '.') ?> uren besteed voor deze fase</div>
						<div class="col"><?= round($phasePrognosisPercentage) ?>% van deze fase afgerond</div>
						<div class="col">
							<?php if ($phaseStatus == 'Gesloten'): ?>
								<?php if ($phaseResult > 0): ?>
									<span class="bg-danger text-white">Resultaat: <?= number_format($phaseResult, 2, ',', '.') ?></span>
								<?php else: ?>
									<span class="bg-success text-white">Resultaat: <?= number_format($phaseResult, 2, ',', '.') ?></span>
								<?php endif; ?>
							<?php else: ?>
								n.n.b.
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php $phaseNum++; endforeach; ?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-header-icon card-header-primary">
				<div class="card-icon">
					<i class="material-icons">graphic_eq</i>
				</div>
				<h4 class="card-title">Project totalen</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col">Status&nbsp;van&nbsp;dit&nbsp;project: <?= str_replace(' ', '&nbsp;', $projectStatus) ?></div>
					<div class="col"><?= number_format($project->Prognosis, 2, ',', '.') ?> uren begroot voor dit project</div>
					<div class="col"><?= number_format($projectWorkedHours, 2, ',', '.') ?> uren besteed voor dit project</div>
					<div class="col"><?= round($projectPrognosisPercentage) ?>% van dit project afgerond</div>
					<div class="col">
						<?php if ($projectStatus == 'Gesloten'): ?>
							<?php if ($projectResult > 0): ?>
								<span class="bg-danger text-white">Resultaat: <?= number_format($projectResult, 2, ',', '.') ?></span>
							<?php else: ?>
								<span class="bg-success text-white">Resultaat: <?= number_format($projectResult, 2, ',', '.') ?></span>
							<?php endif; ?>
						<?php else: ?>
							n.n.b.
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'application/views/inc/footer.php'; ?>
