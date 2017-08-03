<?php $this->extend('default_wrapper'); ?>
<div id="content_wrapper" class="col-sm-12 col-lg-9">
    <div id="content" class="clearfix">
        <?= $this->fetch('content'); ?>
    </div>
</div>
<?= $this->element('sidebar'); ?>
