<?php

namespace PamutProba\App\Controller\Api;

use PamutProba\App\Request;
use PamutProba\App\View\JsonView;

class ApiHomePostController implements IApiController
{
    public function __construct(
        protected Request $request
    )
    {}

    public function __invoke(): JsonView
    {
        return new JsonView([
            "headers" => $this->request->headers()->all(),
            "params" => $this->request->params()->all(),
            "body" => $this->request->body()->all(),
            "success" => true
        ]);
    }
}