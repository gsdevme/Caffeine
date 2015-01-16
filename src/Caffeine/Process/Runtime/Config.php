<?php

namespace Caffeine\Process\Runtime;

class Config
{

    const CONFIG_CHANNEL  = 'channel';
    const CONFIG_USERNAME = 'username';
    const CONFIG_OAUTH    = 'oauth';

    private $config;

    /**
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config)
    {
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->config[self::CONFIG_CHANNEL];
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->config[self::CONFIG_USERNAME];
    }

    /**
     * @return string
     */
    public function getOAuth()
    {
        return $this->config[self::CONFIG_OAUTH];
    }
}
