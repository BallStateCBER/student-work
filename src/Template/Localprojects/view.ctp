<h1>
    <?= $localproject->name; ?>
</h1>
<div class="row">
    <div class="col-lg-8">
        <?php if ($localproject->description): ?>
            <p>
                <?= $localproject->description ?>
            </p>
        <?php else: ?>
            <p>
                There is no description for <?= $localproject->name ?> yet. Go on, put it up, or we aren't gonna know how to be proud of you!
            </p>
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <h6>Organization</h6>
        <a href="<?= $localproject->organization ?>"><?= $localproject->organization ?></a>
    </div>
    <div class="col-lg-4">
        <h6>Grant</h6>
        <?= $localproject->grant->name ?: 'None/not applicable'; ?>
    </div>
</div>
<?php if ($localproject->users): ?>
    <div class="row">
        <div class="col-sm-9">
            <h4>Publication credits</h4>
                <?php foreach ($localproject->users as $user): ?>
                    <h6><?= $user->name; ?></h6>
                    <p>
                        <u>Website role:</u> <?= $user->_joinData->employee_role; ?><br />
                        <u>Job title:</u> <?= $this->Html->link($user->position, ['controller' => 'Users', 'action' => 'view', $user->id]); ?>
                    </p>
                <?php endforeach; ?>
            <?php else: ?>
                No one has taken credit for <?= $localproject->name ?> yet. Were you on the researching team? <?= $this->Html->link('Take Credit!', ['controller' => 'Publications', 'action' => 'edit', $localproject->id]); ?>
        </div>
    </div>
<?php endif; ?>
