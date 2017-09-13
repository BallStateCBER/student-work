<div class="row">
    <div class="col-lg-8 col-xs-12">
        <?= $this->Form->create('User', ['url' => ['controller' => 'Users', 'action' => 'login']]) ?>
        <div class='form-group col-lg-8 col-xs-12'>
            <?= $this->Form->control('email', ['class' => 'form-control']) ?>
        </div>
        <div class='form-group col-lg-8 col-xs-12'>
            <?= $this->Form->control('password', ['class' => 'form-control']) ?>
            <?= $this->Html->link(__('Forgot password?'), ['controller' => 'Users', 'action' => 'forgotPassword'], ['class' => 'nav-link']) ?>
            <?= $this->Html->link(__('New here? Register!'), ['controller' => 'Users', 'action' => 'register'], ['class' => 'nav-link']) ?>
            <div class="float-right">
                <?= $this->Form->input('remember_me', [
                        'type' => 'checkbox',
                        'label' => [
                            'text' => ' Remember me',
                            'style' => 'display: inline;'
                        ],
                        'checked' => true
                    ]);
                ?>
                <?= $this->Form->button(__('Login'), ['class' => 'btn btn-secondary btn-md float-right']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
    <div class='col-lg-4 col-xs-12 self-end text-center'>
        <?= $this->Html->image('cber-logo.png', [
            'class' => 'img-index'
        ]) ?>
    </div>
</div>
