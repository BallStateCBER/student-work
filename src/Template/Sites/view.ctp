<?php if ($site->image): ?>
    <?= $this->Html->image('sites'.DS.$site->image, [
        'alt' => $site->site_name,
        'class' => 'img-account float-right'
    ]); ?>
<?php else: ?>
    <?= $this->Html->image('cber-logo.png', [
        'alt' => $site->site_name,
        'class' => 'img-account float-right'
    ]); ?>
<?php endif; ?>
<h1>
    <?= $site->site_name; ?>
</h1>
<div class="row">
    <div class="col-lg-8">
        <?php if ($site->description): ?>
            <p>
                <?= $site->description ?>
            </p>
        <?php else: ?>
            <p>
                There is no description for <?= $site->site_name ?> yet. Are you the developers behind it? You should probably write your site a description or it'll feel unloved.
            </p>
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <h6>URL</h6>
        <a href="<?= $site->url ?>"><?= $site->url ?></a>
    </div>
    <div class="col-lg-4">
        <h6>Grant</h6>
        <?= isset($site->grant->name) ? $site->grant->name : 'None/not applicable'; ?>
    </div>
    <div class="col-lg-4">
        <?php if ($site->in_progress): ?>
            <h6>Site started on:</h6>
            <?php $date = strtotime($site->date_live); ?>
            <?= date('F jS, Y', $date); ?>
        <?php else: ?>
            <h6>Site live:</h6>
            <?php $date = strtotime($site->date_live); ?>
            <?= date('F jS, Y', $date); ?>
        <?php endif; ?>
    </div>
</div>
<?php if ($site->users): ?>
    <div class="row">
        <div class="col-sm-9">
            <h4>Site credits</h4>
                <?php foreach ($site->users as $user): ?>
                    <h6><?= $user->name; ?></h6>
                    <p>
                        <u>Website role:</u> <?= $user->_joinData->employee_role; ?><br />
                        <u>Job title:</u> <?= $this->Html->link($user->position, ['controller' => 'Users', 'action' => 'view', $user->id]); ?>
                    </p>
                <?php endforeach; ?>
            <?php else: ?>
                No one has taken credit for <?= $site->name ?> yet. Were you on the developing team? <?= $this->Html->link('Take Credit!', ['controller' => 'Sites', 'action' => 'edit', $site->id]); ?>
        </div>
    </div>
<?php endif; ?>
