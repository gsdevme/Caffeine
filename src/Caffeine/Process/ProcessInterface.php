<?php

namespace Caffeine\Process;

interface ProcessInterface
{
    /**
     * @param array $environment
     * @return bool
     * @throws \RuntimeException
     */
    public function run(array $environment);

    /**
     * @return void
     */
    public function close();

    /**
     * @return int
     */
    public function getPid();

    /**
     * @return void
     */
    public function updateStatus();
}
