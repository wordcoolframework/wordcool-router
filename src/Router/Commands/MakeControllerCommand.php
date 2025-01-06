<?php

namespace Router\Commands;

use CommandStyle\CommandStyle;
use Router\Stub;

final class MakeControllerCommand {

    public string $commandName = 'make:controller';

    public static function handle(?string $controllerName = null) : void{

        if($controllerName === null){
            echo CommandStyle::color("[*] Please provide a controller name.\n", ['red']);
            exit(1);
        }

        $stubPath = Stub::get('controller');

        if (!file_exists($stubPath)) {
            echo CommandStyle::color("[!] Controller stub file not found.\n", ['bright_red']);
            exit(1);
        }

        $stubContent = file_get_contents($stubPath);

        $controllerContent = str_replace('{{ControllerName}}', $controllerName, $stubContent);

        $controllersDir = getcwd() . '/App/Http/Controllers';

        if (!is_dir($controllersDir) && !mkdir($controllersDir, 0755, true) && !is_dir($controllersDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $controllersDir));
        }

        $controllerFilePath = $controllersDir . '/' . $controllerName . '.php';

        if (file_exists($controllerFilePath)) {
            echo CommandStyle::color("[!] Controller '$controllerName' already exists.\n", ['bright_yellow']);
            exit(1);
        }

        file_put_contents($controllerFilePath, $controllerContent);

        echo CommandStyle::color("[*] Controller '$controllerName' has been created successfully.\n", ['bright_green']);
    }

}