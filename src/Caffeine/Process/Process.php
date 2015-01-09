<?php

namespace Caffeine\Process;

use Caffeine\Exception\Console\ProcessSpawnForkFailureException;

/**
 * Had some odd issue with the Symfony process so just quickly made my own
 *
 * @package Caffeine\Process
 */
class Process
{
    const STATUS_PID = 'pid';

    private $command;
    private $resource;
    private $pipes;
    private $status;

    /**
     * @param string $command
     */
    public function __construct($command)
    {
        $this->command = $command;
        $this->pipes   = [];
    }

    /**
     * @param array $environment
     * @return bool
     * @throws \RuntimeException
     */
    public function run(array $environment)
    {
        $this->resource = proc_open($this->command, [
            ['pipe', 'r'],
            ['pipe', 'w'],
            ['pipe', 'w']
        ], $this->pipes, null, $environment);

        if (is_resource($this->resource)) {
            $this->updateStatus();

            return true;
        }

        throw new \RuntimeException('proc_open failed to create a resource');
    }

    public function close()
    {
        if (is_resource($this->resource)) {
            proc_close($this->resource);
        }
    }

    /**
     * @return int
     */
    public function getPid()
    {
        if ($this->status) {
            return (int)$this->status[self::STATUS_PID];
        }
    }

    public function updateStatus()
    {
        $this->status = proc_get_status($this->resource);
    }

}
