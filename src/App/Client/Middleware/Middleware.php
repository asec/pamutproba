<?php declare(strict_types=1);

namespace PamutProba\App\Client\Middleware;

use PamutProba\App\Request;

abstract class Middleware
{
    public abstract function __invoke(Request $request, callable $next): Request;
}