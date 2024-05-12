<?php
/**
 * @var HtmlView $this
 */

use PamutProba\App\View\HtmlView;
use PamutProba\Entity\Project;
use PamutProba\Utility\Path;
use PamutProba\Utility\Url;
?>
<?php require Path::template("./components/header.php") ?>

<div class="container my-3 text-lighter">
    <div class="card">
        <ul class="list-group list-group-flush">
            <?php foreach ($this->data->get("projects") as $item): ?><?php
                /**
                 * @var Project $item
                 */
            ?>
            <li class="list-group-item card-body">
                <div class="card-title d-flex justify-content-between">
                    <h5 class="fw-semibold mb-0"><?php echo $item->title ?></h5>
                    <small><?php echo $item->status->name ?></small>
                </div>
                <p class="card-text">
                    <small>
                        <?php echo $item->owner->name ?>
                        (<?php echo $item->owner->email ?>)
                    </small>
                </p>
                <a href="<?php echo Url::base("/projekt/?id=" . $item->id) ?>" class="btn btn-primary">Szerkesztés</a>
                <a href="<?php echo Url::base("/projekt/torol/?id=" . $item->id) ?>" class="btn btn-danger">Törlés</a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php require Path::template("./components/footer.php") ?>