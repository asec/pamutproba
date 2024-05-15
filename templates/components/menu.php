<?php
/**
 * @var HtmlView $this
 */

use PamutProba\Core\App\Config;
use PamutProba\Core\App\View\HtmlView;
use PamutProba\Core\Utility\Url;

$currentProjectId = $this->data->has("project") ? $this->data->get("project")->id : null;
?>
<header>
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a href="<?php echo Url::base() ?>" class="navbar-brand"><?php echo Config::get("APP_TITLE") ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Navigáció">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="<?php echo Url::base() ?>" class="nav-link<?php echo Url::current() === Url::base() ? " active" : "" ?>">Projektlista</a>
                    </li>
                    <li class="nav-item">
                        <a
                            href="<?php echo Url::base(
                                "/projekt" .
                                ($currentProjectId  ? "/?id=$currentProjectId" : "")
                            ) ?>"
                            class="nav-link<?php echo Url::current() === Url::base("/projekt") ? " active" : "" ?>"
                        >
                            <?php if ($currentProjectId): ?>
                            Szerkesztés
                            <?php else: ?>
                            Létrehozás
                            <?php endif ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
