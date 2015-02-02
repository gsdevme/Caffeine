<?php

namespace Caffeine\Console\Command;

use Caffeine\Console\Command\Question\QuestionInterface;
use Caffeine\Storage\Configuration;
use Caffeine\Storage\PhpFilePublisher;
use Caffeine\Storage\StorageService;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ConfigCommand extends Command
{
    private $configuration;
    private $phpFilePublisher;
    private $usernameQuestion;
    private $timezoneQuestion;
    private $oauthQuestion;

    public function __construct(
        Configuration $configuration,
        PhpFilePublisher $phpFilePublisher,
        HelperInterface $questionHelper,
        HelperSet $helperSet,
        QuestionInterface $usernameQuestion,
        QuestionInterface $oauthQuestion,
        QuestionInterface $timezoneQuestion)
    {
        $this->configuration    = $configuration;
        $this->phpFilePublisher = $phpFilePublisher;
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

        $coreConfig = new \Caffeine\Process\Runtime\CoreConfig();

        if(isset($data['username'])){
            $coreConfig->setUsername($data['username']);
        }

        if(isset($data['oauth'])){
            $coreConfig->setOAuth($data['oauth']);
        }

        $coreConfig->setChannel($channel);

        $coreConfig->setUsername((string)$this->askQuestion($input, $output, $this->usernameQuestion, $coreConfig->getUsername()));
        $coreConfig->setOAuth((string)$this->askQuestion($input, $output, $this->oauthQuestion, $coreConfig->getOAuth()));
        $coreConfig->setTimezone($this->askQuestion($input, $output, $this->timezoneQuestion), $coreConfig->getTimezone());

        $this->writeInfo($output, ' -OAuth Token: ' . $coreConfig->getOAuth());
        $this->writeInfo($output, ' -Username: ' . $coreConfig->getUsername());
        $this->writeInfo($output, ' -Timezone: ' . $coreConfig->getTimezone()->getName());

        $this->phpFilePublisher->write($this->configuration->getConfigurationFilePath($channel), $coreConfig->toArray());
    }

    private function askQuestion(InputInterface $input, OutputInterface $outputInterface, QuestionInterface $questionBuilder, $default = null)
    {
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $question = new Question($questionBuilder->getQuestion($default), $default);
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
