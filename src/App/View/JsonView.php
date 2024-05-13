<?php declare(strict_types=1);

namespace PamutProba\App\View;

class JsonView extends View
{
    public function render(): void
    {
        echo json_encode($this->data->all());
    }
}