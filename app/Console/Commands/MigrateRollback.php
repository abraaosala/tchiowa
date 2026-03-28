<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\ConsoleHelpers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateRollback extends Command
{
    use ConsoleHelpers;

    protected static $defaultName = 'rollback';
    protected static $defaultDescription = 'Reverte a última migração';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->runPhinx('rollback');
        return Command::SUCCESS;
    }
}
