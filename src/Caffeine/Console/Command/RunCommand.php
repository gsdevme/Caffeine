<?php

namespace Caffeine\Console\Command;

use Caffeine;
use Symfony\Component\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Command
 * @package Caffeine
 */
class RunCommand extends Console\Command\Command
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

    const WRITE_LINE_INFO = '<info>%s</info>';
    const WRITE_LINE_COMMENT = '<comment>%s</comment>';

    const ARGUMENT_TWITCH_CHANNEL = 'twitch-channel-name';
    const ARGUMENT_OATUH_TOKEN = 'bot-oauth-token';
    const ARGUMENT_USERNAME = 'username';
    const ARGUMENT_TIMEZONE = 'timezone';
    const ARGUMENT_CONFIG = 'config';
    const ARGUMENT_ADMIN_USERS = 'admin-users';

    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Caffeine Twitch.tv chat bot.')
            ->addArgument(
                self::ARGUMENT_TWITCH_CHANNEL,
                InputArgument::REQUIRED,
                'Twitch Channel'
            );

        $this->addOptionalArguments([
            [self::ARGUMENT_OATUH_TOKEN, 'OAuth token for the bot, to override the configuration'],
            [self::ARGUMENT_USERNAME, 'Bot username'],
            [self::ARGUMENT_TIMEZONE, 'Timezone i.e. (Europe/London)'],
            [self::ARGUMENT_CONFIG, 'Config file, i.e. foobar.json'],
            [self::ARGUMENT_ADMIN_USERS, 'List of admin users which can add commands']
        ]);
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
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->writeComment($output, self::TITLE);

        $channel = $input->getArgument(self::ARGUMENT_TWITCH_CHANNEL);

        $this->writeInfo($output, ' -Twitch Channel: ' . $channel);
        $this->setProcessTitle('Caffeine-' . $channel);

        $path   = getcwd() . '/';
        $config = $this->getConfigFileLocation($input, $channel);

        $this->createConfigurationIfDoesntExist($path, $config);

        $this->writeInfo($output, ' -Spawning Caffeine for channel: ' . $channel);

        Caffeine\Console\ProcessSpawnFactory::create($channel);
    }

    /**
     * @param InputInterface $input
     * @param array $config
     */
    private function getOptionalArguments(InputInterface $input, array $config)
    {
        if ($input->getArgument(self::ARGUMENT_OATUH_TOKEN)) {
            $config[self::ARGUMENT_OATUH_TOKEN] = $input->getArgument(self::ARGUMENT_OATUH_TOKEN);
        }
    }

    /**
     * @param string $path
     * @param $config
     * @return bool
     */
    private function createConfigurationIfDoesntExist($path, $config)
    {
        if (!file_exists($path . $config)) {
            touch($path . $config);
            return true;
        }

        return false;
    }

    /**
     * @param InputInterface $input
     * @param $channel
     * @return mixed|string
     */
    private function getConfigFileLocation(InputInterface $input, $channel)
    {
        if (!$input->getArgument(self::ARGUMENT_CONFIG)) {
            return sprintf('caffeine-%s.json', $channel);
        }

        return $input->getArgument(self::ARGUMENT_CONFIG);
    }

    /**
     * @param OutputInterface $output
     * @param string $buffer
     * @return mixed
     */
    private function writeInfo(OutputInterface $output, $buffer)
    {
        return $output->writeln(sprintf(self::WRITE_LINE_INFO, $buffer));
    }

    /**
     * @param OutputInterface $output
     * @param string $buffer
     * @return mixed
     */
    private function writeComment(OutputInterface $output, $buffer)
    {
        return $output->writeln(sprintf(self::WRITE_LINE_COMMENT, $buffer));
    }
}
