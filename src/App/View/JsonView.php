<?php

namespace PamutProba\App\View;

class JsonView extends View
{
    public function render(): void
    {
        header("Content-Type: application/json");
        echo json_encode($this->data->all());
    }
}