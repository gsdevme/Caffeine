<?php

namespace Caffeine\Console\Command;

use Caffeine\Storage\Configuration;
use DateTimeZone;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

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

    const QUESTION_OAUTH    = '<question>Please enter the OAuth token for the bot. (http://goo.gl/mBNASR): </question>';
    const QUESTION_USERNAME = '<question>Please enter the username for the bot: </question>';
    const QUESTION_TIMEZONE = '<question>Please enter the Timezone for the bot/stream[Default UTC]: </question>';

    private $configuration;

    /**
     * @param null|string $name
     * @param Configuration $configuration
     * @param HelperInterface $questionHelper
     * @param HelperSet $helperSet
     */
    public function __construct(
        $name,
        Configuration $configuration,
        HelperInterface $questionHelper,
        HelperSet $helperSet)
    {
        $this->configuration = $configuration;

        $helperSet->set($questionHelper);
        $this->setHelperSet($helperSet);

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

        $this->configService($channel, $output);

        $oauth    = $this->getOptionalOrPrompt($input, $output, self::ARGUMENT_OATUH_TOKEN, self::QUESTION_OAUTH);
        $username = $this->getOptionalOrPrompt($input, $output, self::ARGUMENT_USERNAME, self::QUESTION_USERNAME);
        $timezone = new DateTimeZone($this->getOptionalOrPrompt($input, $output, self::ARGUMENT_TIMEZONE, self::QUESTION_TIMEZONE, 'UTC'));

        $this->writeInfo($output, ' -OAuth Token: ' . $oauth);
        $this->writeInfo($output, ' -Username: ' . $username);
        $this->writeInfo($output, ' -Timezone: ' . $timezone->getName());

        //$storage = new Caffeine\Storage\StorageService([$configuration->getCurrentWorkingDirectory()]);
        //$data = $storage->load($configuration->getConfigurationFilePath($channel));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param string $name
     * @param string $question
     * @param string $default
     * @return mixed|string
     */
    private function getOptionalOrPrompt(InputInterface $input, OutputInterface $output, $name, $question, $default = null)
    {
        $argument = $input->getArgument($name);

        if ($argument === null) {
            /** @var \Symfony\Component\Console\Helper\QuestionHelper $helper */
            $helper = $this->getHelper('question');

            $question = new Question($question, $default);

            /**
             * MAJOR TODO HERE, SORT THIS SHIT OUT
             */
            $question->setValidator(function ($v) {
                if (strlen($v) === 0) {
                    throw new \Exception('Value can not be empty.');
                }

                return $v;
            });

            $argument = $helper->ask($input, $output, $question);
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
