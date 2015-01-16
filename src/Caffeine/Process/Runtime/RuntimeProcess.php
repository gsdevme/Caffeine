<?php

namespace Caffeine\Process\Runtime;

class RuntimeProcess extends \Caffeine\Process\Process
{
    const COMMAND = '%s/../bin/runtime &';

    public function __construct()
    {
        $this->command = sprintf(self::COMMAND, __DIR__);
        $this->pipes   = [];
    }
}
