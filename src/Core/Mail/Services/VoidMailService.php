<?php declare(strict_types=1);

namespace PamutProba\Core\Mail\Services;

use PamutProba\Core\Mail\IMailService;
use PamutProba\Core\Utility\Path;

class VoidMailService implements IMailService
{

    protected static string $logFile = "./logs/email-{uniqid}.txt";
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
        $file = str_replace("{uniqid}", uniqid(), static::$logFile);
        $file = Path::absolute($file);

        $data = [
            date("Y-m-d H:i:s"),
            $_SERVER["REMOTE_ADDR"],
            json_encode([
                "from" => $this->from,
                "to" => $to,
                "headers" => $this->headers,
                "subject" => $subject,
                "message" => $message
            ])
        ];

        $logPath = explode("/", static::$logFile);
        array_pop($logPath);
        $currentPath = [];
        foreach ($logPath as $dir)
        {
            if (!$dir)
            {
                continue;
            }
            $currentPath[] = $dir;
            if (!is_dir(Path::absolute(implode("/", $currentPath))))
            {
                mkdir(Path::absolute(implode("/", $currentPath)));
            }
        }

        $fp = fopen($file, "a+");
        fwrite($fp, implode("\t", $data) . "\n");
        fclose($fp);

        return true;
    }
}