<?php

namespace Caffeine\Console\Command;

use Caffeine;
use SplFileObject;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console;
use Symfony\Component\Process\Process;

/**
 * Class Command
 * @package Caffeine
 */
class ShowCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('show')
            ->setDescription('Shows all the currently running Caffeine bots.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $grep = new Process('ps aux | grep -i Caffeine/Console/../bin/runtime | awk \'{print $2}, {print $8}\'');
        $grep->run();

        if ($grep->isSuccessful()) {
            $ps = new \SplTempFileObject();

            $ps->setCsvControl(" ");
            $ps->setFlags(SplFileObject::READ_CSV);
            $ps->fwrite($grep->getOutput());

            $count = 0;

            foreach ($ps as $process) {
                $count += 1;
                var_dump($process);
            }

            $this->writeInfo($output, $count . ' Cafffeine processes are running');

        }
    }
}
