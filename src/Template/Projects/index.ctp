<div class="row">
    <div class="col-lg-12">
        <h3><?= __('Projects') ?></h3>
        <div class="row">
            <?php $x = 1; ?>
            <?php foreach ($projects as $project): ?>
            <?php if ($x == 1): ?>
                <div class="col-lg-6">
                    <table class="whole-table" cellpadding="10" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('image') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                <th scope="col" class="non-cell"><?= $this->Paginator->sort('users', ['label' => 'Contributors']) ?></th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
            <?php elseif ($x == $halfCount + 1): ?>
                <div class="col-lg-6">
                    <table class="whole-table" cellpadding="10" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col" class="non-mobile"><?= __('Image') ?></th>
                                <th scope="col" class="non-mobile"><?= __('Name') ?></th>
                                <th scope="col" class="non-mobile"><?= __('Contributors') ?></th>
                                <th scope="col" class="non-mobile actions"><?= __('Actions') ?></th>
                            </tr>
            <?php endif ?>
            <?php if ($x == $halfCount + 1 || $x == 1): ?>
                </thead>
                <tbody>
            <?php endif ?>
                        <tr class="table-index">
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
                            <td class="non-cell">
                                <?php if (empty($project->users)): ?>
                                    <i>No users set.</i>
                                <?php endif ?>
                                <?php foreach ($project->users as $user): ?>
                                    <span class="project-users">
                                        <?= $user->name ?><?= $user->_joinData['role'] ? ": " . $user->_joinData['role'] : '' ?>
                                    </span>
                                <?php endforeach; ?>
                            </td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $project->id]) ?>
                                <?= $activeUser['admin'] == 1 ? $this->Html->link(__('Edit'), ['action' => 'edit', $project->id]) : '' ?>
                                <?= $activeUser['admin'] == 1 && empty($project->reports) ? $this->Form->postLink(__('Delete'), ['action' => 'delete', $project->id], ['confirm' => __('Are you sure you want to delete {0}?', $project->name)]) : '' ?>
                            </td>
                        </tr>
                        <?php if ($x == $halfCount || $x == $count): ?>
                        </tbody>
                    </table>
                </div>
                        <?php endif ?>
            <?php $x = $x + 1 ?>
            <?php endforeach ?>
        </div>
    </div>
</div>
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
