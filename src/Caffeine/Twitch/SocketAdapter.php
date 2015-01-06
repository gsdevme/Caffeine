<?php

namespace Caffeine\Twitch;

/**
 * Class SocketAdapter
 *
 * @package Caffeine\Twitch
 */
class SocketAdapter
{

    private $socket;

    /**
     * @param Socket $socket
     */
    public function __construct(Socket $socket)
    {
        $this->socket = $socket;
    }

    /**
     * @param $buffer
     * @param null $length
     * @return bool
     */
    public function write($buffer, $length = null)
    {
        if ($length === null) {
            $length = strlen($buffer);
        }

        fwrite($this->socket->getSocket(), $buffer, $length);

        return true;
    }

    /**
     * @return string
     */
    public function read()
    {
        return fgets($this->socket->getSocket(), 1024);
    }
}
