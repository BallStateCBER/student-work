<h3><?= __('Reports') ?></h3>
<?= $this->Html->link(__('New Report'), ['action' => 'add']) ?>
<table cellpadding="15" cellspacing="0">
    <thead>
        <tr>
            <th scope="col"><?= $this->Paginator->sort('project_name') ?></th>
            <th scope="col"><?= $this->Paginator->sort('student_id') ?></th>
            <th scope="col"><?= $this->Paginator->sort('supervisor_id') ?></th>
            <th scope="col"><?= $this->Paginator->sort('start_date') ?></th>
            <th scope="col"><?= $this->Paginator->sort('end_date') ?></th>
            <th scope="col" class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reports as $report): ?>
        <tr>
            <td><?= h($report->project_name) ?></td>
            <td><?= h($report->student_id) ?></td>
            <td><?= h($report->supervisor_id) ?></td>
            <td><?= h(date('F j, Y', strtotime($report->start_date))) ?></td>
            <td><?= h(date('F j, Y', strtotime($report->end_date))) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['action' => 'view', $report->id]) ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $report->id]) ?>
                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $report->id], ['confirm' => __('Are you sure you want to delete # {0}?', $report->id)]) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="paginator">
    <ul class="pagination">
        <?= $this->Paginator->first('<< ' . __('first')) ?>
        <?= $this->Paginator->prev('< ' . __('previous')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('next') . ' >') ?>
        <?= $this->Paginator->last(__('last') . ' >>') ?>
    </ul>
    <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
</div>
