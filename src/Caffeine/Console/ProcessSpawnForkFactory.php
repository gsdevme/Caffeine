<?php

namespace Caffeine\Console;

use Caffeine\Exception\Console\ProcessSpawnForkFailureException;
use Caffeine\Runtime;
use Symfony\Component\Process\Process;

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
     * @return int|null
     * @throws \Caffeine\Exception\Console\ProcessSpawnForkFailureException
     */
    public static function create($channel, $config)
    {
        $process = new Process(__DIR__ . '/../bin/runtime &');
        $process->setEnv([
            Runtime::CHANNEL => $channel,
            Runtime::DEBUG   => true,
            Runtime::CONFIG  => $config
        ]);

        $process->start();

        if($process->isRunning()){
            $pid = $process->getPid();

            $process->stop(0);

            return $pid;
        }

        throw new ProcessSpawnForkFailureException();
    }
}
