<?php declare(strict_types=1);

namespace PamutProba\Core\App\Controller;

use PamutProba\Core\App\View\View;

interface IController
{
    /**
     * @return View
     * @throws \Exception
     */
    public function __invoke(): View;
}