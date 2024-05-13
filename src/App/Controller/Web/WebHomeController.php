<?php declare(strict_types=1);

namespace PamutProba\App\Controller\Web;

use PamutProba\App\Request;
use PamutProba\App\View\HtmlView;
use PamutProba\Database\DatabaseEntityType;
use PamutProba\Entity\Model\Model;
use PamutProba\Entity\Model\ProjectModel;
use PamutProba\Entity\Model\StatusModel;
use PamutProba\Entity\Status;
use PamutProba\Utility\Path;

class WebHomeController implements IWebController
{
    protected static int $itemsPerPage = 10;

    protected Request $request;
    protected ProjectModel $projectModel;
    protected StatusModel $statusModel;

    public function __invoke(): HtmlView
    {
        $model = $this->projectModel;

        $partial = $this->request->headers()->has("Pamut-Ajax-Partial");

        $status = $this->request->getParam("status") ?? "";
        if ($status)
        {
            $status = $this->statusModel->getBy("key", $status) ?? $status;
            if ($status instanceof Status)
            {
                $model = $model->filterByRelation(DatabaseEntityType::Status, $status);
                $status = $status->key;
            }
        }

        $numPages = 0;
        $currentPage = 1;
        if (static::$itemsPerPage > 0)
        {
            $count = $model->count();
            $numPages = ceil($count / static::$itemsPerPage);
            $currentPage = min($this->request->getParam("page") ?? 1, $numPages);
        }

        return new HtmlView(Path::template("main" . ($partial ? ".partial" : "") . ".php"), [
            "title" => "Projekt Lista",
            "projects" => $model->list(
                $currentPage > 0 ? intval(($currentPage - 1) * static::$itemsPerPage) : 0,
                static::$itemsPerPage
            ),
            "statuses" => $this->statusModel->list(),
            "status" => $status,
            "numPages" => $numPages,
            "currentPage" => $currentPage
        ]);
    }

    /**
     * @throws \Exception
     */
    public function __construct(
        Request $request,
        Model $projectModel,
        Model $statusModel
    )
    {
        if (!($projectModel instanceof ProjectModel))
        {
            throw new \Exception(
                "Invalid model given for [projectModel]. Needs '" . ProjectModel::class . "', got '" .
                get_class($projectModel) . "'"
            );
        }
        if (!($statusModel instanceof StatusModel))
        {
            throw new \Exception(
                "Invalid model given for [statusModel]. Needs '" . StatusModel::class . "', got '" .
                get_class($projectModel) . "'"
            );
        }
        $this->request = $request;
        $this->projectModel = $projectModel;
        $this->statusModel = $statusModel;
    }
}