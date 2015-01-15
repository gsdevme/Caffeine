<?php

namespace Caffeine\Console\Command;

use Caffeine\Storage\Configuration;
use Caffeine\Storage\StorageService;
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
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channel = $input->getArgument(self::ARGUMENT_TWITCH_CHANNEL);

        $this->configService($channel, $output);

        $storage = new StorageService([$this->configuration->getCurrentWorkingDirectory()]);
        $data = $storage->load($this->configuration->getConfigurationFilePath($channel));

        var_dump($data);
        die;

        $oauth    = $this->getOptionalOrPrompt($input, $output, self::QUESTION_OAUTH);
        $username = $this->getOptionalOrPrompt($input, $output, self::QUESTION_USERNAME);
        $timezone = new DateTimeZone($this->getOptionalOrPrompt($input, $output, self::QUESTION_TIMEZONE, 'UTC'));



        $this->writeInfo($output, ' -OAuth Token: ' . $oauth);
        $this->writeInfo($output, ' -Username: ' . $username);
        $this->writeInfo($output, ' -Timezone: ' . $timezone->getName());
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param $question
     * @param null $default
     * @return string
     */
    private function getOptionalOrPrompt(InputInterface $input, OutputInterface $output, $question, $default = null)
    {
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

        return $helper->ask($input, $output, $question);
    }

    /**
     * @param $channel
     * @param OutputInterface $output
     */
    private function configService($channel, OutputInterface $output)
    {
        $config = $this->configuration->getConfigurationFilePath($channel);

        if (!$this->configuration->exists($channel)) {
            $this->writeInfo($output, 'Creating configuration file: ' . $config);

            $this->configuration->mkdir($this->configuration->getFolderStoragePath($channel), 0755);
            $this->configuration->touch($channel);
        } else {
            $this->writeInfo($output, 'Using configuration file: ' . $config);
        }
    }
}
