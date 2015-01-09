<?php

namespace Caffeine\Exception\Config;

use Exception;

class UsernameConfigurationException extends \Exception
{
    public function __construct()
    {
        parent::__construct('username must be defined in the config');
    }
}
