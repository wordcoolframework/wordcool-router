<?php

namespace Router\Commands;

use CommandStyle\CommandStyle;
use Router\Stub;

final class MakeMiddlewareCommand {

    public string $commandName = 'make:middleware';

    public static function handle(?string $middlewareName = null) : void{

        if($middlewareName === null){
            echo CommandStyle::color("[*] Please provide a middleware name.\n", ['red']);
            exit(1);
        }

        $stubPath = Stub::get('middleware');

        if (!file_exists($stubPath)) {
            echo CommandStyle::color("[!] Middleware stub file not found.\n", ['bright_red']);
            exit(1);
        }

        $stubContent = file_get_contents($stubPath);

        $controllerContent = str_replace('{{ControllerName}}', $middlewareName, $stubContent);

        $middlewaresDir = getcwd() . '/App/Http/Controllers';

        if (!is_dir($middlewaresDir) && !mkdir($middlewaresDir, 0755, true) && !is_dir($middlewaresDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $middlewaresDir));
        }

        $controllerFilePath = $middlewaresDir . '/' . $middlewareName . '.php';

        if (file_exists($controllerFilePath)) {
            echo CommandStyle::color("[!] Controller '$middlewareName' already exists.\n", ['bright_yellow']);
            exit(1);
        }

        file_put_contents($controllerFilePath, $controllerContent);

        echo CommandStyle::color("[*] Controller '$middlewareName' has been created successfully.\n", ['bright_green']);
    }

}