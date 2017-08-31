<?php use Cake\Core\Configure;

?>
<?php $x = 0; ?>
<?php foreach ($projects as $project): ?>
    <?= $x % 3 == 0 || $x == 0 ? '<div class="row">' : ''; ?>
    <div class="col-lg-3 index text-center">
        <h5>
            <?= $this->Html->link($project->name, [
                'controller' => 'Projects', 'action' => 'view', $project->id
            ]) ?>
        </h5>
        <?= $project->organization; ?>
        <p>
            <?= $project->description; ?>
        </p>
        <?php foreach ($project->users as $user): ?>
            <ul>
                <li>
                    <?= $user->name; ?>, <?= $user->_joinData->role; ?>
                </li>
            </ul>
        <?php endforeach; ?>
    </div>
    <?= $x % 3 == 2 ? '</div>' : ''; ?>
    <?php $x = $x + 1; ?>
<?php endforeach; ?>
<?php if (!isset($project->id)): ?>
    <p>
        Sorry, there's nothing here! This probably suggests a problem. Contact <a href="mailto:<?= Configure::read('admin_email') ?>">the admin</a> for advice!
    </p>
    <p>
        There might also just actually be nothing here. Go ahead and <?= $this->Html->link('add a project', [
            'controller' => 'Projects', 'action' => 'add'
        ]) ?> so this goofy placeholder message goes away.
    </p>
<?php endif; ?>
