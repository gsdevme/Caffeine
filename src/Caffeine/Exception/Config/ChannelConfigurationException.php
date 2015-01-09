<?php

namespace Caffeine\Exception\Config;

use Exception;

class ChannelConfigurationException extends \Exception
{
    public function __construct()
    {
        parent::__construct('channel must be defined in the config');
    }
}
