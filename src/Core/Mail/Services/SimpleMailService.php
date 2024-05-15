<?php

namespace PamutProba\Core\Mail\Services;

use PamutProba\Core\Mail\IMailService;

class SimpleMailService implements IMailService
{
    /**
     * @var array<string, string>
     */
    protected array $headers = [];

    public function __construct(
        protected string $from,
        protected array $params = []
    )
    {
        $this->setHeader("From", $this->from);
    }

    public function setHeader(string $key, string $value): static
    {
        $this->headers[$key] = $value;

        return $this;
    }

    public function send(string $to, string $subject, string $message): bool
    {
        return mail($to, $subject, $message, $this->headers);
    }
}