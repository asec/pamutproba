<?php

namespace PamutProba\App;

use PamutProba\App\Input\ImmutableInput;

readonly class Request
{
    public function __construct(
        protected ImmutableInput $headers,
        protected ImmutableInput $params,
        protected ImmutableInput $body
    )
    {}

    public static function from(array $headers, array $params, array $body): static
    {
        return new static(
            new ImmutableInput($headers),
            new ImmutableInput($params),
            new ImmutableInput($body)
        );
    }

    public function headers(): ImmutableInput
    {
        return $this->headers;
    }

    public function getHeader(string $key): mixed
    {
        try
        {
            $value = $this->headers->get($key);
        }
        catch (\Exception $e)
        {
            $value = null;
        }

        return $value;
    }

    public function params(): ImmutableInput
    {
        return $this->params;
    }

    public function getParam(string $key): null|string
    {
        try
        {
            $value = $this->params->get($key);
        }
        catch (\Exception $e)
        {
            $value = null;
        }

        return $value;
    }

    public function body(): ImmutableInput
    {
        return $this->body;
    }

    public function getField(string $key): null|string
    {
        try
        {
            $value = $this->body->get($key);
        }
        catch (\Exception $e)
        {
            $value = null;
        }

        return $value;
    }
}