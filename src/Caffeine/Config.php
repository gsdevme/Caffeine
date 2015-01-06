<?php

namespace Caffeine;

use Caffeine\Exception\MissingConfigurationException;

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
        switch (true) {
            case (!isset($config[self::CONFIG_CHANNEL])):
                throw new MissingConfigurationException(self::CONFIG_CHANNEL);
            case (!isset($config[self::CONFIG_USERNAME])):
                throw new MissingConfigurationException(self::CONFIG_USERNAME);
            case (!isset($config[self::CONFIG_OAUTH])):
                throw new MissingConfigurationException(self::CONFIG_OAUTH);
        }

        $this->config = $config;
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
