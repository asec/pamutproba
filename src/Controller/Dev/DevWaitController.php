<?php

namespace PamutProba\Controller\Dev;

use PamutProba\Core\App\Controller\IController;
use PamutProba\Core\App\Request;
use PamutProba\Core\App\View\JsonView;
use PamutProba\Core\App\View\View;
use PamutProba\Core\Exception\HttpException;

class DevWaitController implements IController
{
    public function __construct(
        protected Request $request
    )
    {}

    public function __invoke(): View
    {
        $ms = (int) $this->request->getParam("ms") ?: rand(200, 3000);
        $errorFrequency = (int) $this->request->getParam("error") ?: 0;
        usleep($ms * 1000);

        if ($errorFrequency > 0 && rand(0, 100) < $errorFrequency)
        {
            throw HttpException::with("Random exception");
        }

        return new JsonView([
            "slept" => $ms,
            "message" => "Hey, wake up!"
        ]);
    }
}