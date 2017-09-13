<?php if ($this->request->action == 'edit' && ($degree['user_id'] == $activeUser['id'] or $activeUser['role'] == 'Site Admin') or $this->request->action == 'add'): ?>
    <?= $this->Form->create($degree, [
        'templates' => [
            'select' => '<select class="form-control dates" name="{{name}}">{{content}}</select>'
        ]]) ?>
    <fieldset>
        <h1>
            <?= $titleForLayout ?>
        </h1>
        <div class="row">
            <div class="col-lg-3">
                <?= $this->Form->control('name', [
                    'class' => 'form-control',
                    'label' => 'Name of School'
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $this->Form->control('location', ['class' => 'form-control']) ?>
            </div>
            <div class="col-lg-3">
                <label>Type of Degree</label>
                <?= $this->Form->select('type', $degreeTypes) ?>
            </div>
            <div class="col-lg-2">
                <label>For employee</label>
                <?php if ($activeUser['role'] == 'Site Admin'): ?>
                    <?= $this->Form->text('user_id', [
                        'class' => 'form-control',
                        'value' => $activeUser['name']
                    ]) ?>
                <?php else: ?>
                    <?= $this->Form->text('user_id', [
                        'class' => 'form-control',
                        'disabled' => true,
                        'value' => $activeUser['name']
                    ]) ?>
                <?php endif ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <label class="form-control-label">
                    Date graduated <i>or</i> date of last semester attended
                </label>
                <div>
                    <?= $this->Form->control('date', [
                        'hour' => false,
                        'label' => false,
                        'maxYear' => date('Y'),
                        'meridian' => false,
                        'minute' => false,
                        'minYear' => '2000',
                        'value' => !empty($degree->date) ? $degree->date->format('Y-m-d') : date('Y-m-d')
                    ]);?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <?= $this->Form->control('major', ['class' => 'form-control']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 form-check">
                Have you graduated with this degree?
                <div class="col-lg-6">
                    <?= $this->Form->radio('completed',
                        [
                            ['value' => '1', 'text' => 'Yes'],
                            ['value' => '0', 'text' => 'No']
                        ],
                        ['label' => [
                            'class' => 'form-check'
                            ]
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="col-lg-6">
        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-secondary btn-md']) ?>
        <?= $this->Form->end() ?>
    </div>
    <?php if ($this->request->params['action'] == 'edit'): ?>
        <p class="float-right">
            <small>
                <?= $this->Html->link('Delete degree?', ['controller' => 'Degrees', 'action' => 'delete', $degree->id], ['class' => 'text-danger']) ?>
            </small>
        </p>
    <?php endif ?>
<?php endif ?>
