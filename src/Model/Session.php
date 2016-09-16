<?php

namespace YetAnotherWebStack\PhpMemcachedSession\Model;

class Session {

    /**
     *
     * @var string
     */
    protected $agent;

    /**
     *
     * @var \YetAnotherWebStack\PhpMemcachedSession\Repository\MemCache
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
     * @var \YetAnotherWebStack\PhpMemcachedSession\Model\Session
     */
    protected static $instance;

    /**
     *
     * @param string $sessionId
     */
    protected function __construct($sessionId) {
        $this->sessionId = $sessionId;
        $this->agent = $this->getUserAgent();
        $this->ipPart = explode(strpos($_SERVER['REMOTE_ADDR'], '.') ? '.' : ':', $_SERVER['REMOTE_ADDR'])[0];
        $this->repository = new \YetAnotherWebStack\PhpMemcachedSession\Repository\MemCache();
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
    protected function __destruct() {
        $this->save(serialize($_SESSION));
    }

    /**
     *
     * @return string a serialized string
     */
    public function load() {
        $this->original = $this->getByKey($this->getKeys()) . '';
        return $this->original;
    }

    /**
     *
     * @param string $data
     * @return boolean was it saved?
     */
    public function save($data) {
        if ($data === $this->original) {
            return true; //nothing to change
        }
        return $this->repository->updateByKey($this->getKeys(), $data);
    }

    /**
     * deletes the current data and instance
     */
    public function delete() {
        $this->repository->removeByKey($this->getKeys());
        self::$instance = null;
    }

    /**
     *
     * @return string
     */
    private function getUserAgent() {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($agent, 'Opera') || strpos($agent, 'OPR/')) {
            return 'opera';
        }
        if (strpos($agent, 'Edge')) {
            return 'edge';
        }
        if (strpos($agent, 'Chrome')) {
            return 'chrome';
        }
        if (strpos($agent, 'Safari')) {
            return 'safari';
        }
        if (strpos($agent, 'Firefox')) {
            return 'firefox';
        }
        if (strpos($agent, 'MSIE') || strpos($agent, 'Trident/7')) {
            return 'internet explorer';
        }
        return trim(preg_replace('/(\/|\(|[0-9]|V[0-9]).*/i', '', $agent));
    }

    /**
     *
     * @param string $sessionId
     * @return \YetAnotherWebStack\PhpMemcachedSession\Model\Session
     */
    public static function get($sessionId) {
        if (!self::$instance) {
            self::$instance = new self($sessionId);
        }
        return self::$instance;
    }

}
