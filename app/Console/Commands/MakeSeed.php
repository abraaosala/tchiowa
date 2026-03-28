<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\ConsoleHelpers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeSeed extends Command
{
    use ConsoleHelpers;

    protected static $defaultName = 'make:seed';
    protected static $defaultDescription = 'Cria um novo seeder';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Nome');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $this->runPhinx('seed:create', $name);
        return Command::SUCCESS;
    }
}
