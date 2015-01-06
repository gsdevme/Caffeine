<?php

namespace Caffeine\Console;

use Caffeine\Runtime;

class ProcessSpawnFactory
{

    public static function create($channel)
    {
        $pipes = [];

        $process = proc_open(__DIR__ . '/../../bin/runtime', [
            ['pipe', 'r'],
            ['pipe', 'w'],
            ['pipe', 'w']
        ], $pipes, null, [
            Runtime::CHANNEL => $channel
        ]);

        sleep(1);

        foreach($pipes as $p){
            fpassthru($p);
        }

        proc_close($process);
    }
}
