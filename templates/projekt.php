<?php
/**
 * @var HtmlView $this
 * @var Project $project
 */

use PamutProba\App\View\HtmlView;
use PamutProba\Entity\Project;
use PamutProba\Utility\Path;
use PamutProba\Utility\Url;

$project = $this->data->has("project") ? $this->data->get("project") : null;
?>
<?php require Path::template("./components/header.php") ?>

<div class="container my-3">

    <?php require Path::template("./components/messages.php") ?>

    <form method="post" action="<?php echo Url::current() ?>">
        <div class="mb-3">
            <label for="title" class="form-label">Cím</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo $project->title ?? "" ?>">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Leírás</label>
            <textarea class="form-control" id="description" name="description" rows="2"><?php echo $project->description ?? "" ?></textarea>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Státusz</label>
            <div class="d-block">
                <select aria-label="Státusz választása" id="status" name="status">
                    <?php foreach ($this->data->get("statuses") as $item): ?>
                    <option value="<?php echo $item->id ?>"<?php echo $project?->status->id === $item->id ? ' selected="selected"' : '' ?>><?php echo $item->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label for="owner-name" class="form-label">Kapcsolattartó neve</label>
            <input type="text" class="form-control" id="owner-name" name="owner_name" value="<?php echo $project->owner->name ?? "" ?>">
        </div>
        <div class="mb-3">
            <label for="owner-email" class="form-label">Kapcsolattartó e-mail címe</label>
            <input type="email" class="form-control" id="owner-email" name="owner_email" value="<?php echo $project->owner->email ?? "" ?>">
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Mentés</button>
        </div>
        <?php if ($project): ?>
        <input type="hidden" name="id" value="<?php echo $project->id ?>">
        <?php endif ?>
    </form>
</div>

<?php require Path::template("./components/footer.php") ?>