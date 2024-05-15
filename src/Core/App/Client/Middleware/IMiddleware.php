<?php declare(strict_types=1);

namespace PamutProba\Core\App\Client\Middleware;

use PamutProba\Core\App\Request;

interface IMiddleware
{
    public function __invoke(Request $request, callable $next): Request;
}