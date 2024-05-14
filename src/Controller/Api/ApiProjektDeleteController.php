<?php

namespace PamutProba\Controller\Api;

use PamutProba\Core\App\Controller\IApiController;
use PamutProba\Core\App\Request;
use PamutProba\Core\App\View\JsonView;
use PamutProba\Core\Exception\HttpException;
use PamutProba\Core\Http\Status;
use PamutProba\Factory\ProjectFactory;

class ApiProjektDeleteController implements IApiController
{
    protected static string $messageSuccess = "A projekt sikeresen törlésre került.";

    public function __construct(
        protected Request $request,
        protected ProjectFactory $projectFactory
    )
    {}

    public function __invoke(): JsonView
    {
        $id = (int) $this->request->getField("id");
        if (!$id)
        {
            throw HttpException::with(
                "The request is missing the following parameter from it's body: [id]",
                Status::BadRequest
            );
        }

        $entity = $this->projectFactory->get($id);
        if ($entity === null)
        {
            return new JsonView([
                "message" => static::$messageSuccess
            ]);
        }

        $this->projectFactory->delete($entity);
        return new JsonView([
            "message" => static::$messageSuccess
        ]);
    }
}