<?php

$loggedIn = $this->request->session()->read('Auth.User');
$name = $this->request->session()->read('Auth.User.name');
$name = explode(' ', trim($name));
$name = $name[0];

?>
<nav class="navbar navbar-toggleable-md navbar-light">
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <?= $this->Html->link('Why Are We Here?',
    ['controller' => 'Pages', 'action' => 'home'],
    ['class'=>'navbar-brand accent-font']);
  ?>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <?php if ($loggedIn): ?>
            <?= $this->Html->link('Edit Your Account', ['controller' => 'Users', 'action' => 'account'], ['class'=>'nav-link']); ?>
        <?php endif; ?>
      </li>
      <li class="nav-item">
        <?php if ($loggedIn): ?>
            <?= $this->Html->link('Log out', ['controller' => 'Users', 'action' => 'logout'], ['class'=>'nav-link']); ?>
        <?php else: ?>
            <?= $this->Html->link('Log in', ['controller' => 'Users', 'action' => 'login'], ['class'=>'nav-link']); ?>
        <?php endif; ?>
      </li>
      <li class="nav-item">
        <?= $this->Html->link('Student Reports', ['controller' => 'reports', 'action' => 'index'], ['class'=>'nav-link']); ?>
      </li>
      <li class="nav-item">
        <?= $this->Html->link('Staff', ['controller' => 'Users', 'action' => 'index'], ['class'=>'nav-link']); ?>
      </li>
      <li class="nav-item">
        <?= $this->Html->link('Projects', ['controller' => 'Projects', 'action' => 'index'], ['class'=>'nav-link']); ?>
      </li>
    </ul>
    <!--form class="form-inline my-2 my-lg-0">
        <!--?php
            $formTemplate = [
                'inputContainer' => '{{content}}',
                'submitContainer' => '{{content}}'
            ];
            $this->Form->setTemplates($formTemplate);
        ?-->
        <!--?= $this->Form->create('User', [
                'url' => array_merge(['action' => 'search'], $this->request->params['pass'])
            ]);
        ?-->
      <!--?= $this->Form->input('filter', [
              'label' => false,
              'class' => 'form-control mr-sm-2',
              'placeholder' => 'Search'
          ]);
      ?-->
      <!--?= $this->Form->submit('Search', ['class' => "btn btn-secondary my-2 my-sm-0"]) ?>
      <!--?= $this->Form->end(); ?>
  </form-->
  </div>
</nav>
