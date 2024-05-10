<?php

namespace PamutProba\Http;

enum MimeType: string
{
    case Any = "*/*";
    case Html = "text/html";
    case Json = "application/json";
}
