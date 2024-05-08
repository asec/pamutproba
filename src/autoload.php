<?php
spl_autoload_register(/**
 * @throws Exception
 */ function (string $className) {
    $realClass = explode("\\", $className);
    if (!count($realClass) || array_shift($realClass) !== "PamutProba")
    {
        return;
    }
    $path = __DIR__ . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $realClass) . ".php";
    if (!is_file($path))
    {
        throw new Exception("Missing class: '{$className}'");
    }

    require_once $path;
});