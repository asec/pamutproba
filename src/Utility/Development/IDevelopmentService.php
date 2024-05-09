<?php

namespace PamutProba\Utility\Development;

interface IDevelopmentService
{
    public function isDev(): bool;
    public function printTrace(array $trace): void;
}