<?php

namespace ck_framework\Session;

use ck_framework\Utils\SnippetUtils;

class FlashService
{
    /**
     * @var SessionInterface
     */
    private $session;

    private $sessionKey = 'flash';

    private $messages;

    /**
     * FlashService constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * create success flash
     * @param string $message
     */
    public function success(string $message)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['success'] = $message;
        $this->session->set($this->sessionKey, $flash);
    }

    /**
     * create error flash
     * @param string $message
     */
    public function error(string $message)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['error'] = $message;
        $this->session->set($this->sessionKey, $flash);
    }


    public function warning(string $message)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['warning'] = $message;
        $this->session->set($this->sessionKey, $flash);
    }

    /**
     * get flash message
     * @param string $type
     * @return string|null
     */
    public function get(string $type): ?string
    {
        if (is_null($this->messages)) {
            $this->messages = $this->session->get($this->sessionKey, []);
            $this->session->delete($this->sessionKey);
        }
        if (array_key_exists($type, $this->messages)) {
            return $this->messages[$type];
        }
        return null;
    }

}
