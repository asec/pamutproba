<?php
/**
 * @var \PamutProba\Core\App\View\HtmlView $this
 */

use PamutProba\Core\App\Client\Client;
use PamutProba\Core\App\View\HtmlView;

$success = Client::session()->getFlashed("message-success");
$error = Client::session()->getFlashed("message-error");

?>
<div id="messages">
    <?php if ($success): ?>
    <div class="alert alert-success" role="alert"><?php echo $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="alert alert-danger" role="alert"><?php echo $error ?></div>
    <?php endif; ?>
</div>
