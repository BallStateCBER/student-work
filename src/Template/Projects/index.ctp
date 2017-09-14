<div class="row">
    <div class="col-lg-12">
        <h3><?= __('Reports') ?></h3>
        <table cellpadding="5" cellspacing="0" class="col-lg-12">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('image') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('users', ['label' => 'Contributors']) ?></th>
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
                            ['controller' => 'Projects', 'action' => 'view', $project->id],
                            ['escape' => false]) ?>
                        <?php else: ?>
                            <?= $this->Html->link($this->Html->image('cber-logo.png', [
                                'alt' => $project->name,
                                'class' => 'img-thumb'
                            ]),
                            ['controller' => 'Projects', 'action' => 'view', $project->id],
                            ['escape' => false]) ?>
                        <?php endif ?>
                    </td>
                    <td><?= h($project->name) ?></td>
                    <td>
                        <?php if (empty($project->users)): ?>
                            <i>No users set.</i>
                        <?php endif ?>
                        <?php
                            foreach ($project->users as $user) {
                                echo '<span class="project-users">' . $user->name . ': ' . $user->_joinData['role'] . '</span>';
                            }
                        ?>
                    </td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $project->id]) ?>
                        <?= $activeUser['admin'] == 1 ? $this->Html->link(__('Edit'), ['action' => 'edit', $project->id]) : '' ?>
                        <?= $activeUser['admin'] == 1 && empty($project->reports) ? $this->Form->postLink(__('Delete'), ['action' => 'delete', $project->id], ['confirm' => __('Are you sure you want to delete {0}?', $project->name)]) : '' ?>
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
    </div>
</div>
