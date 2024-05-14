<?php

use PamutProba\Core\App\Environment;
use PamutProba\Core\Mail\MailServiceDriver;

return [
    "APP_ENV" => Environment::Development,
    "APP_TITLE" => "WeLove Test",

    "MYSQL" => [
        "DRIVER" => "pdo",
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