<?php

namespace PamutProba\App\View;

use PamutProba\App\Input\Input;

abstract class View
{
    protected Input $data;

    public function __construct(array $data = [])
    {
        $this->data = new Input($data);
    }

    public abstract function render(): void;
}