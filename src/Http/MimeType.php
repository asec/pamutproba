<?php declare(strict_types=1);

namespace PamutProba\Http;

enum MimeType: string
{
    case Any = "*/*";
    case Html = "text/html";
    case Json = "application/json";
    case FormUrlencoded = "application/x-www-form-urlencoded";

    public function contentType(): string
    {
        return match($this)
        {
            MimeType::Json => MimeType::Json->value,
            MimeType::Html, MimeType::FormUrlencoded, MimeType::Any => MimeType::Html->value . "; charset=utf-8"
        };
    }
}
