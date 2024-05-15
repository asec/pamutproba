<?php declare(strict_types=1);

namespace PamutProba\Core\Mail;

enum MailServiceDriver: string
{
    case Null = "";
    case SimpleMail = "simple";
}
