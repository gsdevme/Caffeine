<?php

namespace Caffeine\Console;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class Application extends SymfonyApplication
{
    const TITLE = <<<TITLE
 #####################################
 # Caffeine Twitch IRC Bot           #
 # --------------------------------- #
 #                                   #
 # Created by: Gavin Staniforth      #
 # For use with Twitch.tv            #
 #####################################

TITLE;

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    private $container;
    private $containerLoader;

    public function __construct($version)
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        $this->container = new ContainerBuilder();

        parent::__construct('Caffeine', $version);
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->loadServices();
        $this->addServiceCommands();

        $output->writeln(sprintf('<comment>%s</comment>', self::TITLE));

        return parent::doRun($input, $output);
    }

    /**
     * @return InputDefinition
     */
    protected function getDefaultInputDefinition()
    {
        return new InputDefinition([
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display this application version.'),
        ]);
    }

    /**
     * Add all the commands from the services.xml to the application
     */
    private function addServiceCommands()
    {
        $commands = $this->container->findTaggedServiceIds('caffeine.command');

        foreach ($commands as $id => $v) {
            /** @var \Symfony\Component\Console\Command\Command $command */
            $command = $this->container->get($id);
            $this->add($command);
        }
    }

    /**
     * Load the services.xml
     */
    private function loadServices()
    {
        $this->containerLoader = new XmlFileLoader($this->container, new FileLocator(__DIR__ . '/../Resources/config/'));
        $this->containerLoader->load('command-services.xml');
        $this->containerLoader->load('services.xml');
    }
}
