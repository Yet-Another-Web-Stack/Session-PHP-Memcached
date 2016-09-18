<?php

namespace YetAnotherWebStack\PhpMemcachedSession\Repository;

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
        $status = $this->memcache->set($this->getKey($params), $value,
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
        $this->memcache->delete($this->getKey($params) . '.locked');
        return $this->memcache->delete($this->getKey($params));
    }

}
