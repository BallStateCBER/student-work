<?= $this->Form->create($job); ?>
<fieldset>
    <h1>
        <?= $titleForLayout; ?>
    </h1>
    <div class="row">
        <div class="col-lg-3">
            <?= $this->Form->control('job_title', [
                'class' => 'form-control'
            ]); ?>
        </div>
        <div class="col-lg-2">
            <label>For employee</label>
            <?= $this->Form->text('user_id', [
                'class' => 'form-control',
                'disabled' => true,
                'value' => $activeUser
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
