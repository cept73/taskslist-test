<?php

spl_autoload_register(static function ($className) {
    $className = str_replace('\\', '/', $className);
    require_once($_SERVER['DOCUMENT_ROOT'] . "/$className.php");
});
