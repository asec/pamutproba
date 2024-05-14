<?php declare(strict_types=1);

namespace PamutProba\Controller\Web;

use PamutProba\Core\App\Controller\IWebController;
use PamutProba\Core\App\Request;
use PamutProba\Core\App\Session;
use PamutProba\Core\App\View\RedirectView;
use PamutProba\Core\Exception\HttpException;
use PamutProba\Core\Http\Status;
use PamutProba\Factory\ProjectFactory;

class WebProjektDeleteController implements IWebController
{
    protected static string $messageSuccess = "A projekt sikeresen törlésre került.";

    public function __construct(
        protected Request $request,
        protected Session $session,
        protected ProjectFactory $projectFactory
    )
    {}

    public function __invoke(): RedirectView
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
            $this->session->flash("message-success", static::$messageSuccess);
            return RedirectView::to("/");
        }

        $this->projectFactory->delete($entity);

        $this->session->flash("message-success", static::$messageSuccess);
        return RedirectView::to("/");
    }
}