<?php

namespace Router\Commands;

use CommandStyle\CommandStyle;
use Router\Route;

final class ServeCommand {

    public string $commandName = 'serve';

    public static function handle(?int $port = null) : void {
        $defaultPort = 8000;

        $port = $port ?? $defaultPort;

        if (!is_numeric($port) || (int)$port <= 0 || (int)$port > 65535) {
            echo "The port is invalid. Please enter a valid number between 1 and 65535.\n";
            exit(1);
        }

        $port = (int)$port;

        $rootDir = getcwd();

        echo "[*] Server running on http://localhost:$port \n";
        echo "Press Ctrl+C to exit . \n";

        $command = sprintf('php -S localhost:%d -t %s', $port, escapeshellarg($rootDir));
        shell_exec($command);
    }
}