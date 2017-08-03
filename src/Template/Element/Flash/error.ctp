<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert alert-danger col-lg-4" onclick="this.classList.add('hidden');"><?= $message ?></div>
