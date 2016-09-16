<?php

namespace YetAnotherWebStack\PhpMemcachedSession\Interfaces;

interface Configuration {

    /**
     *
     * @param string $key
     * @return mixed
     */
    public function getGeneral($key);

    /**
     *
     * @param string $key
     * @return mixed
     */
    public function getSpecific($key);

    /**
     *
     * @param string $key
     * @return mixed
     */
    public function setGeneral($key, $value);

    /**
     *
     * @param string $key
     * @return mixed
     */
    public function setSpecific($key, $value);
}
