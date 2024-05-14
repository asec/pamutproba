<?php declare(strict_types=1);

namespace PamutProba\Controller\Web;

use PamutProba\Core\App\Controller\IWebController;
use PamutProba\Core\App\Request;
use PamutProba\Core\App\View\HtmlView;
use PamutProba\Core\Utility\Path;
use PamutProba\Database\DatabaseEntityType;
use PamutProba\Entity\Project;
use PamutProba\Entity\Status;
use PamutProba\Factory\ProjectFactory;
use PamutProba\Factory\StatusFactory;

class WebHomeController implements IWebController
{
    protected static int $itemsPerPage = 10;

    public function __construct(
        protected Request        $request,
        protected ProjectFactory $projectFactory,
        protected StatusFactory  $statusFactory
    )
    {}

    public function __invoke(): HtmlView
    {
        $factory = $this->projectFactory;

        $partial = $this->request->headers()->has("Pamut-Ajax-Partial");

        $status = $this->request->getParam("status") ?? "";
        if ($status)
        {
            $status = $this->statusFactory->getBy("key", $status) ?? $status;
            if ($status instanceof Status)
            {
                $factory->filterByRelation(DatabaseEntityType::Status, $status);
                $status = $status->key;
            }
        }

        $numPages = 0;
        $currentPage = 1;
        if (static::$itemsPerPage > 0)
        {
            $count = $factory->count();
            $numPages = ceil($count / static::$itemsPerPage);
            $currentPage = min($this->request->getParam("page") ?? 1, $numPages);
        }

        return new HtmlView(Path::template("main" . ($partial ? ".partial" : "") . ".php"), [
            "title" => "Projekt Lista",
            "projects" => $factory->list(
                $currentPage > 0 ? intval(($currentPage - 1) * static::$itemsPerPage) : 0,
                static::$itemsPerPage
            ),
            "statuses" => $this->statusFactory->list(),
            "status" => $status,
            "numPages" => $numPages,
            "currentPage" => $currentPage
        ]);
    }
}