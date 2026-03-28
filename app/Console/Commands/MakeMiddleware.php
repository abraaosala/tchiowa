<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\ConsoleHelpers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeMiddleware extends Command
{
    use ConsoleHelpers;

    protected static $defaultName = 'make:middleware';
    protected static $defaultDescription = 'Cria um Middleware';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Nome da classe');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $class = $this->studly($name);
        if (!str_ends_with($class, 'Middleware')) {
            $class .= 'Middleware';
        }
        
        $path = dirname(__DIR__, 3) . "/app/Http/Middleware/{$class}.php";
        
        $content = $this->renderStub('middleware', [
            'namespace' => 'App\\Http\\Middleware',
            'class' => $class
        ]);
        
        $this->putClassFile($path, $content, $output);
        
        return Command::SUCCESS;
    }
}
