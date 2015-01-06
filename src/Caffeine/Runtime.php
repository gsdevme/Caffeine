<?php

namespace Caffeine;

class Runtime
{
    const CHANNEL = 'CAFFEINE_CHANNEL';

    public function bootstrap()
    {
        set_time_limit(0);
        ignore_user_abort(true);

        return $this;
    }

    public function environment()
    {
        var_dump($_SERVER);

        return $this;
    }
}
