<?= $this->Form->create($fund) ?>
<fieldset>
    <h1>
        <?= $titleForLayout ?>
    </h1>
    <div class="row">
        <div class="col-lg-3">
            <label class="form-control-label">
                Fund number
            </label>
            <?= $this->Form->control('name', [
                'class' => 'form-control',
                'label' => false
            ]) ?>
        </div>
        <div class="col-lg-3">
            <?= $this->Form->control('organization', [
                'class' => 'form-control'
            ]) ?>
        </div>
        <div class="col-lg-3">
            <?= $this->Form->control('amount', [
                'class' => 'form-control'
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <label class="form-control-label">
                Funding details
            </label>
            <?= $this->CKEditor->loadJs() ?>
            <?= $this->Form->textarea('funding_details', ['class' => 'form-control']) ?>
            <?= $this->CKEditor->replace('funding_details') ?>
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
            <?= $this->Html->link('Delete fund?', ['controller' => 'Funds', 'action' => 'delete', $fund->id], ['class' => 'text-danger']) ?>
        </small>
    </p>
<?php endif ?>
