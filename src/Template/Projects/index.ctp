<div class="row">
    <div class="col-lg-12">
        <h3><?= __('Reports') ?></h3>
        <table cellpadding="15" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('image') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('description') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                <tr>
                    <td>
                        <?php if ($project->image): ?>
                            <?= $this->Html->link($this->Html->image('projects'.DS.$project->image, [
                                'alt' => $project->name,
                                'class' => 'img-thumb'
                            ]),
                            ['controller' => 'Users', 'action' => 'view', $project->id],
                            ['escape' => false]); ?>
                        <?php else: ?>
                            <?= $this->Html->link($this->Html->image('cber-logo.png', [
                                'alt' => $project->name,
                                'class' => 'img-thumb'
                            ]),
                            ['controller' => 'Users', 'action' => 'view', $project->id],
                            ['escape' => false]); ?>
                        <?php endif; ?>
                    </td>
                    <td><?= h($project->name) ?></td>
                    <td><?= h($project->description) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $project->id]) ?>
                        <?= $activeUser['role'] == 'Site Admin' ? $this->Html->link(__('Edit'), ['action' => 'edit', $project->id]) : '' ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $project->id], ['confirm' => __('Are you sure you want to delete # {0}?', $project->id)]) ?>
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
    </div>
</div>
