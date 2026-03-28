<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\ConsoleHelpers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Migrate extends Command
{
    use ConsoleHelpers;

    protected static $defaultName = 'migrate';
    protected static $defaultDescription = 'Executa migrações pendentes';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->runPhinx('migrate');
        return Command::SUCCESS;
    }
}
