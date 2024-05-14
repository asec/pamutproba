<?php declare(strict_types=1);

namespace PamutProba\Core\App\Controller;

use PamutProba\Core\App\View\HtmlView;
use PamutProba\Core\App\View\RedirectView;

interface IWebController extends IController
{
    public function __invoke(): HtmlView|RedirectView;
}