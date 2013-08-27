<?php

namespace AtomicPHP\Utilities;

use \InvalidArgumentException;

/**
 * UnregisterableCallback
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package AtomicPHP\Utilities
 **/
class UnregisterableCallback
{
    /**
     * The callable function or class method
     *
     * @access protected
     * @var    callable
     **/
    protected $callback;

    /**
     * __construct
     *
     * Creates a new instance of UnregisterableCallback
     *
     * @access public
     * @param  callable $callback
     * @return UnregisterableCallback
     * @throws InvalidArgumentException
     **/
    public function __construct($callback)
    {
        if (!is_callable($callback) ) {
            throw new InvalidArgumentException("The argument is not callable callback");
        }

        $this->callback = $callback;
    }

    /**
     * call
     *
     * The call handler method. Use this method to call the callback
     *
     * @access public
     * @return mixed
     **/
    public function call()
    {
        if ($this->callback == null) {
            return;
        }

        $callback = $this->callback;

        return $callback();
    }

    /**
     * unregister
     *
     * Unregisters the callback
     *
     * @access public
     * @return void
     **/
    public function unregister()
    {
        $this->callback = null;
    }
}
