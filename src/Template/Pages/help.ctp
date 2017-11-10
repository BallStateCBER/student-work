<?php if ($activeUser['is_admin']): ?>
    <?= $this->element('help/admin'); ?>
<?php else: ?>
    <?= $this->element('help/student'); ?>
<?php endif ?>
