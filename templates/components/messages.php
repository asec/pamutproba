<?php
/**
 * @var HtmlView $this
 */

use PamutProba\Core\App\View\HtmlView;

$success = $this->data->get("message-success");
$error = $this->data->get("message-error");

?>
<div id="messages">
    <?php if ($success): ?>
    <div class="alert alert-success" role="alert"><?php echo $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="alert alert-danger" role="alert"><?php echo $error ?></div>
    <?php endif; ?>
</div>
