<?php

namespace PamutProba\Core\Mail;

enum MailServiceDriver: string
{
    case Null = "";
    case SimpleMail = "simple";
}
