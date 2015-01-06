<?php

namespace Caffeine\Exception;

use Exception;

class InvalidEnvironmentException extends Exception
{
    /**
     * @inheritdoc
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct(sprintf('%s is missing from $_SERVER', $message), $code, $previous);
    }
}
