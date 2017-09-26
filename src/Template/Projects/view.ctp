<?php if ($project->image) {
    echo $this->Html->image('projects'.DS.$project->image, [
        'alt' => $project->name,
        'class' => 'img-account float-right'
    ]);
} ?>
<h1>
    <?= $project->name ?>
</h1>
<small>Admin: <?= $this->Html->link('Edit this project source', ['controller' => 'Projects', 'action' => 'edit', $project->id], ['class' => 'text-danger']) ?></small>
<div class="row">
    <div class="col-lg-8 no-marg">
        <?= $project->description ?: "<p>There is no description for $project->name yet. Go on, put it up, or we aren't gonna know how to be proud of you!</p>" ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <h6>Organization</h6>
        <?= $project->organization ?>
    </div>
    <div class="col-lg-4">
        <h6>Fund number</h6>
        <?= isset($project->fund_id) ? $this->Html->link($fundNumber, "/funds/index#fund-$project->fund_id") : 'None/not applicable' ?>
    </div>
</div>
<?php if ($project->funding_details): ?>
    <div class="row">
        <div class="col-lg-6">
            <h6>Funding details</h6>
            <?= $project->funding_details ?>
        </div>
    </div>
<?php endif ?>
<?php if ($project->users): ?>
    <div class="row">
        <div class="col-sm-9">
            <h4>Project credits</h4>
                <?php foreach ($project->users as $user): ?>
                    <h6><?= $user->name ?></h6>
                    <p>
                        <u>Project role:</u> <?= $user->_joinData->role ?><br />
                        <u>Job title:</u> <?= $this->Html->link($user->position, ['controller' => 'Users', 'action' => 'view', $user->id]) ?>
                    </p>
                <?php endforeach ?>
<?php else: ?>
                No one has taken credit for <?= $project->name ?> yet. Were you on the project team? <?= $this->Html->link('Take Credit!', ['controller' => 'Projects', 'action' => 'edit', $project->id]) ?>
        </div>
    </div>
<?php endif ?>
