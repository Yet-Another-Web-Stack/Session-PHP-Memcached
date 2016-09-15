<?php

namespace Idrinth\PhpMemcachedSession\Model;

class Session {
    /**
     *
     * @var string
     */
    protected $agent;
    /**
     *
     * @var \Idrinth\PhpMemcachedSession\Repository\MemCache
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
    protected $id;
    /**
     *
     * @var string
     */
    protected $ipPart;
    /**
     *
     * @var \Idrinth\PhpMemcachedSession\Model\Session
     */
    protected static $instance;
    /**
     *
     * @param string $id
     */
    protected function __construct($id) {
        $this->id = $id;
        $this->agent = $this->getUserAgent();
        $this->ipPart = explode(strpos($_SERVER['REMOTE_ADDR'],'.')?'.':':',$_SERVER['REMOTE_ADDR'])[0];
        $this->repository = new \Idrinth\PhpMemcachedSession\Repository\MemCache();
    }
    /**
     *
     * @return string[]
     */
    protected function getKeys() {
        return [$this->agent,$this->ipPart,$this->id];
    }
    /**
     * stores the session
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
        if($data === $this->original) {
            return true;//nothing to change
        }
        return $this->repository->updateByKey($this->getKeys(),$data);
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
        if(strpos($agent,'Opera') || strpos($agent,'OPR/')) {
            return 'opera';
        }
        if(strpos($agent,'Edge')) {
            return 'edge';
        }
        if(strpos($agent,'Chrome')) {
            return 'chrome';
        }
        if(strpos($agent,'Safari')) {
            return 'safari';
        }
        if(strpos($agent,'Firefox')) {
            return 'firefox';
        }
        if(strpos($agent,'MSIE') || strpos($agent,'Trident/7')) {
            return 'internet explorer';
        }
        return trim(preg_replace('/(\/|\(|[0-9]|V[0-9]).*/i','',$agent));
    }
    /**
     *
     * @param string $id
     * @return \Idrinth\PhpMemcachedSession\Model\Session
     */
    public static function get($id) {
        if(!self::$instance) {
            self::$instance = new self($id);
        }
        return self::$instance;
    }
}