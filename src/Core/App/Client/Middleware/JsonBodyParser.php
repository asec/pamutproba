<?php declare(strict_types=1);

namespace PamutProba\Core\App\Client\Middleware;

use PamutProba\Core\App\Request;
use PamutProba\Core\Http\MimeType;

class JsonBodyParser extends Middleware
{
    /**
     * @throws \Exception
     */
    public function __invoke(Request $request, callable $next): Request
    {
        $key = "CONTENT_TYPE";
        $typeNeeded = MimeType::Json->value;

        if (!$request->headers()->has($key))
        {
            return $next($request);
        }

        $contentType = $request->getHeader($key);
        if (strtok($contentType, ";") !== $typeNeeded)
        {
            return $next($request);
        }

        $body = file_get_contents("php://input");
        $parsed = json_decode($body, true);
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