<h1 class="page_title">
    <?= $titleForLayout; ?>
</h1>

<div class='form-group'>
    <p>
        If you have forgotten the password to your MuncieEvents.com account, you can enter your email address below (the same one that
        you used to register an account) and we'll send you an email with a link to reset your password.
        If you need assistance, please
        <?= $this->Html->link('contact us', [
            'controller' => 'Pages',
            'url' => 'contact'
        ]); ?>.
    </p>
    <?= $this->Form->create('User', ['url' => ['controller' => 'Users', 'action' => 'forgotPassword']]); ?>
    <div class="col-lg-6">
        <?php
            echo $this->Form->input('email', ['class' => 'form-control', 'label' => false]);
            echo $this->Form->button('Send password-resetting email', ['class' => 'form-control']);
            echo $this->Form->end();
        ?>
    </div>
    <br class="clear" />
</div>
