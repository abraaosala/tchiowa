<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\ConsoleHelpers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeController extends Command
{
    use ConsoleHelpers;

    protected static $defaultName = 'make:controller';
    protected static $defaultDescription = 'Cria um Controller';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Nome da classe');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $class = $this->studly($name);
        if (!str_ends_with($class, 'Controller')) {
            $class .= 'Controller';
        }
        
        $path = dirname(__DIR__, 3) . "/app/Http/Controllers/{$class}.php";
        
        $content = $this->renderStub('controller', [
            'namespace' => 'App\\Http\\Controllers',
            'class' => $class
        ]);
        
        $this->putClassFile($path, $content, $output);
        
        return Command::SUCCESS;
    }
}
