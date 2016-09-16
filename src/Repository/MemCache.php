<?php

namespace YetAnotherWebStack\PhpMemcachedSession\Repository;

class MemCache implements \YetAnotherWebStack\PhpMemcachedSession\Interfaces\Repository {

    /**
     *
     * @var int
     */
    protected $duration = 3600;

    /**
     *
     * @var string[]
     */
    protected $prefix = ['yet-another-web-stack', 'memcached-session'];

    /**
     *
     * @var \Memcached
     */
    protected $memcache;

    /**
     * parma \Memcached $memcache
     */
    public function __construct(\Memcached $memcache) {
        $this->memcache = $memcache;
        $this->memcache->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
        if (\YetAnotherWebStack\PhpMemcachedSession\Service\DependencyInjector::get('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration')->getSpecific('memcache_user') && \YetAnotherWebStack\PhpMemcachedSession\Service\DependencyInjector::get('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration')->getSpecific('memcache_password')) {
            $this->memcache->setSaslAuthData(
                    \YetAnotherWebStack\PhpMemcachedSession\Service\DependencyInjector::get('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration')->getSpecific('memcache_user'),
                    \YetAnotherWebStack\PhpMemcachedSession\Service\DependencyInjector::get('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration')->getSpecific('memcache_password')
            );
        }
        $this->duration = DependencyInjector::get('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration')->getGeneral("gc_maxlifetime");
        if (count($this->memcache->getServerList()) == 0) {
            $this->memcache->addServer(
                    \YetAnotherWebStack\PhpMemcachedSession\Service\DependencyInjector::get('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration')->getSpecific('memcache_server'),
                    \YetAnotherWebStack\PhpMemcachedSession\Service\DependencyInjector::get('YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration')->getSpecific('memcache_port')
            );
        }
        if (!$this->duration) {
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
        return trim(
                implode('.', $this->prefix) . '.' . implode('.', $params), '.'
        );
    }

    /**
     *
     * @param array $params
     * @return string
     */
    public function getByKey(array $params) {
        while ($this->memcache->get($this->getKey($params) . '.locked')) {
            usleep(mt_rand(100, 1000));
        }
        $this->memcache->set($this->getKey($params) . '.locked', '1');
        $value = $this->memcache->get($this->getKey($params));
        if ($value) {
            $this->memcache->touch($this->getKey($params),
                    time() + $this->duration);
        }
        return $value;
    }

    /**
     *
     * @param string[] $params
     * @param string $value
     * @return boolean
     */
    public function setByKey(array $params, $value) {
        $this->memcache->delete($this->getKey($params) . '.locked');
        return $this->memcache->set($this->getKey($params), $value,
                        time() + $this->duration);
    }

    /**
     *
     * @param string[] $params
     * @return boolean
     */
    public function removeByKey(array $params) {
        $this->memcache->delete($this->getKey($params) . '.locked');
        return $this->memcache->delete($this->getKey($params));
    }

}
