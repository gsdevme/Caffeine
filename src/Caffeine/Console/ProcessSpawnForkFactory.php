<?php

namespace Caffeine\Console;

use Caffeine\Runtime;

/**
 * Creates a process, forks it, then gets information then closes it.
 *
 * Class ProcessSpawnForkFactory
 * @package Caffeine\Console
 */
class ProcessSpawnForkFactory
{

    /**
     * @param $channel
     * @param $config
     * @return array
     * @throws \RuntimeException
     */
    public static function create($channel, $config)
    {
        $pipes = [];

        $process = proc_open(__DIR__ . '/../bin/runtime &', [
            ['pipe', 'r'],
            ['pipe', 'w'],
            ['pipe', 'w']
        ], $pipes, null, [
            Runtime::CHANNEL => $channel,
            Runtime::DEBUG   => true,
            Runtime::CONFIG  => $config
        ]);

        if (is_resource($process)) {
            $status = proc_get_status($process);

            proc_close($process);

            return $status;

        }

        throw new \RuntimeException('Could not create runtime');
    }
}
