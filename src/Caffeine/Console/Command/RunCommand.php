<?php

namespace Caffeine\Console\Command;

use Caffeine;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console;

/**
 * Class Command
 * @package Caffeine
 */
class RunCommand extends Command
{
    private $runtimeProcess;
    private $processService;

    public function __construct($name, Caffeine\Process\RuntimeProcess $runtime, Caffeine\Process\ProcessService $processService)
    {
        $this->runtimeProcess = $runtime;
        $this->processService = $processService;

        parent::__construct($name);
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setDescription('Spawns a Caffeine bot into the background.')
            ->addArgument(
                'config',
                InputArgument::REQUIRED,
                'Twitch Channel'
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$pid = $this->createProcess('azimiwow', $config, $output);

        //$this->writeInfo($output, ' -Process Spawned, PID: ' . $pid);

        //$this->processService->handle($this->runtimeProcess, $channel, $config);
    }
}
