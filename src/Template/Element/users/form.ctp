<?php if ($activeUser['role'] == 'Site Admin'): ?>
    <?php
    $firstName = explode(' ', trim($user->name));
    $firstName = $firstName[0];
     ?>
    <?= $this->Form->create($user, [
        'templates' => [
            'file' => '<input type="file" name="{{name}}" class="form-control" {{attrs}} />',
            'select' => '<select class="form-control dates" name="{{name}}">{{content}}</select>'
        ],
        'type' => 'file'
        ]); ?>
    <fieldset>
        <?php if ($user->image) {
            echo $this->Html->image('users'.DS.$user->image, [
                'alt' => $user->name,
                'class' => 'img-account float-right'
            ]);
        } ?>
        <h1>
            <?= $titleForLayout; ?>
        </h1>
        <div class="row">
            <div class="col-lg-4">
                <?= $this->Form->control('name', ['class' => 'form-control']); ?>
            </div>
            <div class="col-lg-4">
                <?= $this->Form->control('email', ['class' => 'form-control']); ?>
            </div>
            <div class="col-lg-4">
                <?= $this->Form->control('position', ['class' => 'form-control']); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <label class="form-control-label">
                    Start date (for working at CBER)
                </label>
                <div>
                    <?= $this->Form->control('start_date', [
                        'hour' => false,
                        'label' => false,
                        'maxYear' => date('Y'),
                        'meridian' => false,
                        'minute' => false,
                        'minYear' => '1970',
                        'value' => !empty($user->start_date) ? $user->start_date->format('Y-m-d') : date('Y-m-d')
                    ]);?>
                </div>
            </div>
            <div class="col-lg-4">
                <label class="form-control-label">
                    End date (for working at CBER)
                </label>
                <div>
                    <?= $this->Form->control('end_date', [
                        'default' => null,
                        'empty' => 'n/a',
                        'hour' => false,
                        'label' => false,
                        'maxYear' => date('Y') + 10,
                        'meridian' => false,
                        'minute' => false,
                        'minYear' => '1970',
                        'value' => !empty($user->end_date) ? $user->end_date->format('Y-m-d') : null
                    ]);?>
                </div>
            </div>
            <div class="col-lg-4">
                <label class="form-control-label">
                    Date of birth
                </label>
                <div>
                    <?= $this->Form->control('birth_date', [
                        'hour' => false,
                        'format' => ('Y-m-d'),
                        'label' => false,
                        'maxYear' => date('Y') - 18,
                        'meridian' => false,
                        'minute' => false,
                        'minYear' => date('Y') - 90,
                        'value' => !empty($user->birth_date) ? $user->birth_date->format('Y-m-d') : date('1992-01-28')
                    ]);?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <?= $this->Form->control('ice_name', ['class' => 'form-control', 'label' => 'Emergency Contact']); ?>
            </div>
            <div class="col-lg-4">
                <?= $this->Form->control('ice_phone', ['class' => 'form-control', 'label' => "Emergency Contact's Number"]); ?>
            </div>
            <div class="col-lg-4">
                <?= $this->Form->control('ice_relationship', ['class' => 'form-control', 'label' => 'Relationship']); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <?= $this->Form->control('alt_email', ['class' => 'form-control', 'label' => 'Alternate Email']); ?>
            </div>
            <div class="col-lg-4">
                <?= $this->Form->control('cell', ['class' => 'form-control']); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <label class="form-control-label">
                    Profile picture
                </label>
                <?= $this->Form->input('image', [
                    'type' => 'file',
                    'label' => false
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <label class="form-control-label">
                    A short bio?
                </label>
                <?= $this->Form->textarea('bio', ['class' => 'form-control']); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 form-check">
                Are you currently employed here at CBER?
                <div class="col-lg-6">
                    <?= $this->Form->radio('is_current',
                        [
                            ['value' => '1', 'text' => 'Yes'],
                            ['value' => '0', 'text' => 'No']
                        ],
                        ['label' => [
                            'class' => 'form-check'
                            ]
                        ]
                    ); ?>
                </div>
            </div>
            <div class="col-lg-3">
                Do you have publications?
                <div class="col-lg-6">
                    <?= $this->Form->radio('has_publications',
                        [
                            ['value' => '1', 'text' => 'Yes'],
                            ['value' => '0', 'text' => 'No']
                        ],
                        ['label' => [
                            'class' => 'form-check'
                            ]
                        ]
                    ); ?>
                </div>
            </div>
            <div class="col-lg-3">
                Do you have websites?
                <div class="col-lg-6">
                    <?= $this->Form->radio('has_sites',
                        [
                            ['value' => '1', 'text' => 'Yes'],
                            ['value' => '0', 'text' => 'No']
                        ],
                        ['label' => [
                            'class' => 'form-check'
                            ]
                        ]
                    ); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-secondary btn-md']); ?>
            <?= $this->Form->end() ?>
        </div>
    </fieldset>
    <div class="row">
        <div class="col-sm-9">
            <h4>Your educational background (<?= $this->Html->link('+', ['controller' => 'Degrees', 'action' => 'add']) ?>)</h4>
            <?php if ($degrees): ?>
                <?php foreach ($degrees as $degree): ?>
                    <h6><?= $degree->name; ?>: <em><?= $degree->location; ?></em></h6>
                    <p class="exp-description">
                        <?= $degree->type; ?> in <?= $degree->major; ?><br />
                        <?php $date = strtotime($degree->date); ?>
                        <?= $degree->completed == 1 ? 'Completed' : 'Attended'; ?> <?= date('F, Y', $date); ?>
                        <em class="text-muted"><?= $this->Html->link('Edit degree', ['controller' => 'Degrees', 'action' => 'edit', $degree->id]) ?></em>
                    </p>
                <?php endforeach; ?>
            <?php else: ?>
                You do not have any listed degrees or credentials. Recent graduate? <em class="text-muted"><?= $this->Html->link('Add a degree!', ['controller' => 'Degrees', 'action' => 'add']) ?></em>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h4>Your awards (<?= $this->Html->link('+', ['controller' => 'Awards', 'action' => 'add']) ?>)</h4>
            <?php if ($awards): ?>
                <?php foreach ($awards as $award): ?>
                    <h6><?= $award->name; ?></h6>
                    <p class="exp-description">
                        <?php $date = strtotime($award->awarded_on); ?>
                        Awarded on <?= date('F jS, Y', $date); ?><br />
                        Awarded by <?= $award->awarded_by; ?><br />
                        <?= $award->description; ?>
                        <em class="text-muted"><?= $this->Html->link('Edit award', ['controller' => 'Awards', 'action' => 'edit', $award->id]) ?></em>
                    </p>
                <?php endforeach; ?>
            <?php else: ?>
                You do not have any listed awards. How about that participation trophy? <em class="text-muted"><?= $this->Html->link('Add an award!', ['controller' => 'Awards', 'action' => 'add']) ?></em>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h4>Your projects (<?= $this->Html->link('+', ['controller' => 'Projects', 'action' => 'add']) ?>)</h4>
            <?php if ($user->projects): ?>
                <?php foreach ($user->projects as $project): ?>
                    <h6><?= $project->name; ?></h6>
                    <p class="exp-description">
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
                        <em class="text-muted"><?= $this->Html->link('Edit project', ['controller' => 'Projects', 'action' => 'edit', $project->id]) ?></em>
                    </p>
                <?php endforeach; ?>
            <?php else: ?>
                You do not have any listed projects. Don't be scared to show off! <em class="text-muted"><?= $this->Html->link('Add a project!', ['controller' => 'Projects', 'action' => 'add']) ?></em>
            <?php endif; ?>
        </div>
    </div>
    <p class="float-right">
        <small>Your Ball State ID is: <?= $user->id ?></small>
        <br />
        <small>
            <?= $this->Html->link('Delete your account?', ['controller' => 'Users', 'action' => 'delete', $user->id], ['class' => 'text-danger']) ?>
        </small>
    </p>
<?php endif; ?>
