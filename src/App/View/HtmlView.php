<?php declare(strict_types=1);

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
        require $this->template;
    }
}