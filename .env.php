<?php declare(strict_types=1);

use PamutProba\Core\App\Environment;
use PamutProba\Core\Database\DatabaseServiceDriver;
use PamutProba\Core\Mail\MailServiceDriver;

return [
    "APP_ENV" => Environment::Development,
    "APP_TITLE" => "WeLove Test",

    "MYSQL" => [
        "DRIVER" => DatabaseServiceDriver::MySQLWithPdo,
        "HOST" => "localhost",
        "PORT" => 3306,
        "USER" => "root",
        "PASSWORD" => "",
        "DATABASE" => "pamutproba"
    ],

    "MAIL" => [
        "DRIVER" => MailServiceDriver::Null,
        "FROM" => "info@welove.test",
        "SMTP_HOST" => "",
        "SMTP_PORT" => "",
        "SMTP_USER" => "",
        "SMTP_PASSWORD" => ""
    ]
];