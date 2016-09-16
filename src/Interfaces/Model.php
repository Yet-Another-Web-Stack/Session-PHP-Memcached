<?php

namespace YetAnotherWebStack\PhpMemcachedSession\Interfaces;

interface Model {

    /**
     *
     * @param string $sessionId
     * @param \YetAnotherWebStack\PhpMemcachedSession\Interfaces\Repository $repository
     */
    public function __construct($sessionId, $repository);

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
    public function save($data);

    /**
     * deletes the current data and instance
     */
    public function delete();

    /**
     *
     * @param string $sessionId
     * @return \YetAnotherWebStack\PhpMemcachedSession\Model\Session
     */
    public static function get($sessionId);
}
