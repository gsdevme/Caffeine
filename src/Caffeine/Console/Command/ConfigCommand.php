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

    const QUESTION_OAUTH = 'Please enter the OAuth token for the bot';

    private $configuration;
    private $questionHelper;

    /**
     * @param null|string $name
     * @param FileInterface $configuration
     * @param Console\Helper\HelperInterface $questionHelper
     */
    public function __construct($name, FileInterface $configuration, Console\Helper\HelperInterface $questionHelper)
    {
        $this->configuration = $configuration;
        $this->questionHelper = $questionHelper;

        $this->setHelperSet($questionHelper);

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

        $this->questionHelper = $this->getHelper('question');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channel = $input->getArgument(self::ARGUMENT_TWITCH_CHANNEL);

        $this->writeInfo($output, ' -Twitch Channel: ' . $channel);
        $this->setProcessTitle('Caffeine-' . $channel);

        //$this->configService($channel, $output);

        //$oauth = $this->getOptionalOrPrompt($input, $output, self::ARGUMENT_OATUH_TOKEN, self::QUESTION_OAUTH);

        //$username = $input->getArgument(self::ARGUMENT_USERNAME);

        //$storage = new Caffeine\Storage\StorageService([$configuration->getCurrentWorkingDirectory()]);
        //$data = $storage->load($configuration->getConfigurationFilePath($channel));
    }

    private function getOptionalOrPrompt(InputInterface $input, OutputInterface $output, $name, $question)
    {
        $argument = $input->getArgument($name);

        if($argument === null){
            $argument = $this->questionHelper->ask($input, $output, new Console\Question\Question($question));
        }

        return $argument;
    }

    /**
     * @param $channel
     * @param OutputInterface $output
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
