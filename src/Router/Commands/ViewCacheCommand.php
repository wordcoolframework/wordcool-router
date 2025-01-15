<?php

namespace Router\Commands;

use CommandStyle\CommandStyle;
use CoolView\CoolEngine;
use Router\Stub;

final class ViewCacheCommand {

    public string $commandName = 'view:cache';

    public static function handle() : void{

        (new CoolEngine(
            getcwd() . '/template/views',
            getcwd() . '/template/caches')
        )->clearCache();

        echo CommandStyle::color("[*] View Cache Cleared .\n", ['bright_green']);
    }

}