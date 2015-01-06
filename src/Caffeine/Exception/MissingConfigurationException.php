<?php

namespace Caffeine\Exception;

use Exception;

class MissingConfigurationException extends \Exception
{
    /**
     * @inheritdoc
     */
    public function __construct($config)
    {
        parent::__construct(sprintf('%s must be defined in the config', $config));
    }
}
