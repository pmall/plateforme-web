<? if(count($jobs) == 0): ?>
<p>
  Il n'y a pas de tâches à afficher.
</p>
<? else: ?>
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
    <td><?= h(format($job->name_project)) ?></td>
    <td><?= h(format($job->name_analysis)) ?></td>
    <td><?= h(format($job->type)) ?></td>
    <td><?= h(format($job->status)) ?></td>
    <td><?= h(format($job->start)) ?></td>
    <td><?= h(format($job->end)) ?></td>
  </tr>
  <? endforeach; ?>
</table>
<? endif; ?>
