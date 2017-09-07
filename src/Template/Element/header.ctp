<?php

$user = $this->request->session()->read('Auth.User');
$name = explode(' ', trim($user['name']));
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
        <?php if ($user): ?>
            <?= $this->Html->link('Log out', ['controller' => 'Users', 'action' => 'logout'], ['class'=>'nav-link']); ?>
        </li>
        <?php if ($user['role'] == 'Site Admin'): ?>
            <li class="nav-item">
                <?= $this->Html->link('Funding', ['controller' => 'Funds', 'action' => 'index'], ['class'=>'nav-link']); ?>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Projects</a>
                <div class="dropdown-menu">
                    <?= $this->Html->link('Projects Index', ['controller' => 'Projects', 'action' => 'index'], ['class'=>'dropdown-item']); ?>
                    <?= $this->Html->link('Add a Project', ['controller' => 'Projects', 'action' => 'add'], ['class'=>'dropdown-item']); ?>
                </div>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <?= $this->Html->link('Projects', ['controller' => 'Projects', 'action' => 'index'], ['class'=>'nav-link']); ?>
            </li>
        <?php endif; ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Student Reports</a>
            <div class="dropdown-menu">
                <?= $this->Html->link('Reports Index', ['controller' => 'Reports', 'action' => 'index'], ['class'=>'dropdown-item']); ?>
                <?= $this->Html->link('Add a Report', ['controller' => 'Reports', 'action' => 'add'], ['class'=>'dropdown-item']); ?>
            </div>
        </li>
        <?php if ($this->request->session()->read('Auth.User.role') == 'Site Admin'): ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Students & Staff</a>
            <div class="dropdown-menu">
                <?= $this->Html->link('Users Index', ['controller' => 'Users', 'action' => 'index'], ['class'=>'dropdown-item']); ?>
                <?= $this->Html->link('Add a User', ['controller' => 'Users', 'action' => 'register'], ['class'=>'dropdown-item']); ?>

            </div>
        </li>
        <?php else: ?>
            <li class="nav-item">
                <?= $this->Html->link('Students & Staff', ['controller' => 'Users', 'action' => 'index'], ['class'=>'nav-link']); ?>
            </li>
        <?php endif; ?>
        <li class="nav-item">
            <?php if ($user): ?>
                <u>
                    <?= $this->Html->link("$name: Edit Your Account", ['controller' => 'Users', 'action' => 'account'], ['class'=>'nav-link']); ?>
                </u>
            <?php endif; ?>
        </li>
        <?php else: ?>
            <?= $this->Html->link('Log in', ['controller' => 'Users', 'action' => 'login'], ['class'=>'nav-link']); ?>
        </li>
        <?php endif; ?>
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
