<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\ConsoleHelpers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateStatus extends Command
{
    use ConsoleHelpers;

    protected static $defaultName = 'status';
    protected static $defaultDescription = 'Mostra o estado das migrações';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->runPhinx('status');
        return Command::SUCCESS;
    }
}
