<?php

namespace Caffeine\Console;

use Caffeine\Exception\Console\ProcessSpawnForkFailureException;
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
     * @throws \Caffeine\Exception\Console\ProcessSpawnForkFailureException
     */
    public static function create($channel, $config)
    {
        $process = self::processOpen($channel, $config);

        if (is_resource($process)) {
            $status = proc_get_status($process);

            proc_close($process);

            return $status;

        }

        throw new ProcessSpawnForkFailureException();
    }

    /**
     * @param $channel
     * @param $config
     * @param array $pipes
     * @return resource
     */
    private static function processOpen($channel, $config, array $pipes = [])
    {
        return proc_open(__DIR__ . '/../bin/runtime &', [
            ['pipe', 'r'],
            ['pipe', 'w'],
            ['pipe', 'w']
        ], $pipes, null, [
            Runtime::CHANNEL => $channel,
            Runtime::DEBUG   => true,
            Runtime::CONFIG  => $config
        ]);

    }
}
