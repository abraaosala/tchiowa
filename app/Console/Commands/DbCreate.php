<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\ConsoleHelpers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DbCreate extends Command
{
    use ConsoleHelpers;

    protected static $defaultName = 'db:create';
    protected static $defaultDescription = 'Cria a base de dados definida em DB_DATABASE';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $database = (string) env('DB_DATABASE', '');
        $this->assertValidDatabaseName($database);
        
        $pdo = $this->makeServerPdo();
        $pdo->exec(sprintf('CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci', $database));
        
        $output->writeln("<info>Base de dados '{$database}' criada (ou já existente).</info>");
        return Command::SUCCESS;
    }
}
