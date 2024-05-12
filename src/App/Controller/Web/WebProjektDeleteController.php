<?php

namespace PamutProba\App\Controller\Web;

use PamutProba\App\Client\Client;
use PamutProba\App\Request;
use PamutProba\App\Session;
use PamutProba\App\View\HtmlView;
use PamutProba\Entity\Model\Model;
use PamutProba\Exception\HttpException;
use PamutProba\Http\Status;

class WebProjektDeleteController implements IWebController
{
    protected static string $messageSuccess = "A projekt sikeresen törlésre került.";

    public function __construct(
        protected Request $request,
        protected Session $session,
        protected Model $projectModel
    )
    {}

    public function __invoke(): HtmlView
    {
        $id = $this->request->getField("id");
        if (!$id)
        {
            throw new HttpException(
                "The request is missing the following parameter from it's body: [id]",
                Status::BadRequest
            );
        }
        $entity = $this->projectModel->get($id);
        if ($entity === null)
        {
            $this->session->flash("message-success", static::$messageSuccess);
            Client::redirect("/");
        }

        $this->projectModel->delete($entity);

        $this->session->flash("message-success", static::$messageSuccess);
        Client::redirect("/");
    }
}