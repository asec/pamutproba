<?php declare(strict_types=1);

namespace PamutProba\Controller\Dev;

use PamutProba\Core\App\Controller\IController;
use PamutProba\Core\App\Request;
use PamutProba\Core\App\Session;
use PamutProba\Core\App\View\RedirectView;
use PamutProba\Core\Exception\HttpException;
use PamutProba\Core\Http\Status;
use PamutProba\Entity\Project;
use PamutProba\Factory\OwnerFactory;
use PamutProba\Factory\ProjectFactory;
use PamutProba\Factory\StatusFactory;

class DevRandomController implements IController
{
    public function __construct(
        protected Request        $request,
        protected Session        $session,
        protected ProjectFactory $projectFactory,
        protected StatusFactory  $statusFactory,
        protected OwnerFactory $ownerFactory
    )
    {}

    public function __invoke(): RedirectView
    {
        $count = (int) $this->request->getParam("count") ?: 10;

        if ($count < 0 || $count > 1000)
        {
            throw HttpException::with(
                "A 'count' paraméter értéke 1 és 1000 között lehet. Az alapértelmezett 10",
                Status::BadRequest
            );
        }

        $statuses = $this->statusFactory->list();
        if (!$statuses)
        {
            throw new \Exception("A művelethez kérlek, vigyél fel először státuszokat.");
        }
        $owners = $this->ownerFactory->list();
        if (!$owners)
        {
            throw new \Exception("A művelethez kérlek, vigyél fel először kapcsolattartókat.");
        }

        for ($i = 0; $i < $count; $i++)
        {
            $statusIndex = array_rand($statuses);
            $ownerIndex = array_rand($owners);

            $project = Project::random();
            $project->status = $statuses[$statusIndex];
            $project->owner = $owners[$ownerIndex];

            $this->projectFactory->save($project);
        }

        $this->session->flash(
            "message-success",
            "(dev) A projektek tömeges létrehozása sikeres volt. $count db készült."
        );
        return RedirectView::to("/");
    }
}