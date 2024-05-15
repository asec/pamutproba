<?php

namespace PamutProba\Core\App\Client;

use PamutProba\Core\App\Request;
use PamutProba\Core\App\Router\RouteHandler\RouteHandler;
use PamutProba\Core\App\Session;
use PamutProba\Core\Http\Status;

interface IClientBase
{
    public function exitWithError(\Exception $error, Status $code = Status::InternalServerError): void;
}