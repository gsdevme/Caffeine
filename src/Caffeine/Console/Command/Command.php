<?php

namespace Caffeine\Console\Command;

use Symfony\Component\Console;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Command
 * @package Caffeine
 */
abstract class Command extends Console\Command\Command
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

    const WRITE_LINE_INFO    = '<info>%s</info>';
    const WRITE_LINE_COMMENT = '<comment>%s</comment>';

    protected function welcomeMessage(OutputInterface $output)
    {
        $this->writeComment($output, self::TITLE);
    }


    /**
     * @param array $arguments
     * @param int $type
     */
    protected function addArguments(array $arguments, $type = Console\Input\InputArgument::REQUIRED)
    {
        foreach ($arguments as $argument) {
            $this->addArgument($argument[0], $type, $argument[1]);
        }
    }

    /**
     * @param OutputInterface $output
     * @param string $buffer
     * @return mixed
     */
    protected function writeInfo(OutputInterface $output, $buffer)
    {
        return $output->writeln(sprintf(self::WRITE_LINE_INFO, $buffer));
    }

    /**
     * @param OutputInterface $output
     * @param string $buffer
     * @return mixed
     */
    protected function writeComment(OutputInterface $output, $buffer)
    {
        return $output->writeln(sprintf(self::WRITE_LINE_COMMENT, $buffer));
    }
}
