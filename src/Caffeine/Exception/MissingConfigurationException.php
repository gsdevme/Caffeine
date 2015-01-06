<?php

namespace Caffeine\Exception;

use Exception;

class MissingConfigurationException extends \Exception
{
    const EXCEPTION_MESSAGE = '%s must be defined in the config';

    /**
     * @inheritdoc
     */
    public function __construct($config)
    {
        parent::__construct(sprintf(self::EXCEPTION_MESSAGE, $config));
    }
}
