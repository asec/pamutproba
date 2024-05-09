<?php

namespace PamutProba\App;

use PamutProba\App\Input\Input;
use PamutProba\Utility\Path;

class Config
{
    protected static ?Input $data = null;
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
        static::$data = new Input($data);
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