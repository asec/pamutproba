<?php declare(strict_types=1);

namespace PamutProba\App\Controller\Web;

use PamutProba\App\Request;
use PamutProba\App\Session;
use PamutProba\App\View\HtmlView;
use PamutProba\Entity\Model\Model;
use PamutProba\Exception\HttpException;
use PamutProba\Http\Status;
use PamutProba\Utility\Path;

class WebProjektController implements IWebController
{
    public function __construct(
        protected Request $request,
        protected Session $session,
        protected Model $projectModel,
        protected Model $statusModel
    )
    {}

    public function __invoke(): HtmlView
    {
        $project = null;
        if ($id = (int) $this->request->getParam("id"))
        {
            $project = $this->projectModel->get($id);
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
            "statuses" => $this->statusModel->list()
        ]);
    }
}