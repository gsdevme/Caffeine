<?php

namespace Caffeine\Process;

class RuntimeProcess extends Process
{
    const COMMAND = '%s/../bin/runtime &';

    public function __construct()
    {
        $this->command = sprintf(self::COMMAND, __DIR__);
        $this->pipes   = [];
    }
}
