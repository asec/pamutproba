<?php
/**
 * @var HtmlView $this
 */

use PamutProba\App\View\HtmlView;
use PamutProba\Utility\Url;

$numPages = (int) $this->data->get("numPages");
$currentPage = (int) $this->data->get("currentPage");
$status = $this->data->get("status");

?>
<?php if ($numPages > 1): ?>
<nav aria-label="Oldalak lapozása" class="my-3">
    <ul class="pagination fw-light justify-content-end">
        <li class="page-item">
            <a
                class="page-link<?php echo $currentPage === 1 ? " disabled" : "" ?>"
                href="<?php echo Url::current([ "status" => $status ]) ?>"
            >
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <li class="page-item">
            <a
                class="page-link<?php echo $currentPage === 1 ? " disabled" : "" ?>"
                href="<?php echo Url::current([
                        "status" => $status,
                        "page" => $currentPage > 2 ? max($currentPage - 1, 1) : null
                    ])
                ?>"
            >
                <span aria-hidden="true">&lsaquo;</span>
            </a>
        </li>

        <?php for ($i = 1; $i <= $numPages; $i++): ?>
        <li class="page-item">
            <a
                class="page-link<?php echo $currentPage === $i ? " active" : "" ?>"
                href="<?php echo Url::current([
                        "status" => $status,
                        "page" => $i > 1 ? $i : null
                    ])
                ?>"
            >
                <?php echo $i ?>
            </a>
        </li>
        <?php endfor ?>


        <li class="page-item">
            <a
                class="page-link<?php echo $currentPage === $numPages ? " disabled" : "" ?>"
                href="<?php echo Url::current([
                        "status" => $status,
                        "page" => min($currentPage + 1, $numPages)
                    ])
                ?>"
            >
                <span aria-hidden="true">&rsaquo;</span>
            </a>
        </li>
        <li class="page-item">
            <a
                class="page-link<?php echo $currentPage === $numPages ? " disabled" : "" ?>"
                href="<?php echo Url::current([
                        "status" => $status,
                        "page" => $numPages
                    ])
                ?>"
            >
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
<?php endif; ?>