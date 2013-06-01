<?php

/*
 * bootstrapping the test suite with composer
 */

spl_autoload_register(function ($class) {
            if (preg_match('#^Project\\\\(.+)$#', $class, $ret)) {
                $relPath = str_replace('\\', DIRECTORY_SEPARATOR, $ret[1]);
                require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . $relPath . '.php';
            }
        });