<?php

namespace Caffeine;

class Config
{

    private $config;

    /**
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        if (!isset($config['channel'], $config['username'], $config['auth'])) {
            throw new \Exception('Channel, Username & Auth must be defined in the config');
        }

        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->config['channel'];
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->config['username'];
    }

    /**
     * @return string
     */
    public function getAuth()
    {
        return $this->config['auth'];
    }

    /**
     * @return string|null
     */
    public function getLoginMessage()
    {
        return (isset($this->config['login-message'])) ? $this->config['login-message'] : null;
    }

    /**
     * @return bool
     */
    public function isMod()
    {
        return (isset($this->config['mod'])) ? $this->config['mod'] : false;
    }
}
