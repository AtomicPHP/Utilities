<?php

use Nijens\Utilities\Configuration;

/*
 * POC script to demonstrate caching is usually faster
 * As it's not always faster, it's not suitable to be tested in a unit test
 */
require '../vendor/autoload.php';

foreach ([2, 3] as $count) {
    $success = 0;
    for ($i = 0; $i < 100; ++$i) {
        $cached = new Configuration(__DIR__ . '/Resources/configuration/default.xml', __DIR__ . '/Resources/xsd/default.xsd');
        $cached->loadConfiguration(null);
        $notCached = new Configuration(__DIR__ . '/Resources/configuration/default.xml', __DIR__ . '/Resources/xsd/default.xsd', false);
        $notCached->loadConfiguration(null);
        $cachedStart = microtime(true);
        for ($j = 0; $j < $count; ++$j) {
            $cached->get('/test/foo');
        }
        $cachedTime = microtime(true) - $cachedStart;

        $notCachedStart = microtime(true);
        for ($j = 0; $j < $count; ++$j) {
            $notCached->get('/test/foo');
        }
        $notCachedTime = microtime(true) - $notCachedStart;

        if ($notCachedTime > $cachedTime) {
            ++$success;
        }
    }
    echo "Cached was fastest {$success} times out of 100 on {$count} calls\n";
}
