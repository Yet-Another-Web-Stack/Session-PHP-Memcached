<?php

namespace YetAnotherWebStack\PhpMemcachedSession\Service;

class DependencyInjector {

    /**
     *
     * @var \Auryn\Injector
     */
    private static $instance;

    /**
     * param string $id
     * @return \stdClass
     */
    public static function get($id, $arguments) {
        if (!self::$instance) {
            self::$instance = new \Auryn\Injector();
        }
        $object = self::$instance->make($id, $arguments);
        if (key_exists($id, self::$instance)) {
            self::$instance->share($object);
        }
        return $object;
    }

    /**
     *
     * @param string $interface
     * @param string $implementation
     * @param boolean $makeSingleton
     */
    public static function set($interface, $implementation,
            $makeSingleton = false) {
        if (!self::$instance) {
            self::$instance = new \Auryn\Injector();
        }
        self::$instance->alias($interface, $implementation);
        if ($makeSingleton) {
            self::$instance->share($interface);
        }
    }

}
