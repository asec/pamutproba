<?php declare(strict_types=1);

namespace PamutProba\Core\App;

use PamutProba\Core\Utility\Input\ImmutableInput;
use PamutProba\Core\Utility\Path;

class Config
{
    protected static ?ImmutableInput $data = null;
    protected static array $filesToCheck = [
        ".env.php",
        ".env.local.php"
    ];

    protected static function create(): void
    {
        $data = [];
        foreach (static::$filesToCheck as $file)
        {
            $file = Path::absolute($file);
            if (is_file($file))
            {
                $data = array_merge($data, require $file);
            }
        }
        static::$data = new ImmutableInput($data);
    }

    /**
     * @throws \Exception
     */
    public static function get(string $key): mixed
    {
        if (static::$data === null)
        {
            static::create();
        }

        return static::$data->get($key);
    }
}