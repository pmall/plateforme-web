<? if(count($jobs) == 0): ?>
<p>
  Il n'y a pas de tâches à afficher.
</p>
<? else: ?>
<? include 'helpers/jobs.php' ?>
<table>
  <tr>
    <th>Projet</th>
    <th>Analysis</th>
    <th>Type</th>
    <th>Status</th>
    <th>Start</th>
    <th>End</th>
  </tr>
  <? foreach($jobs as $job): ?>
  <tr>
    <td><?= format($job->name_project) ?></td>
    <td><?= format($job->name_analysis) ?></td>
    <td><?= format($job->type) ?></td>
    <td><?= format($job->status) ?></td>
    <td><?= format($job->start) ?></td>
    <td><?= format($job->end) ?></td>
  </tr>
  <? endforeach; ?>
</table>
<? endif; ?>
