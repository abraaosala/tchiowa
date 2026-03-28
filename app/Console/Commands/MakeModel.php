<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\ConsoleHelpers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeModel extends Command
{
    use ConsoleHelpers;

    protected static $defaultName = 'make:model';
    protected static $defaultDescription = 'Cria um Model Eloquent';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Nome da classe');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $class = $this->studly($name);
        $table = strtolower($class) . 's';
        
        $path = dirname(__DIR__, 3) . "/app/Infrastructure/Persistence/Eloquent/{$class}.php";
        
        $content = $this->renderStub('model', [
            'namespace' => 'App\\Infrastructure\\Persistence\\Eloquent',
            'class' => $class,
            'table' => $table
        ]);
        
        $this->putClassFile($path, $content, $output);
        
        return Command::SUCCESS;
    }
}
