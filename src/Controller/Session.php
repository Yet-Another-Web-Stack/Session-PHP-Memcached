<?php

namespace Idrinth\PhpMemcachedSession\Controller;

class Session implements \SessionHandlerInterface {

    /**
     *
     * @return boolean
     */
    public function close() {
        return true;
    }

    /**
     *
     * @return string
     */
    public function create_sid() {
        return sha1(mt_rand() . microtime() . getmypid() . ini_get('idrinth_session.sid_pepper'));
    }

    /**
     *
     * @param string $session_id
     */
    public function destroy($session_id) {
        return \Idrinth\PhpMemcachedSession\Model\Session::get($session_id)->delete();
    }

    /**
     *
     * @param int $maxlifetime
     * @return boolean
     */
    public function gc($maxlifetime) {
        return true;
    }

    /**
     *
     * @param string $save_path
     * @param string $name
     * @return boolean
     */
    public function open($save_path, $name) {
        ini_set('session.serialize_handler', 'php_serialize');
        return true;
    }

    /**
     *
     * @param string $session_id
     * @return string
     */
    public function read($session_id) {
        return \Idrinth\PhpMemcachedSession\Model\Session::get($session_id)->load();
    }

    /**
     *
     * @param string $session_id
     * @param string $session_data
     * @return boolean
     */
    public function write($session_id, $session_data) {
        return \Idrinth\PhpMemcachedSession\Model\Session::get($session_id)->save($session_data);
    }

}
