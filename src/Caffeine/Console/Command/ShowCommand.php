<?php

namespace Caffeine\Console\Command;

use Caffeine;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console;

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

    }
}
