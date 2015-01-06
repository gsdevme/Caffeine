<?php

namespace Caffeine;

use Caffeine\Exception\InvalidEnvironmentException;
use RuntimeException;

class Runtime
{
    const CHANNEL = 'CAFFEINE_CHANNEL';
    const CONFIG  = 'CAFFEINE_CONFIG';
    const DEBUG   = 'CAFFEINE_DEBUG';



    /**
     * @return $this
     */
    public function bootstrap()
    {
        set_time_limit(0);
        ignore_user_abort(true);

        return $this;
    }

    /**
     * @return $this
     * @throws Exception\InvalidEnvironmentException
     */
    public function validateEnvironment()
    {
        switch(true){
            case (!isset($_SERVER[self::CHANNEL])):
                throw new InvalidEnvironmentException(self::CHANNEL);
            case (!isset($_SERVER[self::CONFIG])):
                throw new InvalidEnvironmentException(self::CONFIG);
            case (!isset($_SERVER[self::DEBUG])):
                throw new InvalidEnvironmentException(self::DEBUG);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function addErrorHandler()
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        return $this;
    }
}
