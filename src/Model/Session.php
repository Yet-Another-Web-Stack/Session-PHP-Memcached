<?php

namespace YetAnotherWebStack\PhpMemcachedSession\Model;

class Session implements \YetAnotherWebStack\PhpMemcachedSession\Interfaces\Model {

    /**
     *
     * @var string
     */
    protected $agent;

    /**
     *
     * @var \YetAnotherWebStack\PhpMemcachedSession\Interfaces\Repository
     */
    protected $repository;

    /**
     *
     * @var string
     */
    protected $original = '';

    /**
     *
     * @var string
     */
    protected $sessionId;

    /**
     *
     * @var string
     */
    protected $ipPart;

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     *
     * @var boolean
     */
    protected $wasStored = false;

    /**
     *
     * @param string $sessionId
     * @param \YetAnotherWebStack\PhpMemcachedSession\Interfaces\Repository $repository
     */
    public function __construct($sessionId,
            \YetAnotherWebStack\PhpMemcachedSession\Interfaces\Repository $repository,
            \Psr\Log\LoggerInterface $logger) {
        $this->logger = $logger;
        $this->sessionId = $sessionId;
        $this->agent = md5($_SERVER['HTTP_USER_AGENT']);
        $this->ipPart = explode(strpos($_SERVER['REMOTE_ADDR'], '.') ? '.' : ':',
                        $_SERVER['REMOTE_ADDR'])[0];
        $this->repository = $repository;
    }

    /**
     *
     * @return string[]
     */
    protected function getKeys() {
        return [$this->agent, $this->ipPart, $this->sessionId];
    }

    /**
     * stores the sessionId
     */
    public function __destruct() {
        $this->save(serialize($_SESSION));
        $this->logger->debug("Saved session");
    }

    /**
     *
     * @return string a serialized string
     */
    public function load() {
        $this->original = $this->repository->getByKey($this->getKeys()) . '';
        $this->logger->debug("Loading session");
        return $this->original;
    }

    /**
     *
     * @param string $data
     * @return boolean was it saved?
     */
    public function save($data) {
        if ($this->wasStored) {
            $this->logger->debug("Already saved, nothing to store");
            return;
        }
        $this->wasStored;
        if ($data === $this->original) {
            $this->logger->debug("Session unchanged, nothing to store");
            return true; //nothing to change
        }
        $this->logger->debug("Session changed, storing");
        return $this->repository->setByKey($this->getKeys(), $data);
    }

    /**
     * deletes the current data and instance
     */
    public function delete() {
        $this->logger->debug("Deleting session");
        $this->repository->removeByKey($this->getKeys());
        self::$instance = null;
    }

}
