<?php

namespace PamutProba\App\Client\Middleware;

use PamutProba\App\Request;

abstract class Middleware
{
    public abstract function __invoke(Request $request, callable $next): Request;
}