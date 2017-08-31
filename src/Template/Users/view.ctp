<?php
$firstName = explode(' ', trim($user->name));
$firstName = $firstName[0];
?>
<?php if ($user->image): ?>
    <?= $this->Html->image('users'.DS.$user->image, [
        'alt' => $user->name,
        'class' => 'img-account float-right'
    ]); ?>
<?php else: ?>
    <?= $this->Html->image('cber-staff.jpg', [
        'alt' => $user->name,
        'class' => 'img-account float-right'
    ]); ?>
<?php endif; ?>
<h1>
    <?= $user->name; ?>
</h1>
<div class="row">
    <div class="col-lg-8">
        <?php if ($user->bio): ?>
            <p>
                <?= $user->bio ?>
            </p>
        <?php else: ?>
            <p>
                <?= $firstName ?> has not written a bio yet. Are you <?= $firstName ?>? You should write your bio. No, like, how are we supposed to know you're a real person if you don't talk about it on the Internet?
            </p>
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <h6>Email</h6>
        <?= $this->Text->autoLinkEmails($user->email); ?>
    </div>
    <div class="col-lg-4">
        <h6>Current Position</h6>
        <?= $user->position; ?>
    </div>
    <div class="col-lg-4">
        <h6>Role</h6>
        <?= $user->role; ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <h6>Birthday</h6>
        <?php $date = strtotime($user->birth_date); ?>
        <?= date('F jS, Y', $date); ?>
    </div>
    <div class="col-lg-4">
        <h6>Start Date</h6>
        <?php $date = strtotime($user->start_date); ?>
        <?= date('F jS, Y', $date); ?>
    </div>
    <div class="col-lg-4">
        <?php if ($user->end_date): ?>
            <h6>End Date</h6>
            <?php $date = strtotime($user->end_date); ?>
            <?= date('F jS, Y', $date); ?>
        <?php elseif ($user->is_current == 1): ?>
            <h6>Currently</h6>
            <h6>employed</h6>
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-3">
        <h6>Emergency Contact</h6>
        <?= $user->ice_name; ?>
    </div>
    <div class="col-lg-3">
        <h6>Emergency Contact Number</h6>
        <?php $iceNumber = $this->Users->numberConvert($user->ice_phone); ?>
        <?= '<a href="tel:'.$iceNumber.'">'.$user->ice_phone.'</a>'; ?>
    </div>
    <div class="col-lg-3">
        <h6>Relationship</h6>
        <?= $user->ice_relationship; ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <h6>Alternate Email</h6>
        <?= $this->Text->autoLinkEmails($user->alt_email); ?>
    </div>
    <div class="col-lg-4">
        <h6>Cell Number</h6>
        <?php $cellNumber = $this->Users->numberConvert($user->cell); ?>
        <?= '<a href="tel:'.$cellNumber.'">'.$user->cell.'</a>'; ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-9">
        <h4>Educational Background</h4>
        <?php if ($degrees): ?>
            <?php foreach ($degrees as $degree): ?>
                <h6><?= $degree->name; ?>: <em><?= $degree->location; ?></em></h6>
                <p>
                    <?= $degree->type; ?> in <?= $degree->major; ?><br />
                    <?php $date = strtotime($degree->date); ?>
                    <?= $degree->completed == 1 ? 'Completed' : 'Attended'; ?> <?= date('F, Y', $date); ?>
                </p>
            <?php endforeach; ?>
        <?php else: ?>
            <?= $firstName ?> does not have any listed degrees or credentials.
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h4>Awards</h4>
        <?php if ($awards): ?>
            <?php foreach ($awards as $award): ?>
                <h6><?= $award->name; ?></h6>
                <p>
                    <?php $date = strtotime($award->awarded_on); ?>
                    Awarded on <?= date('F jS, Y', $date); ?><br />
                    Awarded by <?= $award->awarded_by; ?><br />
                    <?= $award->description; ?>
                </p>
            <?php endforeach; ?>
        <?php else: ?>
            <?= $firstName ?> does not have any listed awards.
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h4>Projects</h4>
        <?php if ($user->projects): ?>
            <?php foreach ($user->projects as $project): ?>
                <h6><?= $project->name; ?></h6>
                <p>
                    <u>Project role:</u> <?= $project->_joinData->role; ?><br />
                    <?= $project->organization ? 'With '.$project->organization.'<br />' : ''; ?>
                    <?php
                        $descriptionArray = explode(' ', trim($project->description));
                        $wordCount = count($descriptionArray);
                        $halfCount = $wordCount / 2;
                    ?>
                    <?php for ($x = 0; $x <= $halfCount; $x++): ?>
                        <?= $descriptionArray[$x]; ?>
                    <?php endfor; ?>
                    ...
                    <em class="text-muted"><?= $this->Html->link('Read more', ['controller' => 'Projects', 'action' => 'view', $project->id]) ?></em>
                </p>
            <?php endforeach; ?>
        <?php else: ?>
            <?= $firstName; ?> does not have any listed projects.
        <?php endif; ?>
    </div>
</div>
