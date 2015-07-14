<?php

namespace Nijens\Utilities;

use ReflectionClass;
use ReflectionMethod;

/**
 * AbstractSingleton
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Utilities
 **/
abstract class AbstractSingleton
{
    /**
     * The array with instances extending from AbstractSingleton
     *
     * @access private
     * @var array
     **/
    private static $instances = array();

    /**
     * getInstance
     *
     * Returns an AbstractSingleton instance
     *
     * @access public
     * @param  mixed ...
     * @return AbstractSingleton
     **/
    public static function getInstance()
    {
        $class = new ReflectionClass(get_called_class());
        $className = $class->getName();
        if (isset(self::$instances[$className]) === false || $class->isInstance(self::$instances[$className]) === false) {
            self::$instances[$className] = $class->newInstanceWithoutConstructor();

            if (($constructorMethod = $class->getConstructor()) instanceof ReflectionMethod) {
                call_user_func_array(array(self::$instances[$className], '__construct'), func_get_args());

                if ($constructorMethod->isPublic() === true) {
                    trigger_error($className . '::__construct is public and could be instantiated as non-singleton.', E_USER_NOTICE);
                    // @codeCoverageIgnoreStart
                } // @codeCoverageIgnoreEnd
            }
        }

        return self::$instances[$className];
    }
}
