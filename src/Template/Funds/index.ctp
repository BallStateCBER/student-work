<h3><?= __('Funds') ?></h3>
<?= $this->Html->link(__('New Funding Source'), ['action' => 'add']) ?>
<table cellpadding="15" cellspacing="0">
    <thead>
        <tr>
            <th scope="col"><?= $this->Paginator->sort('name', 'Fund Number') ?></th>
            <th scope="col"><?= $this->Paginator->sort('organization') ?></th>
            <th scope="col"><?= $this->Paginator->sort('amount') ?></th>
            <th scope="col" class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($funds as $fund): ?>
        <tr>
            <td><?= h($fund->name) ?></td>
            <td><?= h($fund->organization) ?></td>
            <td><?= h($fund->amount) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['action' => 'view', $fund->id]) ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $fund->id]) ?>
                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $fund->id], ['confirm' => __('Are you sure you want to delete # {0}?', $fund->id)]) ?>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
<div class="paginator">
    <ul class="pagination">
        <?= $this->Paginator->first('<< ' . __('first')) ?>
        <?= $this->Paginator->hasPrev() ? $this->Paginator->prev('< ' . __('previous')) : '' ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->hasNext() ? $this->Paginator->next(__('next') . ' >') : '' ?>
        <?= $this->Paginator->last(__('last') . ' >>') ?>
    </ul>
    <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
</div>
