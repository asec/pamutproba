<?php
/**
 * @var HtmlView $this
 */

use PamutProba\App\View\HtmlView;
use PamutProba\Utility\Development\Development;
use PamutProba\Utility\Path;

?>
<?php require Path::template("./components/header.php") ?>

<div class="container my-3">
    <h1>Hiba történt!</h1>
    <div class="bg-body-tertiary p-3">
        <?php echo $this->data->get("error")->getMessage() ?>.
        <?php Development::printTrace($this->data->get("error")->getTrace()); ?>
    </div>
</div>

<?php require Path::template("./components/footer.php") ?>