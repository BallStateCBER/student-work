<?php
if (isset($user->name)) {
    $firstName = explode(' ', trim($user->name));
    $firstName = $firstName[0];
}
if (!isset($user->name)) {
    $firstName = "This Employee";
}
?>
<?php if ($user->image): ?>
    <?= $this->Html->image('users'.DS.$user->image, [
        'alt' => $user->name,
        'class' => 'img-account float-right'
    ]) ?>
<?php else: ?>
    <?= $this->Html->image('cber-staff.jpg', [
        'alt' => $user->name,
        'class' => 'img-account float-right'
    ]) ?>
<?php endif ?>
<h1 class="user-view">
    <?= $user->name ?: "Unnamed employee #$user->id" ?>
</h1>
<div class="row">
    <div class="col-xl-8">
        <p class="view-bio">
            <?php $noBio = "$firstName has not written a bio yet. Are you $firstName? You should write your bio. No, like, how are we supposed to know you're a real person if you don't talk about it on the Internet?" ?>
            <?= $user->bio ?: $noBio ?>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-4">
        <h6>Email</h6>
        <?= $this->Text->autoLinkEmails($user->email) ?>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4">
        <h6>Current Position</h6>
        <?= $user->position ?: "<i>Not specified</i>" ?>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4">
        <h6>Role</h6>
        <?= $user->admin ? 'Site Admin' : 'Student' ?>
    </div>
</div>
<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-3">
        <h6>Birthday</h6>
        <?= $user->birth_date ? date('F jS, Y', strtotime($user->birth_date)) : '<i>Not specifiied</i>' ?>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-3">
        <h6>Start Date</h6>
        <?= $user->start_date ? date('F jS, Y', strtotime($user->start_date)) : '<i>Not specified</i>' ?>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-3">
        <?php if ($user->end_date != null): ?>
            <h6>End Date</h6>
            <?php $date = strtotime($user->end_date) ?>
            <?= date('F jS, Y', $date) ?>
        <?php endif ?>
        <?php if ($user->end_date == null || $user->end_date >= date('Y-m-d')): ?>
            <h6>Currently</h6>
            <h6>employed</h6>
        <?php endif ?>
    </div>
</div>
<div class="row">
    <div class="col-xl-4 col-lg-3 col-md-3">
        <h6>Emergency Contact</h6>
        <?= $user->ice_name ?: "<i>Not specified</i>" ?>
    </div>
    <div class="col-xl-4 col-lg-3 col-md-3">
        <h6>Emergency Contact Number</h6>
        <?php if (isset($user->ice_phone)): ?>
            <?php $iceNumber = $this->Users->numberConvert($user->ice_phone) ?>
            <?= '<a href="tel:'.$iceNumber.'">'.$user->ice_phone.'</a>' ?>
        <?php else: ?>
            <i>Not specified</i>
        <?php endif ?>
    </div>
    <div class="col-xl-4 col-lg-3 col-md-3">
        <h6>Relationship</h6>
        <?= $user->ice_relationship ?: "<i>Not specified</i>" ?>
    </div>
</div>
<div class="row">
    <div class="col-xl-3 col-lg-3 col-md-3">
        <h6>Alternate Email</h6>
        <?php if (isset($user->alt_email)): ?>
            <?= $this->Text->autoLinkEmails($user->alt_email) ?>
        <?php else: ?>
            <i>Not specified</i>
        <?php endif ?>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3">
        <h6>Cell Number</h6>
        <?php if (isset($user->cell)): ?>
            <?php $cellNumber = $this->Users->numberConvert($user->cell) ?>
            <?= '<a href="tel:'.$cellNumber.'">'.$user->cell.'</a>' ?>
        <?php else: ?>
            <i>Not specified</i>
        <?php endif ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-9">
        <h4>Educational Background</h4>
        <?php if ($degrees): ?>
            <?php foreach ($degrees as $degree): ?>
                <h6><?= $degree->name ?>: <em><?= $degree->location ?></em></h6>
                <p>
                    <?= $degree->type ?> in <?= $degree->major ?><br />
                    <?php $date = strtotime($degree->date) ?>
                    <?= $degree->completed == 1 ? 'Completed' : 'Attended' ?> <?= date('F, Y', $date) ?>
                </p>
            <?php endforeach ?>
        <?php else: ?>
            <?= $firstName ?> does not have any listed degrees or credentials.
        <?php endif ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h4>Awards</h4>
        <?php if ($awards): ?>
            <?php foreach ($awards as $award): ?>
                <h6><?= $award->name ?></h6>
                <p>
                    <?php $date = strtotime($award->awarded_on) ?>
                    Awarded on <?= date('F jS, Y', $date) ?><br />
                    Awarded by <?= $award->awarded_by ?><br />
                    <?= $award->description ?>
                </p>
            <?php endforeach ?>
        <?php else: ?>
            <?= $firstName ?> does not have any listed awards.
        <?php endif ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h4>Projects</h4>
        <?php if ($user->projects): ?>
            <?php foreach ($user->projects as $project): ?>
                <h6><?= $project->name ?></h6>
                <p>
                    <u>Project role:</u> <?= $project->_joinData->role ?><br />
                    <?= $project->organization ? 'With '.$project->organization.'<br />' : '' ?>
                    <?php
                        $descriptionArray = explode(' ', trim($project->description));
                        $wordCount = count($descriptionArray);
                        $halfCount = $wordCount / 2;
                    ?>
                    <?php for ($x = 0; $x <= $halfCount; $x++): ?>
                        <?= $descriptionArray[$x] ?>
                    <?php endfor ?>
                    ...
                    <em class="text-muted"><?= $this->Html->link('Read more', ['controller' => 'Projects', 'action' => 'view', $project->id]) ?></em>
                </p>
            <?php endforeach ?>
        <?php else: ?>
            <?= $firstName ?> does not have any listed projects.
        <?php endif ?>
    </div>
</div>
