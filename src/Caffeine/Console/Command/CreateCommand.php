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
            ->setDescription('Creates a config & spawns a Caffeine bot into the background.')
            ->addArgument(
                self::ARGUMENT_TWITCH_CHANNEL,
                InputArgument::REQUIRED,
                'Twitch Channel'
            );

        $this->addOptionalArguments([
            [self::ARGUMENT_OATUH_TOKEN, 'OAuth token for the bot, to override the configuration'],
            [self::ARGUMENT_USERNAME, 'Bot username'],
            [self::ARGUMENT_TIMEZONE, 'Timezone i.e. (Europe/London)'],
            [self::ARGUMENT_ADMIN_USERS, 'List of admin users which can add commands']
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channel = $input->getArgument(self::ARGUMENT_TWITCH_CHANNEL);

        $this->welcomeMessage($output);
        $this->writeInfo($output, ' -Twitch Channel: ' . $channel);
        $this->setProcessTitle('Caffeine-' . $channel);

        $config = $this->getConfiguration($channel);

        $this->writeInfo($output, ' -Using configuration file: ' . $config);
        $this->writeInfo($output, ' -Spawning Caffeine for channel: ' . $channel);

        $this->createProcess($channel, $config, $output);
    }

    /**
     * @param $channel
     * @return string
     */
    private function getConfiguration($channel)
    {
        $config = getcwd() . '/' . $this->getConfigFilename($channel);

        $this->createConfigurationIfDoesntExist($config);

        return $config;
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

    /**
     * @param array $arguments
     */
    private function addOptionalArguments(array $arguments)
    {
        foreach ($arguments as $argument) {
            $this->addArgument($argument[0], InputArgument::OPTIONAL, $argument[1]);
        }
    }

    /**
     * @param $config
     * @return bool
     */
    private function createConfigurationIfDoesntExist($config)
    {
        if (!file_exists($config)) {
            touch($config);
            return true;
        }

        return false;
    }

    /**
     * @param $channel
     * @return string
     */
    private function getConfigFilename($channel)
    {
        return sprintf('caffeine-%s.json', $channel);
    }
}
