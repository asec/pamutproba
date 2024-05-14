<?php declare(strict_types=1);

namespace PamutProba\Core\Utility\Development;

interface IDevelopmentService
{
    public function isDev(): bool;
    public function printTrace(array $trace): void;
}