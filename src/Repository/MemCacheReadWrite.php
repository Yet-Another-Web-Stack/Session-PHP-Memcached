<?php

namespace Org\YetAnotherWebStack\PhpMemcachedSession\Repository;

class MemCacheReadWrite extends MemCacheRead {

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
        return parent::getByKey($params);
    }

    /**
     *
     * @param string[] $params
     * @param string $value
     * @return boolean
     */
    public function setByKey(array $params, $value) {
        $this->logger->debug("setting $value at " . implode('.', $params));
        $serializer = $this->configuration->getSpecific('serializer');
        $status = $this->memcache->set($this->getKey($params),
                $serializer ?
                        call_user_func($serializer, unserialize($value)) : $value,
                time() + $this->duration);
        $this->memcache->delete($this->getKey($params) . '.locked');
        return $status;
    }

    /**
     *
     * @param string[] $params
     * @return boolean
     */
    public function removeByKey(array $params) {
        $this->logger->debug("deleting " . implode('.', $params));
        $this->memcache->delete($this->getKey($params) . '.locked');
        return $this->memcache->delete($this->getKey($params));
    }

}
