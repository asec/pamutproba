<?php

namespace PamutProba\App;

use PamutProba\App\Input\Input;

readonly class Request
{
    public function __construct(
        protected Input $headers,
        protected Input $params,
        protected Input $body
    )
    {}

    public static function from(array $headers, array $params, array $body): static
    {
        return new static(
            new Input($headers),
            new Input($params),
            new Input($body)
        );
    }

    public function headers(): Input
    {
        return $this->headers;
    }

    public function getHeader(string $key): mixed
    {
        return $this->headers()->has($key) ? $this->headers()->get($key) : null;
    }

    public function params(): Input
    {
        return $this->params;
    }

    public function getParam(string $key): null|string
    {
        return $this->params()->has($key) ? $this->params()->get($key) : null;
    }

    public function body(): Input
    {
        return $this->body;
    }

    public function getField(string $key): null|string
    {
        return $this->body()->has($key) ? $this->body()->get($key) : null;
    }
}