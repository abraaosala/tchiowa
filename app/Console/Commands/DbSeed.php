<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\ConsoleHelpers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DbSeed extends Command
{
    use ConsoleHelpers;

    protected static $defaultName = 'seed:run';
    protected static $defaultDescription = 'Executa os seeders';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->runPhinx('seed:run');
        return Command::SUCCESS;
    }
}
