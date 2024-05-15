<?php declare(strict_types=1);

namespace PamutProba\Core\Mail;

class Mail
{
    protected static IMailService $service;

    public static function set(IMailService $service): void
    {
        static::$service = $service;
    }

    public static function get(): IMailService
    {
        return static::$service;
    }
}