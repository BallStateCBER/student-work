<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert alert-success col-lg-4" onclick="this.classList.add('hidden')"><?= $message ?></div>
