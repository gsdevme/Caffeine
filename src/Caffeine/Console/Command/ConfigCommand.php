<?php

namespace Caffeine\Console\Command;

use Caffeine\Console\Command\Question\QuestionInterface;
use Caffeine\Storage\Configuration;
use Caffeine\Storage\StorageService;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ConfigCommand extends Command
{
    const ARGUMENT_TWITCH_CHANNEL = 'twitch-channel-name';

    private $configuration;
    private $usernameQuestion;
    private $timezoneQuestion;
    private $oauthQuestion;

    public function __construct(
        Configuration $configuration,
        HelperInterface $questionHelper,
        HelperSet $helperSet,
        QuestionInterface $usernameQuestion,
        QuestionInterface $oauthQuestion,
        QuestionInterface $timezoneQuestion)
    {
        $this->configuration    = $configuration;
        $this->usernameQuestion = $usernameQuestion;
        $this->oauthQuestion    = $oauthQuestion;
        $this->timezoneQuestion = $timezoneQuestion;

        $helperSet->set($questionHelper);
        $this->setHelperSet($helperSet);

        parent::__construct();
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

        $this->createConfigurationIfDoesntExist($channel, $output);

        $storage = new StorageService([$this->configuration->getCurrentWorkingDirectory()]);
        $data    = $storage->load($this->configuration->getConfigurationFilePath($channel));

        $config = new \Caffeine\Process\Runtime\Config($data);

        $username = $this->askQuestion($input, $output, $this->usernameQuestion, $config->getUsername());
        $oauth    = $this->askQuestion($input, $output, $this->oauthQuestion);
        $timezone = $this->askQuestion($input, $output, $this->timezoneQuestion);

        $this->writeInfo($output, ' -OAuth Token: ' . $oauth);
        $this->writeInfo($output, ' -Username: ' . $username);
        $this->writeInfo($output, ' -Timezone: ' . $timezone->getName());
    }

    private function askQuestion(InputInterface $input, OutputInterface $outputInterface, QuestionInterface $questionBuilder, $default = null)
    {
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $question = new Question($questionBuilder->getQuestion(), $default);
        $question->setValidator($questionBuilder->getValidator());

        return $helper->ask($input, $outputInterface, $question);
    }

    /**
     * @param $channel
     * @param OutputInterface $output
     */
    private function createConfigurationIfDoesntExist($channel, OutputInterface $output)
    {
        $config = $this->configuration->getConfigurationFilePath($channel);

        if (!$this->configuration->exists($channel)) {
            $this->configuration->mkdir($this->configuration->getFolderStoragePath($channel), 0755);
            $this->configuration->touch($channel);
        }
    }
}
