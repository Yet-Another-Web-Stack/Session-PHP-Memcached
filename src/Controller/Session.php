<?php

namespace YetAnotherWebStack\PhpMemcachedSession\Controller;

class Session implements YetAnotherWebStack\PhpMemcachedSession\Interfaces\Controller {

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     *
     * @var \YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration
     */
    protected $configuration;

    /**
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration $configuration
     */
    public function __construct(\Psr\Log\LoggerInterface $logger,
            \YetAnotherWebStack\PhpMemcachedSession\Interfaces\Configuration $configuration) {
        $this->logger = $logger;
        $this->configuration = $configuration;
    }

    /**
     *
     * @return boolean
     */
    public function close() {
        $this->logger->debug("Trying to close the session...");
        return true;
    }

    /**
     *
     * @return string
     */
    public function create_sid() {
        $this->logger->debug("Creating new session id");
        return sha1(
                mt_rand() . microtime() . getmypid() .
                $this->configuration->getSpecific('sid_pepper')
        );
    }

    /**
     *
     * @param string $session_id
     */
    public function destroy($session_id) {
        $this->logger->debug("Destroying session");
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
        $this->logger->debug("Trying to clean sessions with $maxlifetime as lifetime");
        return true;
    }

    /**
     *
     * @param string $save_path
     * @param string $name
     * @return boolean
     */
    public function open($save_path, $name) {
        $this->logger->debug("Trying open a session with the following parameters: $save_path,$name");
        return true;
    }

    /**
     *
     * @param string $session_id
     * @return string
     */
    public function read($session_id) {
        $this->logger->debug("Trying read session $session_id");
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
        $this->logger->debug("Trying write to the session $session_id");
        return \YetAnotherWebStack\PhpMemcachedSession\Service\DependencyInjector::get(
                        'YetAnotherWebStack\PhpMemcachedSession\Interfaces\Model',
                        ['sessionId' => $session_id])->save($session_data);
    }

}
