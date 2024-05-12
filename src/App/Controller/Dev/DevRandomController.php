<?php

namespace PamutProba\App\Controller\Dev;

use PamutProba\App\Client\Client;
use PamutProba\App\Controller\IController;
use PamutProba\App\Request;
use PamutProba\App\Session;
use PamutProba\App\View\View;
use PamutProba\Entity\Model\Model;
use PamutProba\Entity\Project;

class DevRandomController implements IController
{
    public function __construct(
        protected Request $request,
        protected Session $session,
        protected Model $projectModel,
        protected Model $statusModel,
        protected Model $ownerModel
    )
    {}

    public function __invoke(): View
    {
        $count = (int) $this->request->getParam("count") ?: 10;

        $statuses = $this->statusModel->list();
        $owners = $this->ownerModel->list();

        for ($i = 0; $i < $count; $i++)
        {
            $statusIndex = array_rand($statuses);
            $ownerIndex = array_rand($owners);
            $project = Project::random();
            $project->status = $statuses[$statusIndex];
            $project->owner = $owners[$ownerIndex];

            $this->projectModel->save($project);
        }

        $this->session->flash(
            "message-success",
            "(dev) A projektek tömeges létrehozása sikeres volt. $count db készült."
        );
        Client::redirect("/");
    }
}