<?php

namespace Caffeine;

use Caffeine\Exception\Config\ChannelConfigurationException;
use Caffeine\Exception\Config\OAuthConfigurationException;
use Caffeine\Exception\Config\UsernameConfigurationException;

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
                throw new ChannelConfigurationException();
            case (!isset($config[self::CONFIG_USERNAME])):
                throw new UsernameConfigurationException();
            case (!isset($config[self::CONFIG_OAUTH])):
                throw new OAuthConfigurationException();
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
