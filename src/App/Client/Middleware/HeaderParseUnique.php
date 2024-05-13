<?php

namespace PamutProba\App\Client\Middleware;

use PamutProba\App\Request;

class HeaderParseUnique extends Middleware
{
    public function __invoke(Request $request, callable $next): Request
    {
        $prefix = "Pamut-";
        $headers = $request->headers()->all();
        $apacheHeaders = apache_request_headers();
        foreach ($apacheHeaders as $key => $value)
        {
            if (!str_starts_with($key, $prefix))
            {
                continue;
            }
            $headers[$key] = $value;
        }

        if ($headers === $request->headers()->all())
        {
            return $next($request);
        }

        return $next(Request::from(
            $headers,
            $request->params()->all(),
            $request->body()->all()
        ));
    }
}