<?php

namespace Caffeine\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{
    public function __construct($version)
    {
        parent::__construct('Caffeine', $version);
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->addCommands([
            new Command\CreateCommand(),
            new Command\ShowCommand()
        ]);

        return parent::doRun($input, $output);
    }

    protected function getDefaultInputDefinition()
    {
        return new InputDefinition([
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display this application version.'),
        ]);
    }

    protected function getDefaultCommands()
    {
        return array(new ListCommand());
    }
}
