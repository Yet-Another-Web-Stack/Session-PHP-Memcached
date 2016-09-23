<?php

namespace Org\YetAnotherWebStack\PhpMemcachedSession\Service;

class Configuration implements \Org\YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration {

    /**
     *
     * @var (int|string)[]
     */
    private static $defaults = [
        'memcache_server' => 'localhost',
        'memcache_port' => 11211,
        'sid_pepper' => 'this is not quite secret',
    ];

    /**
     *
     * @var the prefix for module specific settings
     */
    private static $iniPrefix = 'yetanotherwebstack_session';

    /**
     *
     * @param string $key
     * @return mixed
     */
    public function getGeneral($key) {
        return ini_get('session.' . $key);
    }

    /**
     *
     * @param string $key
     * @return mixed
     */
    public function getSpecific($key) {
        $value = ini_get(self::$iniPrefix . '.' . $key);
        if (!$value && !isset(self::$defaults[$key])) {
            return null;
        }
        return self::$defaults[$key];
    }

    /**
     *
     * @param string $key
     * @return mixed
     */
    public function setGeneral($key, $value) {
        return ini_set('session.' . $key, $value);
    }

    /**
     *
     * @param string $key
     * @return mixed
     */
    public function setSpecific($key, $value) {
        return ini_set(self::$iniPrefix . '.' . $key, $value);
    }

}
