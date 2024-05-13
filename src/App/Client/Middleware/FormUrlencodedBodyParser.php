<?php

namespace PamutProba\App\Client\Middleware;

use PamutProba\App\Request;
use PamutProba\Http\MimeType;

class FormUrlencodedBodyParser extends Middleware
{
    /**
     * @throws \Exception
     */
    public function __invoke(Request $request, callable $next): Request
    {
        $key = "CONTENT_TYPE";
        $typeNeeded = MimeType::FormUrlencoded->value;

        if (!$request->headers()->has($key))
        {
            return $next($request);
        }

        $contentType = $request->getHeader($key);
        if (strtok($contentType, ";") !== $typeNeeded)
        {
            return $next($request);
        }

        if (count($request->body()->all()) > 0)
        {
            return $next($request);
        }

        $body = file_get_contents("php://input");
        parse_str($body, $parsed);

        if (!is_array($parsed))
        {
            throw new \Exception("Failed to parse the input data");
        }

        return $next(Request::from(
            $request->headers()->all(),
            $request->params()->all(),
            $parsed
        ));
    }
}