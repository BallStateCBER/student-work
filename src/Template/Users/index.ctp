
<?php $x = 0 ?>
<?php foreach ($users as $user): ?>
    <?= $x % 5 == 0 || $x == 0 ? '<div class="row">' : '' ?>
    <div class="col-lg-2 index text-center">
        <?php if ($user->image): ?>
            <?= $this->Html->link($this->Html->image('users'.DS.$user->image, [
                'alt' => $user->name,
                'class' => 'img-index'
            ]),
            ['controller' => 'Users', 'action' => 'view', $user->id],
            ['escape' => false]) ?>
        <?php else: ?>
            <?= $this->Html->link($this->Html->image('cber-staff.jpg', [
                'alt' => $user->name,
                'class' => 'img-index'
            ]),
            ['controller' => 'Users', 'action' => 'view', $user->id],
            ['escape' => false]) ?>
        <?php endif ?>
        <h5><?= $user->name ?: "Employee #$user->id" ?></h5>
        <?= $this->Text->autoLinkEmails($user->email) ?>
        <p>
            <?php if (isset($user->position)): ?>
                <?= $user->end_date == null || $user->end_date >= date('Y-m-d') ? 'Current' : 'Former' ?> <?= $this->Html->link($user->position, ['controller' => 'Users', 'action' => 'view', $user->id])  ?>.
            <?php else: ?>
                <I>No position specified.</I>
            <?php endif ?>
            <br />
            <?php if ($this->request->session()->read('Auth.User.admin') == 1): ?>
                <small>Admin: <?= $this->Html->link('edit user', ['controller' => 'Users', 'action' => 'edit', $user->id], ['class'=>'text-danger']) ?></small>
            <?php endif ?>
        </p>
    </div>
    <?= $x % 5 == 4 ? '</div>' : '' ?>
    <?php $x = $x + 1 ?>
<?php endforeach ?>
<?php if (count($users) < 1): ?>
    <p>
        Sorry, there's nothing here! This probably suggests a problem. Contact <a href="mailto:<?= Configure::read('admin_email') ?>">the admin</a> for advice!
    </p>
    <p>
        There might also just actually be nothing here. Go ahead and <?= $this->Html->link('add a user', [
            'controller' => 'Users', 'action' => 'register'
        ]) ?> so this goofy placeholder message goes away.
    </p>
<?php endif ?>
