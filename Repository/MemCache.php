<?php

namespace Idrinth\PhpMemcachedSession\Repository;

class MemCache {
    /**
     *
     * @var int
     */
    protected $duration = 3600;
    /**
     *
     * @var string[]
     */
    protected $prefix = array('idrinth','memcached-session');
    /**
     *
     * @var \Memcached
     */
    protected $memcache;
    /**
     * @return \Idrinth\PhpMemcachedSession\Repository\MemCache
     */
    public function __construct() {
        $this->memcache = new \Memcached();
        $this->duration = ini_get("session.gc_maxlifetime");
        if(count($this->memcache->getServerList()) == 0) {
            $this->memcache->addServer(ini_get('idrinth_session.memcache_server'),ini_get('idrinth_session.memcache_port'));
        }
        if(!$this->duration) {
            $this->duration = 3600;
        }
        return $this;
    }
    /**
     *
     * @param string[] $params
     * @return string
     */
    protected function getKey($params = array()) {
        return trim(implode('.',$this->prefix) . '.' . implode('.',$params),'.');
    }
    /**
     *
     * @param array $params
     * @return string
     */
    public function getByKey(array $params) {
        $value = $this->memcache->get($this->getKey($params));
        if($value) {
            $this->memcache->touch($this->getKey($params),time() + $this->duration);
        }
        return $value;
    }
    /**
     *
     * @param string[] $params
     * @param string $value
     * @return boolean
     */
    public function setByKey(array $params,$value) {
        return $this->memcache->set($this->getKey($params),$value,time() + $this->duration);
    }
    /**
     *
     * @param string[] $params
     * @return boolean
     */
    public function removeByKey(array $params) {
        return $this->memcache->set($this->getKey($params),'',1);
    }
}