<?php

namespace Caffeine\Console\Command;

use Caffeine;
use Symfony\Component\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Command
 * @package Caffeine
 */
class RunCommand extends Command
{

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('run')
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

    }
}
