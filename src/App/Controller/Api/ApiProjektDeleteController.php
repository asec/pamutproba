<?php

namespace PamutProba\App\Controller\Api;

use PamutProba\App\Request;
use PamutProba\App\View\JsonView;
use PamutProba\Entity\Model\Model;
use PamutProba\Exception\HttpException;
use PamutProba\Http\Status;

class ApiProjektDeleteController implements IApiController
{
    protected static string $messageSuccess = "A projekt sikeresen törlésre került.";

    public function __construct(
        protected Request $request,
        protected Model $projectModel
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

        $entity = $this->projectModel->get($id);
        if ($entity === null)
        {
            return new JsonView([
                "message" => static::$messageSuccess
            ]);
        }

        $this->projectModel->delete($entity);
        return new JsonView([
            "message" => static::$messageSuccess
        ]);
    }
}