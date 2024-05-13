<?php declare(strict_types=1);

namespace PamutProba\App\Controller\Web;

use PamutProba\App\Client\Client;
use PamutProba\App\Request;
use PamutProba\App\Session;
use PamutProba\App\View\HtmlView;
use PamutProba\Entity\Model\Model;
use PamutProba\Entity\Owner;
use PamutProba\Entity\Project;
use PamutProba\Exception\HttpException;
use PamutProba\Exception\ValidationException;
use PamutProba\Http\Status;
use PamutProba\Utility\Path;

class WebProjektSaveController implements IWebController
{
    public function __construct(
        protected Request $request,
        protected Session $session,
        protected Model $statusModel,
        protected Model $ownerModel,
        protected Model $projectModel
    )
    {}

    public function __invoke(): HtmlView
    {
        $id = (int) $this->request->getField("id");
        $status_id = (int) $this->request->getField("status");
        $owner_email = $this->request->getField("owner_email");

        /**
         * @var \PamutProba\Entity\Status|null $status
         */
        $status = $this->statusModel->get($status_id);
        if ($status === null)
        {
            throw HttpException::with("A megadott státusz nem létezik", Status::BadRequest);
        }

        /**
         * @var Owner|null $owner
         */
        $owner = $this->ownerModel->getBy("email", $owner_email);
        if ($owner !== null)
        {
            $owner->name = $this->request->getField("owner_name");
            $owner->email = $this->request->getField("owner_email");
        }
        else
        {
            $owner = new Owner(
                null,
                $this->request->getField("owner_name"),
                $this->request->getField("owner_email")
            );
        }

        /**
         * @var Project|null $prevProject
         */
        $prevProject = null;
        if ($id)
        {
            $prevProject = $this->projectModel->get($id);
            if ($prevProject === null)
            {
                throw HttpException::with("A megadott projekt nem létezik", Status::BadRequest);
            }
        }

        $newProject = new Project(
            $prevProject->id ?? null,
            $this->request->getField("title"),
            $this->request->getField("description"),
            $status,
            $owner
        );

        try
        {
            $this->projectModel->save($newProject);
        }
        catch (ValidationException $e)
        {
            $this->session->flash("message-error", $e->getMessage() . ".");
            $this->session->flash("project", $newProject);
            Client::redirect("/projekt" . ($prevProject && $prevProject->id ? "/?id={$prevProject->id}" : ""));
        }


        $this->session->flash("message-success", "A projekt mentése sikeres volt.");
        Client::redirect("/");
    }
}