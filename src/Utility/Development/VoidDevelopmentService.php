<?php declare(strict_types=1);

namespace PamutProba\Utility\Development;

class VoidDevelopmentService implements IDevelopmentService
{
    public function isDev(): bool
    {
        return false;
    }

    public function printTrace(array $trace): void
    {}
}