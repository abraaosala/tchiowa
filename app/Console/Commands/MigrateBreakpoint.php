<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\ConsoleHelpers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateBreakpoint extends Command
{
    use ConsoleHelpers;

    protected static $defaultName = 'breakpoint';
    protected static $defaultDescription = 'Gere breakpoints de migração';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->runPhinx('breakpoint');
        return Command::SUCCESS;
    }
}
