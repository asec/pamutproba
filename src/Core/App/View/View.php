<?php declare(strict_types=1);

namespace PamutProba\Core\App\View;

use PamutProba\Core\Utility\Input\ImmutableInput;

abstract class View
{
    protected ImmutableInput $data;

    public function __construct(array $data = [])
    {
        $this->data = new ImmutableInput($data);
    }

    public abstract function render(): string;
}