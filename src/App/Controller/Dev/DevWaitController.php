<?php

namespace PamutProba\App\Controller\Dev;

use PamutProba\App\Request;
use PamutProba\App\View\JsonView;
use PamutProba\App\View\View;
use PamutProba\Exception\HttpException;

class DevWaitController implements \PamutProba\App\Controller\IController
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