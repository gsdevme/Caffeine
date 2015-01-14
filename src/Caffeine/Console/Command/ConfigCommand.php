<?php

namespace Caffeine\Console\Command;

use Caffeine\Storage\FileInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console;

/**
 * Class Command
 * @package Caffeine
 */
class ConfigCommand extends Command
{
    const ARGUMENT_TWITCH_CHANNEL = 'twitch-channel-name';
    const ARGUMENT_OATUH_TOKEN    = 'bot-oauth-token';
    const ARGUMENT_USERNAME       = 'username';
    const ARGUMENT_TIMEZONE       = 'timezone';
    const ARGUMENT_ADMIN_USERS    = 'admin-users';

    private $configuration;

    public function __construct($name, FileInterface $configuration)
    {
        $this->configuration = $configuration;

        parent::__construct($name);
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('caffeine:config')
            ->setDescription('Create/Edit a config for caffeine bot.');

        $this->addArguments([
            [self::ARGUMENT_TWITCH_CHANNEL, 'Twitch Channel']
        ], InputArgument::REQUIRED);

        $this->addArguments([
            [self::ARGUMENT_OATUH_TOKEN, 'OAuth token for the bot, to override the configuration'],
            [self::ARGUMENT_USERNAME, 'Bot username'],
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

        $this->writeInfo($output, ' -Twitch Channel: ' . $channel);
        $this->setProcessTitle('Caffeine-' . $channel);

        $configuration = $this->configService($channel, $output);

        $oauth = $input->getArgument(self::ARGUMENT_OATUH_TOKEN);

        if ($oauth === null) {

        }

        //$username = $input->getArgument(self::ARGUMENT_USERNAME);

        //$storage = new Caffeine\Storage\StorageService([$configuration->getCurrentWorkingDirectory()]);
        //$data = $storage->load($configuration->getConfigurationFilePath($channel));
    }

    /**
     * @param $channel
     * @param OutputInterface $output
     * @return Configuration
     */
    private function configService($channel, OutputInterface $output)
    {
        $config = $this->configuration->getConfigurationFilePath($channel);

        if (!$this->configuration->exists($channel)) {
            $this->writeInfo($output, ' -Creating configuration file: ' . $config);

            $this->configuration->mkdir($this->configuration->getFolderStoragePath($channel), 0755);
            $this->configuration->touch($channel);
        } else {
            $this->writeInfo($output, ' -Using configuration file: ' . $config);
        }
    }
}
