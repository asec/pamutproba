<?php declare(strict_types=1);

namespace PamutProba\Core\App\Client\Middleware;

use PamutProba\Core\App\Request;

abstract class Middleware
{
    public abstract function __invoke(Request $request, callable $next): Request;
}