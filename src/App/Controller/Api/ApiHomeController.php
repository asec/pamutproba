<?php declare(strict_types=1);

namespace PamutProba\App\Controller\Api;

use PamutProba\App\View\JsonView;
use PamutProba\Entity\Factory\ProjectFactory;

class ApiHomeController implements IApiController
{
    public function __invoke(): JsonView
    {
        return new JsonView([
            "foo" => "bar",
            "baz" => 10,
            "test" => new \DateTime(),
            "project" => ProjectFactory::createMore(10)
        ]);
    }
}