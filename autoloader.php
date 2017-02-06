<?php

define('DOCROOT', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

spl_autoload_register(function ($class) {
    if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        $class = str_replace('\\', '/', $class);
    }
    $path = DOCROOT . $class.'.php';
    if (file_exists($path)) {
        include_once $path;
    }
});