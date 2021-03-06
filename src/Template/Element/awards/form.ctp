<?= $this->Form->create($award, [
    'templates' => [
        'select' => '<select class="form-control dates" name="{{name}}">{{content}}</select>'
    ]]) ?>
<fieldset>
    <h1>
        <?= $titleForLayout ?>
    </h1>
    <div class="row">
        <div class="col-lg-3">
            <?= $this->Form->control('name', ['class' => 'form-control']) ?>
        </div>
        <div class="col-lg-3">
            <?= $this->Form->control('awarded_by', ['class' => 'form-control']) ?>
        </div>
        <div class="col-lg-2">
            <label>For employee</label>
            <?php
                $val = $activeUser['name'] ? $activeUser['name'] : $activeUser['id'];
            ?>
            <?= $this->Form->text('user_id', [
                'class' => 'form-control',
                'value' => $val
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <label class="form-control-label">
                Awarded on
            </label>
            <div>
                <?= $this->Form->control('awarded_on', [
                    'hour' => false,
                    'label' => false,
                    'maxYear' => date('Y'),
                    'meridian' => false,
                    'minute' => false,
                    'minYear' => '2000',
                    'value' => !empty($award->awarded_on) ? date('Y-m-d', strtotime($award->awarded_on)) : date('Y-m-d')
                ]);?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <label class="form-control-label">
                Award description
            </label>
            <?= $this->Form->textarea('description', ['class' => 'form-control']) ?>
        </div>
    </div>
</fieldset>
<div class="col-lg-6 center-button">
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-secondary btn-md']) ?>
    <?= $this->Form->end() ?>
</div>
<?php if ($this->request->params['action'] == 'edit'): ?>
    <p class="float-right">
        <small>
            <?= $this->Html->link('Delete award?', ['controller' => 'Awards', 'action' => 'delete', $award->id], ['class' => 'text-danger']) ?>
        </small>
    </p>
<?php endif ?>