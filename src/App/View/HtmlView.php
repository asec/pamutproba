<?php

namespace PamutProba\App\View;

use PamutProba\Http\MimeType;

class HtmlView extends View
{
    protected string $template;

    public function __construct(string $template, array $data = [])
    {
        parent::__construct($data);
        $this->template = $template;
    }

    public function render(): void
    {
        $data = &$this->data;
        require $this->template;
    }
}