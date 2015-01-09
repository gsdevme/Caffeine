<?php

namespace Caffeine\Exception\Process;

class ProcessSpawnForkFailureException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Failed to proc_open() the runtime');
    }
}
