<?php
    // Activate secret config for getenv('something')
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    // Return open config
    return [
        'site_name'     => 'TaskMan',       // <-- Site name
        'version'       => '1.0',           // Version
        'site_url'      => '/',             // <-- Web-site base url
        'lang'          => 'en',            // 
        'copyright'     => 'by Cept',       // Footer copyright
    ];
