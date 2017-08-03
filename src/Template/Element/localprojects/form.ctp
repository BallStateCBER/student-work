<?= $this->Form->create($localproject); ?>
<fieldset>
    <h1>
        <?= $titleForLayout; ?>
    </h1>
    <div class="row">
        <div class="col-lg-4">
            <?= $this->Form->control('name', ['class' => 'form-control', 'label' => 'Project name']); ?>
        </div>
        <div class="col-lg-4">
            <?= $this->Form->control('organization', ['class' => 'form-control']); ?>
        </div>
        <div class="col-lg-2">
            <?= $this->Form->control('grant_id', ['class' => 'form-control']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <label class="form-control-label">
                Project description
            </label>
            <?= $this->Form->textarea('description', ['class' => 'form-control']); ?>
        </div>
    </div>
    <?php if ($this->request->getParam('action') == 'edit'): ?>
        <h3>
            Edit contributors
            <?php if (!$localproject->users): ?>
                (<a data-toggle="collapse" href="#employee0" aria-expanded="false" aria-controls="employee0">+</a>)
            <?php endif; ?>
        </h3>
        <?php $y = 0; ?>
        <?php foreach ($localproject->users as $x => $user): ?>
            <div class="collapse show" id="employee<?= $x ?>">
                <div class="row">
                    <div class="col-lg-4">
                        <?= $this->Form->control('users.'.$x.'._joinData.user_id', [
                            'class' => 'form-control select-box',
                            'label' => 'Contributor Name',
                            'multiple' => false,
                            'type' => 'select'
                        ]); ?>
                    </div>
                    <div class="col-lg-4">
                        <?= $this->Form->control('users.'.$x.'._joinData.role', [
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
                    <div class="col-lg-4">
                        <?= $this->Form->control('users.'.$x.'._joinData.user_id', [
                            'class' => 'form-control select-box',
                            'label' => 'Contributor Name',
                            'empty' => true,
                            'multiple' => false,
                            'type' => 'select'
                        ]); ?>
                    </div>
                    <div class="col-lg-4">
                        <?= $this->Form->control('users.'.$x.'._joinData.role', [
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
            <?= $this->Html->link('Delete project?', ['controller' => 'Localprojects', 'action' => 'delete', $localproject->id], ['class' => 'text-danger']) ?>
        </small>
    </p>
<?php endif; ?>
