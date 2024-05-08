<?php

namespace PamutProba\App\View;

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
        header("Content-Type: text/html; charset=utf-8");
        require $this->template;
    }
}