<?php

namespace YetAnotherWebStack\PhpMemcachedSession\Controller;

class Session implements YetAnotherWebStack\PhpMemcachedSession\Interfaces\Controller {

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
        return sha1(
                mt_rand() . microtime() . getmypid() .
                \YetAnotherWebStack\PhpMemcachedSession\Service\DependencyInjector::get(
                        'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration'
                )->getSpecific('sid_pepper')
        );
    }

    /**
     *
     * @param string $session_id
     */
    public function destroy($session_id) {
        return \YetAnotherWebStack\PhpMemcachedSession\Service\DependencyInjector::get(
                        'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Model',
                        [':sessionId' => $session_id])->delete();
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
        return true;
    }

    /**
     *
     * @param string $session_id
     * @return string
     */
    public function read($session_id) {
        return \YetAnotherWebStack\PhpMemcachedSession\Service\DependencyInjector::get(
                        'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Model',
                        ['sessionId' => $session_id])->load();
    }

    /**
     *
     * @param string $session_id
     * @param string $session_data
     * @return boolean
     */
    public function write($session_id, $session_data) {
        return \YetAnotherWebStack\PhpMemcachedSession\Service\DependencyInjector::get(
                        'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Model',
                        ['sessionId' => $session_id])->save($session_data);
    }

}
