<?php

namespace Caffeine\Console;

use Caffeine\Exception\Console\ProcessSpawnForkFailureException;
use Caffeine\Process\Process;
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
     * @return mixed
     * @throws \Caffeine\Exception\Console\ProcessSpawnForkFailureException
     */
    public static function create($channel, $config)
    {
        $process = new Process(__DIR__ . '/../bin/runtime &');

        try {
            $process->run([
                Runtime::CHANNEL => $channel,
                Runtime::DEBUG   => true,
                Runtime::CONFIG  => $config
            ]);

            $pid = $process->getPid();

            $process->close();

            return $pid;
        } catch (\RuntimeException $e) {

        }

        throw new ProcessSpawnForkFailureException();
    }
}
