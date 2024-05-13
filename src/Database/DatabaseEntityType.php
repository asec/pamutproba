<?php declare(strict_types=1);

namespace PamutProba\Database;

enum DatabaseEntityType
{
    case Owner;
    case Project;
    case Status;
}
