<?php

namespace YetAnotherWebStack\PhpMemcachedSession\Model;

class Session implements YetAnotherWebStack\PhpMemcachedSession\Interfaces\Model {

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
     * @param string $sessionId
     * @param \YetAnotherWebStack\PhpMemcachedSession\Interfaces\Repository $repository
     */
    protected function __construct($sessionId,
            \YetAnotherWebStack\PhpMemcachedSession\Interfaces\Repository $repository) {
        $this->sessionId = $sessionId;
        $this->agent = $this->getUserAgent();
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

}
