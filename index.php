<?php
/** @noinspection PhpUnhandledExceptionInspection */

use app\controller\Controller;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/autoload.php';

// Init new application and resolve request
$siteConfig = require('config/global.php');
$siteRoutes = require('config/routes.php');
$url = $_SERVER['REDIRECT_URL'] ?? '';

$controller = new Controller($siteConfig);
$controller->resolve($url, $_REQUEST, $siteRoutes);
