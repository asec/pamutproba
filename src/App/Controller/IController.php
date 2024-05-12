<?php

namespace PamutProba\App\Controller;

use PamutProba\App\View\View;

interface IController
{
    /**
     * @return View
     * @throws \Exception
     */
    public function __invoke(): View;
}