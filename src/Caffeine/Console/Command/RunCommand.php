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
    private $configuration;

    public function __construct(
        Caffeine\Storage\Configuration $configuration,
        Caffeine\Process\Runtime\RuntimeProcess $runtime,
        Caffeine\Process\ProcessService $processService)
    {
        $this->runtimeProcess = $runtime;
        $this->processService = $processService;
        $this->configuration  = $configuration;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('caffeine:run')
            ->setDescription('Spawns a Caffeine bot into the background.');

        $this->addArguments([
            [self::ARGUMENT_TWITCH_CHANNEL, 'Twitch Channel']
        ], InputArgument::REQUIRED);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channel = $input->getArgument(self::ARGUMENT_TWITCH_CHANNEL);

        try{
            $storage = new Caffeine\Storage\StorageService([$this->configuration->getCurrentWorkingDirectory()]);
            $data    = $storage->load($this->configuration->getConfigurationFilePath($channel));

            $coreConfig = new \Caffeine\Process\Runtime\CoreConfig($data);

            $this->processService->handle($this->runtimeProcess, $coreConfig->getChannel(), $this->configuration->getConfigurationFilePath($channel));
        }catch (\Exception $e){
            var_dump($e->getFile());
            var_dump($e->getLine());
        }
    }
}
