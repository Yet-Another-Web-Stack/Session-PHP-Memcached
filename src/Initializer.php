<?php

namespace YetAnotherWebStack\PhpMemcachedSession;

class Initializer {

    /**
     * initialises defaults, calls the callable and then sets the handler
     * @param callable $callable
     * @param string $configuration class name of the configuration class to use
     * @param boolean $isReadOnly
     */
    public static function run(callable $callable,
            $configuration = 'YetAnotherWebStack\PhpMemcachedSession\Service\Configuration',
            $isReadOnly = false
    ) {
        Service\DependencyInjector::set(
                'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration',
                $configuration, true);

        self::setSettings();
        self::setClasses($isReadOnly);
        call_user_func($callable);
        session_set_save_handler(
                Service\DependencyInjector::get(
                        'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Controller'
                )
        );
    }

    /**
     * sets the class name lookup
     * @param boolean $isReadOnly
     */
    protected static function setClasses($isReadOnly = false) {
        Service\DependencyInjector::set(
                'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Repository',
                ($isReadOnly ?
                        'YetAnotherWebStack\PhpMemcachedSession\Repository\MemCacheRead' :
                        'YetAnotherWebStack\PhpMemcachedSession\Repository\MemCacheReadWrite'
                )
        );
        Service\DependencyInjector::set(
                'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Model',
                'YetAnotherWebStack\PhpMemcachedSession\Model\Session', true);
        Service\DependencyInjector::set(
                'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Controller',
                'YetAnotherWebStack\PhpMemcachedSession\Controller\Session',
                true);
    }

    /**
     * sets the generally expected settings for the session. files
     */
    protected static function setSettings() {
        Service\DependencyInjector::get(
                'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration'
        )->setGeneral('serialize_handler', 'php_serialize');
        Service\DependencyInjector::get(
                'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration'
        )->setGeneral('name', 'name');
        Service\DependencyInjector::get(
                'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration'
        )->setGeneral('use_cookies', 1);
        Service\DependencyInjector::get(
                'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration'
        )->setGeneral('use_only_cookies', 1);
    }

}
