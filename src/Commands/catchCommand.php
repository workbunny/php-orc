<?php declare(strict_types=1);

namespace PhpyReqsHelper\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class catchCommand extends Command
{

    protected function configure(): void
    {
        $this->setName('catch')
            ->setDescription('install');
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return self::SUCCESS;
    }
}