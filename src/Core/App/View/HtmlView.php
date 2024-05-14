<?php declare(strict_types=1);

namespace PamutProba\Core\App\View;

class HtmlView extends View
{
    protected string $template;

    public function __construct(string $template, array $data = [])
    {
        parent::__construct($data);
        $this->template = $template;
    }

    public function render(): string
    {
        $data = &$this->data;
        ob_start();
        require $this->template;
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}