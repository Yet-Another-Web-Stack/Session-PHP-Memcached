<?php

namespace Org\YetAnotherWebStack\PhpMemcachedSession\Repository;

class MemCacheRead implements \Org\YetAnotherWebStack\PhpMemcachedSession\Interfaces\Repository {

    /**
     *
     * @var int
     */
    protected $duration = 3600;

    /**
     *
     * @var string[]
     */
    protected $prefix = ['org', 'yet-another-web-stack', 'memcached-session'];

    /**
     *
     * @var \Memcached
     */
    protected $memcache;

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     *
     * @var \Org\YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration
     */
    protected $configuration;

    /**
     * parma \Memcached $memcache
     */
    public function __construct(\Memcached $memcache,
            \Psr\Log\LoggerInterface $logger,
            \Org\YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration $configuration) {
        $this->memcache = $memcache;
        $this->logger = $logger;
        $this->configuration = $configuration;
        $this->memcache->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
        $this->setMemcacheLogin();
        $this->initializeServer();
        $this->duration = $configuration->getGeneral("gc_maxlifetime");
        if (!$this->duration) {
            $this->duration = 3600;
        }
        return $this;
    }

    /**
     * add login data if provided
     */
    protected function setMemcacheLogin() {
        if ($this->configuration->getSpecific('memcache_user') &&
                $this->configuration->getSpecific('memcache_password')) {
            $this->memcache->setSaslAuthData(
                    $this->configuration->getSpecific('memcache_user'),
                    $this->configuration->getSpecific('memcache_password')
            );
        }
    }

    /**
     * initializes a new server if necessary
     */
    protected function initializeServer() {
        if (count($this->memcache->getServerList()) == 0) {
            $this->memcache->addServer(
                    $this->configuration->getSpecific('memcache_server'),
                    $this->configuration->getSpecific('memcache_port')
            );
        }
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
        $value = $this->memcache->get($this->getKey($params));
        if ($value) {
            $this->memcache->touch($this->getKey($params),
                    time() + $this->duration);
        }
        $unserializer = $this->configuration->getSpecific('unserializer');
        if ($unserializer && is_callable($unserializer)) {
            $this->logger->debug("re-serializing value of " . implode('.',
                            $params));
            $value = serialize(call_user_func($unserializer, $value));
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
        $this->logger->debug("will not set value $value at " . implode('.',
                        $params));
        return false;
    }

    /**
     *
     * @param string[] $params
     * @return boolean
     */
    public function removeByKey(array $params) {
        $this->logger->debug("will not delete " . implode('.', $params));
        return false;
    }

}
