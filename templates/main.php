<?php
/**
 * @var HtmlView $this
 */

use PamutProba\App\View\HtmlView;
use PamutProba\Entity\Project;
use PamutProba\Entity\Status;
use PamutProba\Utility\Path;
use PamutProba\Utility\Url;

/**
 * @var Project[] $projects
 */
$projects = $this->data->get("projects");
/**
 * @var Status[] $statuses
 */
$statuses = $this->data->get("statuses");
$status = $this->data->get("status");
$currentPage = (int) $this->data->get("currentPage");
?>
<?php require Path::template("./components/header.php") ?>

<div class="container my-3 text-lighter">

    <?php require Path::template("./components/messages.php") ?>

    <div class="d-flex align-items-baseline justify-content-between">
        <?php if($statuses): ?>
        <form action="<?php echo Url::current() ?>" method="get" class="me-4 my-3 w-100 mw-200">
            <select name="status" class="form-select mb-3" onchange="this.form.submit()">
                <option value="">Státusz</option>
                <?php foreach ($statuses as $item): ?>
                    <option
                        value="<?php echo $item->key ?>"
                        <?php echo $status === $item->key ? ' selected="selected"' : "" ?>
                    >
                        <?php echo $item->name ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if ($currentPage > 1): ?>
            <input type="hidden" name="page" value="<?php echo $currentPage ?>">
            <?php endif; ?>
        </form>
        <?php endif; ?>

        <?php require Path::template("./components/pagination.php")?>
    </div>

    <div class="card">
        <ul class="list-group list-group-flush">
            <?php foreach ($projects as $item): ?>
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
                <form
                    action="<?php echo Url::base("/projekt/torol") ?>"
                    method="post"
                    class="d-inline"
                    onsubmit="return confirm('Biztosan törölni szeretnéd?')"
                >
                    <input type="hidden" name="id" value="<?php echo $item->id ?>">
                    <button type="submit" class="btn btn-danger">Törlés</button>
                </form>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php require Path::template("./components/pagination.php")?>

</div>

<?php require Path::template("./components/footer.php") ?>