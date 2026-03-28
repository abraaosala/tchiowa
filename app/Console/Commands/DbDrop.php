<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\ConsoleHelpers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DbDrop extends Command
{
    use ConsoleHelpers;

    protected static $defaultName = 'db:drop';
    protected static $defaultDescription = 'Elimina a base de dados definida em DB_DATABASE';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $database = (string) env('DB_DATABASE', '');
        $this->assertValidDatabaseName($database);
        
        $pdo = $this->makeServerPdo();
        $pdo->exec(sprintf('DROP DATABASE IF EXISTS `%s`', $database));
        
        $output->writeln("<info>Base de dados '{$database}' eliminada (se existia).</info>");
        return Command::SUCCESS;
    }
}
