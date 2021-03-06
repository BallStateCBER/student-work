<?= $this->Form->create($project, [
    'templates' => [
        'file' => '<input type="file" name="{{name}}" class="form-control" {{attrs}} />',
    ],
    'type' => 'file'
]) ?>
<fieldset>
<?php if ($project->image) {
    echo $this->Html->image('projects'.DS.$project->image, [
        'alt' => $project->name,
        'class' => 'img-account float-right'
    ]);
} ?>
    <h1>
        <?= $titleForLayout ?>
    </h1>
    <div class="row">
        <div class="col-lg-3">
            <?= $this->Form->control('name', ['class' => 'form-control', 'label' => 'Project name']) ?>
        </div>
        <div class="col-lg-3">
            <?= $this->Form->control('organization', ['class' => 'form-control']) ?>
        </div>
        <div class="col-lg-3">
            <label class="form-control-label" for="fund-id">
                Fund number (if applicable)
            </label>
            <?= $this->Form->control('fund_id', [
                'class' => 'form-control',
                'default' => 'null',
                'empty' => 'n/a',
                'label' => false
            ]) ?>
            <small>
                Don't see yours listed? <?= $this->Html->link('Add a new fund!', ['controller' => 'Funds', 'action' => 'add'], ['class' => 'text-danger']) ?>
            </small>
        </div>
        <div class="col-lg-3">
            <label class="form-control-label" for="grant-name">
                Grant name (if applicable)
            </label>
            <?= $this->Form->control('grant_name', [
                'class' => 'form-control',
                'label' => false
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <label class="form-control-label" for="image">
                Project image
            </label>
            <?= $this->Form->input('image', [
                'type' => 'file',
                'label' => false,
                'required' => false
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <label class="form-control-label" for="description">
                Project description
            </label>
            <?= $this->CKEditor->loadJs() ?>
            <?= $this->Form->textarea('description', ['class' => 'form-control']) ?>
            <?= $this->CKEditor->replace('description') ?>
        </div>
        <div class="col-lg-6">
            <label class="form-control-label" for="funding-details">
                Funding Details
            </label>
            <?= $this->CKEditor->loadJs() ?>
            <?= $this->Form->textarea('funding_details', ['class' => 'form-control']) ?>
            <?= $this->CKEditor->replace('funding_details') ?>
        </div>
    </div>
    <?php if ($this->request->getParam('action') == 'edit'): ?>
        <h2>
            Edit contributors
            <?php if (!$project->users): ?>
                (<a data-toggle="collapse" href="#employee0" aria-expanded="false" aria-controls="employee0">+ <span class="sr-only"> Add a Contributor</span></a>)
            <?php endif ?>
            (<a tabindex="0" id="help-popover" data-toggle="popover" data-trigger="focus" title="Need help?" data-content="If you need to change an employee's role in a project, you have to delete their old role and re-add them."><span class="sr-only">Do you need help</span>?</a>)
        </h2>
        <?php $y = 0 ?>
        <?php foreach ($project->users as $x => $user): ?>
            <div class="collapse show" id="employee<?= $x ?>">
                <div class="row">
                    <div class="col-lg-4">
                        <?= $this->Form->control('users.'.$x.'._joinData.user_id', [
                            'class' => 'form-control select-box',
                            'label' => 'Contributor Name',
                            'multiple' => false,
                        #    'readonly' => isset($user),
                            'type' => 'select'
                        ]) ?>
                    </div>
                    <div class="col-lg-4">
                        <?= $this->Form->control('users.'.$x.'._joinData.role', [
                            'class' => 'form-control',
                            'label' => 'Contributor Role',
                        #    'readonly' => isset($user)
                        ]) ?>
                    </div>
                    <div class="col-lg-4">
                        <br />
                        <label for="users.<?= $x ?>.delete">Delete this Role?</label>
                        <?= $this->Form->checkbox('users.'.$x.'.delete', ['id' => 'users.'.$x.'.delete']) ?><br />
                        <a class="text-muted" data-toggle="collapse" href="#employee<?= $x + 1 ?>" aria-expanded="false" aria-controls="employee<?= $x + 1 ?>">Add Another?</a>
                    </div>
                </div>
            </div>
            <?php $x = $x ?>
        <?php endforeach ?>
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
                        ]) ?>
                    </div>
                    <div class="col-lg-4">
                        <?= $this->Form->control('users.'.$x.'._joinData.role', [
                            'class' => 'form-control',
                            'label' => 'Contributor Role'
                        ]) ?>
                    </div>
                    <div class="col-lg-4">
                        <br />
                        <label>Delete This Role?</label>
                        <?= $this->Form->checkbox('users.'.$x.'._joinData.delete') ?><br />
                        <a class="text-muted" data-toggle="collapse" href="#employee<?= $x + 1 ?>" aria-expanded="false" aria-controls="employee<?= $x + 1 ?>">Add Another?</a>
                    </div>
                </div>
            </div>
        <?php endfor ?>
    <?php endif ?>
</fieldset>
<div class="col-lg-6 center-button">
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-secondary btn-md']) ?>
    <?= $this->Form->end() ?>
</div>
<?php if ($this->request->params['action'] == 'edit' && empty($project->reports)): ?>
    <p class="float-right">
        <small>
            <?= $this->Html->link('Delete project?', ['controller' => 'Projects', 'action' => 'delete', $project->id], ['class' => 'text-danger']) ?>
        </small>
    </p>
<?php endif ?>
<script>
    $('#help-popover').popover({
        trigger: 'focus'
    })
</script>
