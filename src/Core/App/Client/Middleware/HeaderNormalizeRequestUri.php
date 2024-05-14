<?php declare(strict_types=1);

namespace PamutProba\Core\App\Client\Middleware;

use PamutProba\Core\App\Request;

class HeaderNormalizeRequestUri extends Middleware
{
    public function __invoke(Request $request, callable $next): Request
    {
        $key = "REQUEST_URI";
        if (!$request->headers()->has($key))
        {
            return $next($request);
        }

        $headers = $request->headers()->all();
        $headers[$key] = $this->transformRequestUri($headers[$key]);

        return $next(Request::from(
            $headers,
            $request->params()->all(),
            $request->body()->all()
        ));
    }

    protected function transformRequestUri(string $value): string
    {
        $value = strtok($value, "?");
        $valueElements = explode("/", $value);
        while (end($valueElements) === "")
        {
            array_pop($valueElements);
        }
        return count($valueElements) === 0 ? "/" : implode("/", $valueElements);
    }
}