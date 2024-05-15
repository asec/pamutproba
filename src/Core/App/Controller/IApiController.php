<?php declare(strict_types=1);

namespace PamutProba\Core\App\Controller;

use PamutProba\Core\App\View\JsonView;

interface IApiController extends IController
{
    public function __invoke(): JsonView;
}