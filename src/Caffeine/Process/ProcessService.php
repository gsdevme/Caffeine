<?php

namespace Caffeine\Process;

use Caffeine\Exception\Process\ProcessSpawnForkFailureException;
use Caffeine\Runtime;

/**
 * Creates a process, forks it, then gets information then closes it.
 *
 * Class ProcessSpawnForkFactory
 * @package Caffeine\Console
 */
class ProcessService
{
    /**
     * @param ProcessInterface $process
     * @param $channel
     * @param $config
     * @param bool $debug
     * @return int
     * @throws \Caffeine\Exception\Process\ProcessSpawnForkFailureException
     */
    public static function handle(ProcessInterface $process, $channel, $config, $debug = true)
    {
        try {
            $process->run([
                Runtime::CHANNEL => $channel,
                Runtime::DEBUG   => $debug,
                Runtime::CONFIG  => $config
            ]);

            $pid = $process->getPid();

            $process->close();

            return $pid;
        } catch (\RuntimeException $e) {
            // Failed to create, fall through to below exception
        }

        throw new ProcessSpawnForkFailureException();
    }
}
