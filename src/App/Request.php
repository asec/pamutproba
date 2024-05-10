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

    /**
     * @throws \Exception
     */
    public function getHeader(string $key): mixed
    {
        return $this->headers()->get($key);
    }

    public function params(): Input
    {
        return $this->params;
    }

    /**
     * @throws \Exception
     */
    public function getParam(string $key): null|string
    {
        return $this->params()->get($key);
    }

    public function body(): Input
    {
        return $this->body;
    }

    /**
     * @throws \Exception
     */
    public function getField(string $key): null|string
    {
        return $this->body()->get($key);
    }
}