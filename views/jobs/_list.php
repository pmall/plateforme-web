<? if(count($jobs) == 0): ?>
<p>
  Il n'y a pas de tâches à afficher.
</p>
<? else: ?>
<table class="table table-striped table-condensed">
  <thead>
    <tr>
      <th>Projet</th>
      <th>Analysis</th>
      <th>Type</th>
      <th>Status</th>
      <th>Start</th>
      <th>End</th>
    </tr>
  </thead>
  <tbody>
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
  </tbody>
</table>
<? endif; ?>
