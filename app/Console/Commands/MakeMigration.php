<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\ConsoleHelpers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeMigration extends Command
{
    use ConsoleHelpers;

    protected static $defaultName = 'make:migration';
    protected static $defaultDescription = 'Cria uma nova migração';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Nome');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $this->runPhinx('create', $name);
        return Command::SUCCESS;
    }
}
