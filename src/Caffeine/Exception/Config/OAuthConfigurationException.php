<?php

namespace Caffeine\Exception\Config;

use Exception;

class OAuthConfigurationException extends \Exception
{
    public function __construct()
    {
        parent::__construct('oauth must be defined in the config');
    }
}
