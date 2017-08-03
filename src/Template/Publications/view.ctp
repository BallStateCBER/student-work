<?php if ($publication->cover): ?>
    <?= $this->Html->image('sites'.DS.$publication->cover, [
        'alt' => $publication->title,
        'class' => 'img-account float-right'
    ]); ?>
<?php else: ?>
    <?= $this->Html->image('cber-logo.png', [
        'alt' => $publication->title,
        'class' => 'img-account float-right'
    ]); ?>
<?php endif; ?>
<h1>
    <?= $publication->title; ?>
</h1>
<div class="row">
    <div class="col-lg-8">
        <?php if ($publication->abstract): ?>
            <p>
                <?= $publication->abstract ?>
            </p>
        <?php else: ?>
            <p>
                There is no abstract for <?= $publication->title ?> yet. Go on, put it up, or we aren't gonna know how to be proud of you!
            </p>
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <h6>URL</h6>
        <a href="<?= $publication->url ?>"><?= $publication->url ?></a>
    </div>
    <div class="col-lg-4">
        <h6>Sponsor</h6>
        <?= $publication->sponsor; ?>
    </div>
</div>
<?php if ($publication->users): ?>
    <div class="row">
        <div class="col-sm-9">
            <h4>Publication credits</h4>
                <?php foreach ($publication->users as $user): ?>
                    <h6><?= $user->name; ?></h6>
                    <p>
                        <u>Website role:</u> <?= $user->_joinData->employee_role; ?><br />
                        <u>Job title:</u> <?= $this->Html->link($user->position, ['controller' => 'Users', 'action' => 'view', $user->id]); ?>
                    </p>
                <?php endforeach; ?>
            <?php else: ?>
                No one has taken credit for <?= $publication->title ?> yet. Were you on the researching team? <?= $this->Html->link('Take Credit!', ['controller' => 'Publications', 'action' => 'edit', $publication->id]); ?>
        </div>
    </div>
<?php endif; ?>
