<?php

namespace PamutProba\Core\Mail;

interface IMailService
{
    public function __construct(string $from, array $params = []);
    public function setHeader(string $key, string $value): static;
    public function send(string $to, string $subject, string $message): bool;
}