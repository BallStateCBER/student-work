<?php $this->extend('default_wrapper'); ?>

<div id="content_wrapper" class="col-md-12">
    <div id="content" class="clearfix col-md-11">
        <?= $this->Flash->render('flash'); ?>
        <?= $this->fetch('content'); ?>
    </div>
</div>
