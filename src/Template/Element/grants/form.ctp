<?= $this->Form->create($grant); ?>
<fieldset>
    <h1>
        <?= $titleForLayout; ?>
    </h1>
    <div class="row">
        <div class="col-lg-3">
            <?= $this->Form->control('name', [
                'class' => 'form-control'
            ]); ?>
        </div>
        <div class="col-lg-3">
            <?= $this->Form->control('organization', [
                'class' => 'form-control'
            ]); ?>
        </div>
        <div class="col-lg-3">
            <?= $this->Form->control('amount', [
                'class' => 'form-control'
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <label class="form-control-label">
                Job description
            </label>
            <?= $this->Form->textarea('description', ['class' => 'form-control']); ?>
        </div>
    </div>
</fieldset>
<div class="col-lg-6">
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-secondary btn-md']); ?>
    <?= $this->Form->end() ?>
</div>
<?php if ($this->request->params['action'] == 'edit'): ?>
    <p class="float-right">
        <small>
            <?= $this->Html->link('Delete work experience?', ['controller' => 'Jobs', 'action' => 'delete', $job->id], ['class' => 'text-danger']) ?>
        </small>
    </p>
<?php endif; ?>
