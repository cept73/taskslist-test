<?php
    namespace Todo;

    // Plug autoload libraries
    require __DIR__ . '/vendor/autoload.php';

    // Plug MVC
    require 'app/view.php';
    require 'app/controller.php';

    // Init new application and resolve request
    $siteConfig = require('config/global.php');
    $siteRoutes = require('config/routes.php');
    $url = $_SERVER['REDIRECT_URL'] ?? '';
    (new \Todo\Controller($siteConfig))->resolve($url, $_REQUEST, $siteRoutes);
