<?php
    // Plug autoload libraries
    require __DIR__ . '/vendor/autoload.php';

    // Plug MVC
    require 'app/models.php';
    require 'app/view.php';
    require 'app/controller.php';

    // Init new application and resolve request
    $siteConfig = require('config/global.php');
    $siteRoutes = require('config/routes.php');
    (new \App\Controller($siteConfig))->resolve($_REQUEST, $siteRoutes);
