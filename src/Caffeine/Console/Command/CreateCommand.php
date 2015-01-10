<?php

namespace Caffeine\Console\Command;

use Caffeine;
use Caffeine\Storage\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console;

/**
 * Class Command
 * @package Caffeine
 */
class CreateCommand extends Command
{
    const ARGUMENT_TWITCH_CHANNEL = 'twitch-channel-name';
    const ARGUMENT_OATUH_TOKEN    = 'bot-oauth-token';
    const ARGUMENT_USERNAME       = 'username';
    const ARGUMENT_TIMEZONE       = 'timezone';
    const ARGUMENT_ADMIN_USERS    = 'admin-users';

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('create')
            ->setDescription('Creates a config & spawns a Caffeine bot into the background.');

        $this->addArguments([
            [self::ARGUMENT_TWITCH_CHANNEL, 'Twitch Channel'],
            [self::ARGUMENT_OATUH_TOKEN, 'OAuth token for the bot, to override the configuration'],
            [self::ARGUMENT_USERNAME, 'Bot username']
        ], InputArgument::REQUIRED);

        $this->addArguments([
            [self::ARGUMENT_ADMIN_USERS, 'List of admin users which can add commands'],
            [self::ARGUMENT_TIMEZONE, 'Timezone i.e. (Europe/London)'],
        ], InputArgument::OPTIONAL);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channel = $input->getArgument(self::ARGUMENT_TWITCH_CHANNEL);
        $oauth = $input->getArgument(self::ARGUMENT_OATUH_TOKEN);
        $username = $input->getArgument(self::ARGUMENT_USERNAME);

        $this->welcomeMessage($output);
        $this->writeInfo($output, ' -Twitch Channel: ' . $channel);
        $this->setProcessTitle('Caffeine-' . $channel);

        $configuration = new Configuration();
        $config        = $configuration->getConfigurationFilePath($channel);

        if(!$configuration->exists($channel)){
            $configuration->mkdir($configuration->getFolderStoragePath($channel), 0755);
            $configuration->touch($channel);
        }

        //$storage = new Caffeine\Storage\StorageService([$configuration->getCurrentWorkingDirectory()]);
        //$data = $storage->load($configuration->getConfigurationFilePath($channel));

        $this->writeInfo($output, ' -Using configuration file: ' . $config);
        $this->writeInfo($output, ' -Spawning Caffeine for channel: ' . $channel);

        $this->createProcess($channel, $config, $output);
    }

    /**
     * @param $channel
     * @param $config
     * @param OutputInterface $output
     */
    private function createProcess($channel, $config, OutputInterface $output)
    {
        $pid = Caffeine\Process\ProcessService::handle(new Caffeine\Process\RuntimeProcess(), $channel, $config);

        $this->writeInfo($output, ' -Process Spawned, PID: ' . $pid);
    }
}
