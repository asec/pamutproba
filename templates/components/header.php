<?php
/**
 * @var HtmlView $this
 */

use PamutProba\App\Config;
use PamutProba\App\View\HtmlView;
use PamutProba\Utility\Path;
use PamutProba\Utility\Url;

?><!doctype html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo implode(" - ", [$this->data->get("title"), Config::get("APP_TITLE")]) ?></title>
    <link rel="stylesheet" href="<?php echo Url::base("/css/bootstrap.min") ?>">
    <link rel="stylesheet" href="<?php echo Url::base("/css/style.css") ?>">
</head>
<body>

<?php require Path::template("./components/menu.php") ?>