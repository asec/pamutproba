<?php
/**
 * @var HtmlView $this
 */

use PamutProba\Core\App\View\HtmlView;
use PamutProba\Core\Utility\Path;
use PamutProba\Core\Utility\Url;
use PamutProba\Entity\Project;
use PamutProba\Entity\Status;

/**
 * @var Project[] $projectList
 */
$projectList = $this->data->get("projectList");
/**
 * @var Status[] $statusList
 */
$statusList = $this->data->get("statusList");
$statusFilter = $this->data->get("statusFilter");
$currentPage = (int) $this->data->get("currentPage");
?>
<div id="ajax-refresh-target">
    <div class="d-flex align-items-baseline justify-content-between overflow-x-hidden">
        <?php if($statusList): ?>
            <form action="<?php echo Url::current() ?>" method="get" class="me-4 my-3 w-100 mw-200">
                <select name="status" class="form-select mb-3" onchange="this.form.submit()">
                    <option value="">Státusz</option>
                    <?php foreach ($statusList as $item): ?>
                        <option
                            value="<?php echo $item->key ?>"
                            <?php echo $statusFilter === $item->key ? ' selected="selected"' : "" ?>
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
            <?php foreach ($projectList as $item): ?>
                <li class="list-group-item card-body" data-pamut-disable="delete">
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
                    <a href="<?php echo Url::base("/projekt/?id=" . $item->id) ?>" class="btn btn-primary">
                        Szerkesztés
                    </a>
                    <form
                        action="<?php echo Url::base("/projekt/torol") ?>"
                        method="post"
                        class="d-inline"
                        onsubmit="return confirm(this.getAttribute('data-confirm'))"
                        data-confirm="Biztosan törölni szeretnéd?"
                    >
                        <input type="hidden" name="id" value="<?php echo $item->id ?>">
                        <button type="submit" class="btn btn-danger" data-pamut-action="delete">Törlés</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="overflow-x-hidden">
        <?php require Path::template("./components/pagination.php")?>
    </div>

</div>
