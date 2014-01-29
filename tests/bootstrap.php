<?php
/**
 * Bootstrap file for PHPUnit tests
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
 **/
spl_autoload_register(function($className) {
    $vendorNamespace = "Nijens\\Utilities\\";
    if (strpos($className, $vendorNamespace) === 0) {
        $classNameFile = substr($className, strlen($vendorNamespace) ) . ".php";
        include __DIR__ . "/../src/" . $classNameFile;
    }
}, true);
