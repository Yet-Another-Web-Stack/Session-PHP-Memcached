<?php

namespace YetAnotherWebStack\PhpMemcachedSession\Interfaces;

interface Configuration {

    /**
     *
     * @param string $key
     * @return mixed
     */
    public function getGeneral(string $key);

    /**
     *
     * @param string $key
     * @return mixed
     */
    public function getSpecific(string $key);

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setGeneral(string $key, $value);

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setSpecific(string $key, $value);
}
