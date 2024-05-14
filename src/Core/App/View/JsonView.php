<?php declare(strict_types=1);

namespace PamutProba\Core\App\View;

class JsonView extends View
{
    public function render(): string
    {
        return json_encode($this->data->all());
    }
}