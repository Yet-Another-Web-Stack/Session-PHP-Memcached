<?php

namespace YetAnotherWebStack\PhpMemcachedSession\Service;

class DependencyInjector {

    /**
     *
     * @var \Auryn\Injector
     */
    private static $instance;

    /**
     * param string $interface
     * @return \stdClass
     */
    public static function get($interface, $arguments = array()) {
        if (!self::$instance) {
            self::$instance = new \Auryn\Injector();
        }
        return self::$instance->make($interface, $arguments);
    }

    /**
     * sets a class to be newly instanciated when required
     * @param string $interface
     * @param string $implementation
     */
    public static function setRegular($interface, $implementation) {
        if (!self::$instance) {
            self::$instance = new \Auryn\Injector();
        }
        self::$instance->alias($interface, $implementation);
    }

    /**
     * Sets a class to be treated as a singleton
     * @param string $interface
     * @param string $implementation
     */
    public static function setSingleton($interface, $implementation) {
        if (!self::$instance) {
            self::$instance = new \Auryn\Injector();
        }
        self::$instance->alias($interface, $implementation);
        self::$instance->share($interface);
    }

}
