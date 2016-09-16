<?php

namespace YetAnotherWebStack\PhpMemcachedSession;

class Initializer {

    /**
     * initialises defaults, calls the callable and then sets the handler
     * @param callable $callable
     * @param string $configuration class name of the configuration class to use
     */
    public static function run(callable $callable,
            $configuration = 'YetAnotherWebStack\PhpMemcachedSession\Service\Configuration'
    ) {
        DependencyInjector::set('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration',
                $configuration, true);

        DependencyInjector::get('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration')->setGeneral('serialize_handler',
                'php_serialize'); //more useful - could be used outside php
        DependencyInjector::get('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration')->setGeneral('name',
                'session'); //not obviously php
        DependencyInjector::get('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration')->setGeneral('use_cookies',
                1);
        DependencyInjector::get('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration')->setGeneral('use_only_cookies',
                1);

        DependencyInjector::set('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Repository',
                'YetAnotherWebStack\PhpMemcachedSession\Repository\MemCache');
        DependencyInjector::set('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Model',
                'YetAnotherWebStack\PhpMemcachedSession\Model\Session', true);
        DependencyInjector::set('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Controller',
                'YetAnotherWebStack\PhpMemcachedSession\Controller\Session',
                true);


        call_user_func($callable);
        session_set_save_handler(DependencyInjector::get('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Controller'));
    }

}
