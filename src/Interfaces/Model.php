<?php

namespace YetAnotherWebStack\PhpMemcachedSession\Interfaces;

interface Model {

    /**
     *
     * @param string $sessionId
     * @param \YetAnotherWebStack\PhpMemcachedSession\Interfaces\Repository $repository
     */
    public function __construct(string $sessionId,
            \YetAnotherWebStack\PhpMemcachedSession\Interfaces\Repository $repository);

    /**
     *
     * @return string a serialized string
     */
    public function load();

    /**
     *
     * @param string $data
     * @return boolean was it saved?
     */
    public function save(string $data);

    /**
     * deletes the current data and instance
     */
    public function delete();
}
