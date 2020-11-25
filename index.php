<?php
/** @noinspection PhpUnhandledExceptionInspection */

use app\controller\Controller;

require __DIR__ . '/vendor/autoload.php';
spl_autoload_register(static function ($className) {
    $className = str_replace('\\', '/', $className);
    require_once($_SERVER['DOCUMENT_ROOT'] . "/$className.php");
});

// Init new application and resolve request
$siteConfig = require('config/global.php');
$siteRoutes = require('config/routes.php');
$url = $_SERVER['REDIRECT_URL'] ?? '';

$controller = new Controller($siteConfig);
$controller->resolve($url, $_REQUEST, $siteRoutes);
