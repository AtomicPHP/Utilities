<?php
/**
 * Bootstrap file for PHPUnit tests
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
 */
spl_autoload_register(function($className) {
    if (strpos($className, "AtomicPHP\\Utilities\\") === 0) {
        $classNameFile = substr($className, 20) . ".php";
        include __DIR__ . "/../src/" . $classNameFile;
    }
});
