<?php

namespace PamutProba\App;

class Request
{
    protected Input $headers;
    protected Input $params;
    protected Input $body;

    public function __construct(array $headers, array $params, array $body)
    {
        $this->headers = new Input($headers);
        $this->params = new Input($params);
        $this->body = new Input($body);
    }

    public function headers(): Input
    {
        return $this->headers;
    }

    public function getHeader(string $key): null|string
    {
        return $this->headers()->get($key);
    }

    public function params(): Input
    {
        return $this->params;
    }

    public function getParam(string $key): null|string
    {
        return $this->params()->get($key);
    }

    public function body(): Input
    {
        return $this->body;
    }

    public function getField(string $key): null|string
    {
        return $this->body()->get($key);
    }
}