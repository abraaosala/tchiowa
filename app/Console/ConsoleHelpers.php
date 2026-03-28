<?php

declare(strict_types=1);

namespace App\Console;

use PDO;
use RuntimeException;
use Symfony\Component\Console\Output\OutputInterface;

trait ConsoleHelpers
{
    protected function makeServerPdo(): PDO
    {
        $driver = env('DB_CONNECTION', 'mysql');
        if ($driver !== 'mysql') {
            throw new RuntimeException("Apenas o driver 'mysql' é suportado neste comando por enquanto.");
        }
        $host = (string) env('DB_HOST', '127.0.0.1');
        $port = (int) env('DB_PORT', 3306);
        $username = (string) env('DB_USERNAME', 'root');
        $password = (string) env('DB_PASSWORD', '');
        $dsn = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $host, $port);
        return new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    protected function assertValidDatabaseName(string $name): void
    {
        if ($name === '') {
            throw new RuntimeException("DB_DATABASE não pode ser vazio.");
        }
        if (!preg_match('/^[A-Za-z0-9_]+$/', $name)) {
            throw new RuntimeException("Nome de base de dados inválido.");
        }
    }

    protected function studly(string $value): string
    {
        $value = str_replace(['-', '_'], ' ', $value);
        $value = ucwords(strtolower($value));
        return str_replace(' ', '', $value);
    }

    protected function renderStub(string $stubName, array $replacements): string
    {
        // Adjust the path to the root stubs directory
        $stubPath = dirname(__DIR__, 2) . '/stubs/' . $stubName . '.stub';
        if (!file_exists($stubPath)) {
            throw new RuntimeException("Stub não encontrado: {$stubPath}");
        }
        $content = file_get_contents($stubPath);
        foreach ($replacements as $key => $value) {
            $content = str_replace('{{ ' . $key . ' }}', $value, $content);
        }
        return $content;
    }

    protected function putClassFile(string $path, string $content, OutputInterface $output): void
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if (file_exists($path)) {
            $output->writeln("<comment>Ficheiro já existe: {$path}</comment>");
            return;
        }
        file_put_contents($path, $content);
        $output->writeln("<info>Criado: {$path}</info>");
    }

    protected function runPhinx(string $action, ?string $name = null): void
    {
        $phinx = escapeshellarg(dirname(__DIR__, 2) . '/vendor/bin/phinx');
        $command = "php {$phinx} {$action}";
        if ($name) {
            $command .= " " . escapeshellarg($name);
        }
        passthru($command);
    }
}
