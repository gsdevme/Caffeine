<?php

namespace Caffeine\Console\Command;

use Caffeine;
use SebastianBergmann\Exporter\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console;
use Symfony\Component\Process\Process;

/**
 * Class Command
 * @package Caffeine
 */
class ShowCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('show')
            ->setDescription('Shows all the currently running Caffeine bots.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $process = new Process('ps aux | grep -i Caffeine/Console/../bin/runtime');
        $process->run();

        if(!$process->isSuccessful()){
            throw new Exception('todo message / custom error');
        }

        var_dump($process->getOutput());
    }
}
