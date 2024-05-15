<?php declare(strict_types=1);

namespace PamutProba\Core\App;

enum Environment: string
{
    case Development = "dev";
    case Production = "prod";
}
