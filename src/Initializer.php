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
        self::setClasses($configuration,
                'YetAnotherWebStack\PhpMemcachedSession\Repository\MemCacheRead' . (
                $isReadOnly ? '' : 'Write'
        ));
        self::setSettings();
        call_user_func($callable);
        session_set_save_handler(
                Service\DependencyInjector::get(
                        'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Controller'
                )
        );
    }

    /**
     * sets the class name lookup
     * @param string $configuration name of the configuration class
     * @param string $repository name of the repository class
     */
    protected static function setClasses($configuration, $repository) {
        $mappings = [
            'Singleton' => [
                'Configuration' => $configuration,
                'Model' => 'YetAnotherWebStack\PhpMemcachedSession\Model\Session',
            ],
            'Regular' => [
                'Repository' => $repository,
                'Controller' => 'YetAnotherWebStack\PhpMemcachedSession\Controller\Session',
            ]
        ];
        foreach ($mappings as $type => $list) {
            foreach ($list as $interface => $implementation) {
                Service\DependencyInjector::{'set' . $type}(
                        'YetAnotherWebStack\PhpMemcachedSession\Interfaces\\' . $interface,
                        $implementation);
            }
        }
    }

    /**
     * sets the generally expected settings for the session.
     */
    protected static function setSettings() {
        $configuration = Service\DependencyInjector::get(
                        'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration'
        );
        $configuration->setGeneral('serialize_handler', 'php_serialize');
        $configuration->setGeneral('name', 'name');
        $configuration->setGeneral('use_cookies', 1);
        $configuration->setGeneral('use_only_cookies', 1);
    }

}
