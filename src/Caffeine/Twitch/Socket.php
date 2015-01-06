<?php

namespace Caffeine\Twitch;

/**
 * Class Socket
 *
 * Wraps fsockopen for type hinting
 *
 * @package Caffeine\Twitch
 * @codeCoverageIgnore
 */
class Socket
{

    private $resource;
    private $config;

    /**
     * This will always ensure we get a nice clean disconnect
     *
     * @param Socket $socket
     */
    public static function registerShutdownFunction(Socket $socket)
    {
        register_shutdown_function(function () use ($socket) {
            $socket->disconnect();
            unset($socket);
        });
    }

    /**
     * @param $hostname
     * @param int $port
     * @param int $timeout
     * @throws \Exception
     */
    public function __construct($hostname, $port = -1, $timeout = 1200)
    {
        $this->config = (object)[
            'hostname' => $hostname,
            'port'     => $port,
            'timeout'  => $timeout
        ];

        $this->connect();
    }

    /**
     * @throws \Exception
     */
    private function connect()
    {
        $errstr = null;
        $errno  = null;

        try {
            $this->resource = fsockopen($this->config->hostname, $this->config->port, $errno, $errstr);
            stream_set_blocking($this->resource, 0);
        } catch (\ErrorException $e) {
            throw new \Exception('fsockopen failed to connect. Error: ' . $errstr . ' / Error Number: ' . $errno, 0, $e);
        }
    }

    /**
     * @return Resource
     */
    public function getSocket()
    {
        return $this->resource;
    }

    public function __destruct()
    {
        $this->disconnect();
    }


    public function disconnect()
    {
        fclose($this->resource);
    }
}
