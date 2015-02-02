<?php

namespace Caffeine\Process\Runtime;

class CoreConfig
{

    private $username;
    private $oauth;
    private $channel;
    private $timezone;

    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->setTimezone(new \DateTimeZone($data['timezone']));
            $this->setChannel($data['channel']);
            $this->setOAuth($data['oauth']);
            $this->setUsername($data['username']);
        }
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getOAuth()
    {
        return $this->oauth;
    }

    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    /**
     * @param $oauth
     */
    public function setOAuth($oauth)
    {
        $this->oauth = $oauth;
    }

    /**
     * @param $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param $timezone
     */
    public function setTimezone(\DateTimeZone $timezone)
    {
        $this->timezone = $timezone;
    }

    public function toArray()
    {
        return [
            'username' => $this->getUsername(),
            'channel'  => $this->getChannel(),
            'oauth'    => $this->getOAuth(),
            'timezone' => $this->getTimezone()->getName()
        ];
    }
}
