<?= $this->Form->create($site, [
        'templates' => [
        'file' => '<input type="file" name="{{name}}" class="form-control" {{attrs}} />',
        'select' => '<select class="form-control dates" name="{{name}}">{{content}}</select>'
    ],
    'type' => 'file'
    ]); ?>
<fieldset>
    <?php if ($site->image) {
        echo $this->Html->image('sites'.DS.$site->image, [
            'alt' => $site->name,
            'class' => 'img-account float-right'
        ]);
    } ?>
    <h1>
        <?= $titleForLayout; ?>
    </h1>
    <div class="row">
        <div class="col-lg-4">
            <?= $this->Form->control('site_name', ['class' => 'form-control']); ?>
        </div>
        <div class="col-lg-4">
            <?= $this->Form->control('url', ['class' => 'form-control']); ?>
        </div>
        <div class="col-lg-2">
            <label class="form-control-label">
                Grant (if applicable)
            </label>
            <?= $this->Form->control('grant_id', [
                'class' => 'form-control',
                'default' => 'null',
                'empty' => 'n/a',
                'label' => false
            ]); ?>
            <small>
                Don't see yours listed? <?= $this->Html->link('Add a new grant!', ['controller' => 'Grants', 'action' => 'add'], ['class' => 'text-danger']) ?>
            </small>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <label class="form-control-label">
                Date live <i>or</i> date work on site began
            </label>
            <div>
                <?= $this->Form->control('date_live', [
                    'hour' => false,
                    'label' => false,
                    'maxYear' => date('Y'),
                    'meridian' => false,
                    'minute' => false,
                    'minYear' => '2000',
                    'value' => !empty($site->date_live) ? $site->date_live->format('Y-m-d') : date('Y-m-d')
                ]);?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <label class="form-control-label">
                Screenshot of website
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
                Site description
            </label>
            <?= $this->Form->textarea('description', ['class' => 'form-control']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 form-check">
            Is this site currently in progress?
            <div class="col-lg-6">
                <?= $this->Form->radio('in_progress',
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
    <?php if ($this->request->getParam('action') == 'edit'): ?>
        <h3>
            Edit contributors
            <?php if (!$site->users): ?>
                (<a data-toggle="collapse" href="#employee0" aria-expanded="false" aria-controls="employee0">+</a>)
            <?php endif; ?>
        </h3>
        <?php $y = 0; ?>
        <?php foreach ($site->users as $x => $user): ?>
            <div class="collapse show" id="employee<?= $x ?>">
                <div class="row">
                    <div class="col-lg-2">
                        <label class="form-control-label">
                            Contributor Name
                        </label>
                        <?= $this->Form->control('users.'.$x.'._joinData.user_id', [
                            'class' => 'form-control select-box',
                            'label' => false,
                            'multiple' => false,
                            'type' => 'select'
                        ]); ?>
                    </div>
                    <div class="col-lg-4">
                        <?= $this->Form->control('users.'.$x.'._joinData.employee_role', [
                            'class' => 'form-control',
                            'label' => 'Contributor Role'
                        ]); ?>
                    </div>
                    <div class="col-lg-4">
                        <br />
                        <label>Delete this Role?</label>
                        <?= $this->Form->checkbox('users.'.$x.'.delete') ?><br />
                        <a class="text-muted" data-toggle="collapse" href="#employee<?= $x + 1 ?>" aria-expanded="false" aria-controls="employee<?= $x + 1 ?>">Add Another?</a>
                    </div>
                </div>
            </div>
            <?php $x = $x; ?>
        <?php endforeach; ?>
        <?php
            if (!isset($x)) {
                $x = 0;
            }
            $z = $x + 10;
        ?>
        <?php for ($x; $x <= $z; $x++): ?>
            <div class="collapse" id="employee<?= $x ?>">
                <div class="row">
                    <div class="col-lg-2">
                        <label class="form-control-label">
                            Contributor Name
                        </label>
                        <?= $this->Form->control('users.'.$x.'._joinData.user_id', [
                            'class' => 'form-control select-box',
                            'label' => false,
                            'empty' => true,
                            'multiple' => false,
                            'type' => 'select'
                        ]); ?>
                    </div>
                    <div class="col-lg-4">
                        <?= $this->Form->control('users.'.$x.'._joinData.employee_role', [
                            'class' => 'form-control',
                            'label' => 'Contributor Role'
                        ]); ?>
                    </div>
                    <div class="col-lg-4">
                        <br />
                        <label>Delete This Role?</label>
                        <?= $this->Form->checkbox('users.'.$x.'._joinData.delete'); ?><br />
                        <a class="text-muted" data-toggle="collapse" href="#employee<?= $x + 1 ?>" aria-expanded="false" aria-controls="employee<?= $x + 1 ?>">Add Another?</a>
                    </div>
                </div>
            </div>
        <?php endfor; ?>
    <?php endif; ?>
</fieldset>
<div class="col-lg-6">
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-secondary btn-md']); ?>
    <?= $this->Form->end() ?>
</div>
<?php if ($this->request->params['action'] == 'edit'): ?>
    <p class="float-right">
        <small>
            <?= $this->Html->link('Delete site?', ['controller' => 'Sites', 'action' => 'delete', $site->id], ['class' => 'text-danger']) ?>
        </small>
    </p>
<?php endif; ?>
