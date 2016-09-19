<?php

namespace YetAnotherWebStack\PhpMemcachedSession\Interfaces;

interface Model {

    /**
     *
     * @param string $sessionId
     * @param \YetAnotherWebStack\PhpMemcachedSession\Interfaces\Repository $repository
     */
    public function __construct($sessionId,
            \YetAnotherWebStack\PhpMemcachedSession\Interfaces\Repository $repository,
            \Psr\Log\LoggerInterface $logger);

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
}
