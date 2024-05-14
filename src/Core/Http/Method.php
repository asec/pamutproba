<?php declare(strict_types=1);

namespace PamutProba\Core\Http;

enum Method: string
{
    case GET = "GET";
    case POST = "POST";
    case DELETE = "DELETE";
}
