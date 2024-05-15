<?php declare(strict_types=1);

namespace PamutProba\Controller\Web;

use PamutProba\Core\App\Controller\IWebController;
use PamutProba\Core\App\Request;
use PamutProba\Core\App\Session;
use PamutProba\Core\App\View\HtmlView;
use PamutProba\Core\Exception\HttpException;
use PamutProba\Core\Http\Status;
use PamutProba\Core\Utility\Path;
use PamutProba\Factory\ProjectFactory;
use PamutProba\Factory\StatusFactory;

class WebProjektController implements IWebController
{
    public function __construct(
        protected Request        $request,
        protected Session        $session,
        protected ProjectFactory $projectFactory,
        protected StatusFactory $statusFactory
    )
    {}

    public function __invoke(): HtmlView
    {
        $project = null;
        if ($id = (int) $this->request->getParam("id"))
        {
            $project = $this->projectFactory->get($id);
            if ($project === null)
            {
                throw HttpException::with("A keresett oldal nem található", Status::NotFound);
            }
        }

        $flashedProject = $this->session->getFlashed("project");
        if ($flashedProject)
        {
            $project = $flashedProject;
        }

        return new HtmlView(Path::template("projekt.php"), [
            "title" => $project && $project->id ? "Projekt szerkesztése" : "Projekt Létrehozása",
            "project" => $project,
            "statuses" => $this->statusFactory->list(),
            "message-success" => $this->session->getFlashed("message-success") ?? "",
            "message-error" => $this->session->getFlashed("message-error") ?? ""
        ]);
    }
}