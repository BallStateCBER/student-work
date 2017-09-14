<?php if (isset($activeUser) && $activeUser['admin'] == 1): ?>
    <?php
    $this->Form->setTemplates([
        'select' => '<select class="form-control">{{content}}</select>'
    ]);
     ?>
    <?= $this->Form->create($user) ?>
    <h1 class="page_title">
        <?= $titleForLayout ?>
    </h1>
    <fieldset>
        <div class="col-lg-4">
            <label>
                Ball State ID number
            </label>
            <?= $this->Form->input('id', [
                'class' => 'form-control',
                'label' => false,
                'type' => 'number'
            ]) ?>
            <i class="text-muted">
                Pssst, don't know where to get your BSU ID number? Go to Ball State's
                <?= $this->Html->link('Self-Service Banner', "https://prodssb.bsu.edu") ?>
                and log in!
            </i>
        </div>
        <div class="col-lg-4">
            <?= $this->Form->control('email', ['class' => 'form-control']) ?>
        </div>
        <div class="col-lg-4">
            <?= $this->Form->control('password', ['class' => 'form-control']) ?>
        </div>
        <div class="col-lg-2">
            <label for="admin">
                Site Role
            </label>
            <?= $this->Form->control('admin', [
                'label' => false,
                'options' => [
                    1 => 'Site Admin',
                    0 => 'Student'
                ],
                'type' => 'radio'
            ]) ?>
            <br />
        </div>
    </fieldset>
    <div class="col-lg-6">
        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-secondary btn-md']) ?>
    </div>
    <?= $this->Form->end() ?>
<?php endif ?>
