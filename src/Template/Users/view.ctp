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
        <h2>Email</h2>
        <?= $this->Text->autoLinkEmails($user->email) ?>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4">
        <h2>Current Position</h2>
        <?= $user->position ?: "<i>Not specified</i>" ?>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4">
        <h2>Role</h2>
        <?= $user->is_admin ? 'Site Admin' : 'Student' ?>
    </div>
</div>
<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-3">
        <h2>Birthday</h2>
        <?= $user->birth_date ? date('F jS, Y', strtotime($user->birth_date)) : '<i>Not specifiied</i>' ?>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-3">
        <h2>Start Date</h2>
        <?= $user->start_date ? date('F jS, Y', strtotime($user->start_date)) : '<i>Not specified</i>' ?>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-3">
        <?php if ($user->end_date != null): ?>
            <h2>End Date</h2>
            <?php $date = strtotime($user->end_date) ?>
            <?= date('F jS, Y', $date) ?>
        <?php endif ?>
        <?php if ($user->end_date == null || $user->end_date >= date('Y-m-d')): ?>
            <h2>Currently</h2>
            <h2>employed</h2>
        <?php endif ?>
    </div>
</div>
<div class="row">
    <div class="col-xl-4 col-lg-3 col-md-3">
        <h2>Emergency Contact</h2>
        <?= $user->ice_name ?: "<i>Not specified</i>" ?>
    </div>
    <div class="col-xl-4 col-lg-3 col-md-3">
        <h2>Emergency Contact Number</h2>
        <?php if (isset($user->ice_phone)): ?>
            <?php $iceNumber = $this->Users->numberConvert($user->ice_phone) ?>
            <?= '<a href="tel:'.$iceNumber.'"><span class="sr-only">Call this number: </span>'.$user->ice_phone.'</a>' ?>
        <?php else: ?>
            <i>Not specified</i>
        <?php endif ?>
    </div>
    <div class="col-xl-4 col-lg-3 col-md-3">
        <h2>Relationship</h2>
        <?= $user->ice_relationship ?: "<i>Not specified</i>" ?>
    </div>
</div>
<div class="row">
    <div class="col-xl-3 col-lg-3 col-md-3">
        <h2>Alternate Email</h2>
        <?php if (isset($user->alt_email)): ?>
            <?= $this->Text->autoLinkEmails($user->alt_email) ?>
        <?php else: ?>
            <i>Not specified</i>
        <?php endif ?>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3">
        <h2>Cell Number</h2>
        <?php if (isset($user->cell)): ?>
            <?php $cellNumber = $this->Users->numberConvert($user->cell) ?>
            <?= '<a href="tel:'.$cellNumber.'"><span class="sr-only">Call this number: </span>'.$user->cell.'</a>' ?>
        <?php else: ?>
            <i>Not specified</i>
        <?php endif ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-9">
        <h2>Educational Background</h2>
        <?php if ($degrees): ?>
            <?php foreach ($degrees as $degree): ?>
                <h2><?= $degree->name ?>: <em><?= $degree->location ?></em></h2>
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
        <h2>Awards</h2>
        <?php if ($awards): ?>
            <?php foreach ($awards as $award): ?>
                <h3><?= $award->name ?></h3>
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
        <h2>Projects</h2>
        <?php if ($user->projects): ?>
            <?php foreach ($user->projects as $project): ?>
                <h3><?= $project->name ?></h3>
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
