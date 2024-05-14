<?php declare(strict_types=1);

namespace PamutProba\Controller\Web;

use PamutProba\Core\App\Config;
use PamutProba\Core\App\Controller\IWebController;
use PamutProba\Core\App\Request;
use PamutProba\Core\App\Session;
use PamutProba\Core\App\View\HtmlView;
use PamutProba\Core\App\View\RedirectView;
use PamutProba\Core\Exception\HttpException;
use PamutProba\Core\Exception\ValidationException;
use PamutProba\Core\Mail\IMailService;
use PamutProba\Core\Utility\Path;
use PamutProba\Core\Utility\Url;
use PamutProba\Entity\Owner;
use PamutProba\Entity\Project;
use PamutProba\Factory\OwnerFactory;
use PamutProba\Factory\ProjectFactory;
use PamutProba\Factory\StatusFactory;

class WebProjektSaveController implements IWebController
{
    public function __construct(
        protected Request       $request,
        protected Session       $session,
        protected StatusFactory $statusFactory,
        protected OwnerFactory  $ownerFactory,
        protected ProjectFactory $projectFactory,
        protected IMailService $mailService
    )
    {}

    public function __invoke(): RedirectView
    {
        $id = (int) $this->request->getField("id");
        $status_id = (int) $this->request->getField("status");
        $owner_email = $this->request->getField("owner_email");

        $status = $this->statusFactory->get($status_id);
        if ($status === null)
        {
            throw HttpException::with(
                "A megadott státusz nem létezik",
                \PamutProba\Core\Http\Status::BadRequest
            );
        }

        $owner = $this->ownerFactory->getBy("email", $owner_email);
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

        $prevProject = null;
        if ($id)
        {
            $prevProject = $this->projectFactory->get($id);
            if ($prevProject === null)
            {
                throw HttpException::with(
                    "A megadott projekt nem létezik",
                    \PamutProba\Core\Http\Status::BadRequest
                );
            }
        }

        $newProject = new Project(
            $prevProject->id ?? null,
            $this->request->getField("title"),
            $this->request->getField("description"),
            $status,
            $owner
        );

        if ($prevProject !== null)
        {
            $changes = $newProject->diff($prevProject);
            if ($changes)
            {
                $mailSubject = Config::get("APP_TITLE") . ": Értesítés a project adatainak változásáról";
                $mailBody = new HtmlView(
                    Path::template("./email/notification.php"),
                    [
                        "name" => "Adminisztrátor",
                        "projectUrl" => Url::base("/projekt/?id=$newProject->id"),
                        "changes" => $changes,
                        "appName" => Config::get("APP_TITLE")
                    ]
                );
                $this->mailService->send(Config::get("MAIL")["FROM"], $mailSubject, $mailBody->render());
            }
        }

        try
        {
            $this->projectFactory->save($newProject);
        }
        catch (ValidationException $e)
        {
            $this->session->flash("message-error", $e->getMessage() . ".");
            $this->session->flash("project", $newProject);
            return RedirectView::to(
                "/projekt" . ($prevProject && $prevProject->id ? "/?id={$prevProject->id}" : "")
            );
        }


        $this->session->flash("message-success", "A projekt mentése sikeres volt.");
        return RedirectView::to("/");
    }
}