<?php
use Cake\Core\Configure;

?>
<?php $x = 0; ?>
<?php foreach ($publications as $publication): ?>
    <?= $x % 3 == 0 || $x == 0 ? '<div class="row">' : ''; ?>
    <div class="col-lg-3 index text-center">
        <?php if ($publication->cover): ?>
            <?= $this->Html->link($this->Html->image('sites'.DS.$publication->cover, [
                'alt' => $publication->title,
                'class' => 'img-index'
            ]),
            ['controller' => 'Publications', 'action' => 'view', $publication->id],
            ['escape' => false]); ?>
        <?php else: ?>
            <?= $this->Html->link($this->Html->image('cber-logo.png', [
                'alt' => $publication->title,
                'class' => 'img-index'
            ]),
            ['controller' => 'Publications', 'action' => 'view', $publication->id],
            ['escape' => false]); ?>
        <?php endif; ?>
        <h5><?= $publication->title; ?></h5>
        <a href="<?= $publication->url ?>"><?= $publication->url ?></a>
    </div>
    <?= $x % 3 == 2 ? '</div>' : ''; ?>
    <?php $x = $x + 1; ?>
<?php endforeach; ?>
<?php if (!isset($publication->id)): ?>
    <p>
        Sorry, there's nothing here! This probably suggests a problem. Contact <a href="mailto:<?= Configure::read('admin_email') ?>">the admin</a> for advice!
    </p>
    <p>
        There might also just actually be nothing here. Go ahead and <?= $this->Html->link('add a publication', [
            'controller' => 'Publications', 'action' => 'add'
        ]) ?> so this goofy placeholder message goes away.
    </p>
<?php endif; ?>
