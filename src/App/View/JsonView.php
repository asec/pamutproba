<?php

namespace PamutProba\App\View;

use PamutProba\Http\MimeType;

class JsonView extends View
{
    public function render(): void
    {
        echo json_encode($this->data->all());
    }
}