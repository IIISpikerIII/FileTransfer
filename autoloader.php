<?php

define('DOCROOT', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

spl_autoload_register(function ($class) {
    include_once DOCROOT . str_replace('\\', '/', $class).'.php';
});