<?php declare(strict_types=1);

namespace PamutProba\Core\App\Client\Middleware;

use PamutProba\Core\App\Request;
use PamutProba\Core\Http\MimeType;

class HeaderParseAccept implements IMiddleware
{
    public function __invoke(Request $request, callable $next): Request
    {
        $key = "HTTP_ACCEPT";
        if (!$request->headers()->has($key))
        {
            return $next($request);
        }

        $value = $request->getHeader($key);
        $values = explode(",", $value);

        $result = [];
        foreach ($values as $v)
        {
            $v = strtok(trim($v), ";");
            $v = MimeType::tryFrom($v);
            if ($v === null)
            {
                continue;
            }

            $result[] = $v;
        }

        $headers = $request->headers()->all();
        $headers["APP_ACCEPT"] = $result;

        return $next(Request::from(
            $headers,
            $request->params()->all(),
            $request->body()->all()
        ));
    }
}