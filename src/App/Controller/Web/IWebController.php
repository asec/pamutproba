<?php declare(strict_types=1);

namespace PamutProba\App\Controller\Web;

use PamutProba\App\View\HtmlView;

interface IWebController extends \PamutProba\App\Controller\IController
{
    public function __invoke(): HtmlView;
}