<?php

namespace Caffeine\Exception;

use Exception;

class InvalidEnvironmentException extends Exception
{
    /**
     * @inheritdoc
     */
    public function __construct($environment)
    {
        parent::__construct(sprintf('%s is missing from $_SERVER', $environment));
    }
}
