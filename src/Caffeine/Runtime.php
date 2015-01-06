<?php

namespace Caffeine;

use RuntimeException;

class Runtime
{
    const CHANNEL = 'CAFFEINE_CHANNEL';
    const CONFIG  = 'CAFFEINE_CONFIG';
    const DEBUG   = 'CAFFEINE_DEBUG';

    const ISSET_EXCEPTION = '%s is missing from $_SERVER';

    public function bootstrap()
    {
        set_time_limit(0);
        ignore_user_abort(true);

        return $this;
    }

    public function environment()
    {
        switch(true){
            case (!isset($_SERVER[self::CHANNEL])):
                throw new RuntimeException(sprintf(self::ISSET_EXCEPTION, self::CHANNEL));
            case (!isset($_SERVER[self::CONFIG])):
                throw new RuntimeException(sprintf(self::ISSET_EXCEPTION, self::CONFIG));
            case (!isset($_SERVER[self::DEBUG])):
                throw new RuntimeException(sprintf(self::ISSET_EXCEPTION, self::DEBUG));
        }

        return $this;
    }
}
