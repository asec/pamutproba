<?php
/**
 * @var HtmlView $this
 */

use PamutProba\Core\App\View\HtmlView;
use PamutProba\Core\Utility\Path;

?>
<?php require Path::template("./components/header.php") ?>

<div class="container my-3 text-lighter">

    <?php require Path::template("./components/messages.php") ?>

    <?php require Path::template("./main.partial.php") ?>

</div>

<?php require Path::template("./components/footer.php") ?>