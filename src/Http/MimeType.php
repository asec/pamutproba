<?php

namespace PamutProba\Http;

enum MimeType: string
{
    case Any = "*/*";
    case Html = "text/html";
    case Json = "application/json";

    public function contentType(): string
    {
        return match($this)
        {
            MimeType::Json => MimeType::Json->value,
            MimeType::Html, MimeType::Any => MimeType::Html->value . "; charset=utf-8"
        };
    }
}
