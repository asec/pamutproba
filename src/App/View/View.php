<?php

namespace PamutProba\App\View;

use PamutProba\App\Input\ImmutableInput;

abstract class View
{
    protected ImmutableInput $data;

    public function __construct(array $data = [])
    {
        $this->data = new ImmutableInput($data);
    }

    public abstract function render(): void;
}