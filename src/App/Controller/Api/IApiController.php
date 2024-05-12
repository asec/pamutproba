<?php

namespace PamutProba\App\Controller\Api;

use PamutProba\App\View\JsonView;

interface IApiController extends \PamutProba\App\Controller\IController
{
    public function __invoke(): JsonView;
}