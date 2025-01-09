<?php

namespace Router;

final class Command {

    private static array $commands  = [];

    public static function register(string $className) : void {
       self::$commands[] = $className;
    }

    public static function registerAllCommands(
        string $commandNamespace,
        string $commandDirectory
    ): void {
        $files = glob($commandDirectory . '/*.php');

        foreach ($files as $file) {
            $className = $commandNamespace . '\\' . pathinfo($file, PATHINFO_FILENAME);
            if (class_exists($className)) {
                self::register($className);
            }
        }
    }

    public static function run(string $command, string|int|null $argument = null) : void {
        foreach (self::$commands as $commandClass){
            $commandName = (new $commandClass);
            if (strtolower($command) === $commandName->commandName) {
                $commandClass::handle($argument);
                return;
            }
        }

        echo "Command not found.\n";
    }

}