<?php

namespace PamutProba\Core\App\Client;

use PamutProba\Core\Http\Status;

interface IClientBase
{
    public function exitWithError(\Exception $error, Status $code = Status::InternalServerError): void;
}