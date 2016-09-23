<?php

namespace Org\YetAnotherWebStack\PhpMemcachedSession\Interfaces;

interface Repository {

    /**
     *
     * @param array $params
     * @return string
     */
    public function getByKey(array $params);

    /**
     *
     * @param string[] $params
     * @param string $value
     * @return boolean
     */
    public function setByKey(array $params, $value);

    /**
     *
     * @param string[] $params
     * @return boolean
     */
    public function removeByKey(array $params);
}
