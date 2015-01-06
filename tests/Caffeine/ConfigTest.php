<?php

namespace Caffeine;

class ConfigTest extends \PHPUnit_Framework_TestCase
{

    public function testConfigurationWithoutUsername()
    {
        $this->setExpectedException(
            '\Caffeine\Exception\MissingConfigurationException',
            sprintf(Exception\MissingConfigurationException::EXCEPTION_MESSAGE, Config::CONFIG_USERNAME)
        );

        new Config([
            'oauth' => 1,
            'channel' => 1
        ]);
    }

    public function testConfigurationWithoutOAuth()
    {
        $this->setExpectedException(
            '\Caffeine\Exception\MissingConfigurationException',
            sprintf(Exception\MissingConfigurationException::EXCEPTION_MESSAGE, Config::CONFIG_OAUTH)
        );

        new Config([
            'username' => 1,
            'channel' => 1
        ]);
    }

    public function testConfigurationWithoutChannel()
    {
        $this->setExpectedException(
            '\Caffeine\Exception\MissingConfigurationException',
            sprintf(Exception\MissingConfigurationException::EXCEPTION_MESSAGE, Config::CONFIG_CHANNEL)
        );

        new Config([
            'oauth' => 1,
            'username' => 1
        ]);
    }

    /**
     * @dataProvider configurationBasicProvider
     */
    public function testConfigurationBasic($username, $oauth, $channel)
    {
        $config = new Config([
            Config::CONFIG_CHANNEL => $channel,
            Config::CONFIG_OAUTH => $oauth,
            Config::CONFIG_USERNAME => $username
        ]);

        $this->assertEquals($username, $config->getUsername());
        $this->assertEquals($oauth, $config->getOAuth());
        $this->assertEquals($channel, $config->getChannel());
    }

    public function configurationBasicProvider()
    {
        return [
            ['username', 'oauth', 'channel'],
            ['esfesfse', 'efesfs:fffsefesf', 'soda'],
            ['4444', '4444:fffsefesf', '44'],
        ];
    }
}
