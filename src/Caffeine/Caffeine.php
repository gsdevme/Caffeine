<?php

namespace Caffeine;

class Caffeine
{

    /**
     * This will ensure the application runs forever
     *
     * @param Config $config
     * @param bool $forever
     * @return mixed
     */
    public static function run(Config $config, $forever = true)
    {
        while(true){
            self::sleepForZeroPointZeroPointFiveSeconds();
        }

        if ($forever) {
            return self::run($config, $forever);
        }
    }

    /**
     * usleep for 50000
     */
    private static function sleepForZeroPointZeroPointFiveSeconds()
    {
        usleep(50000);
    }
}
