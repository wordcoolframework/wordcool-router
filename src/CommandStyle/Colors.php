<?php

namespace CommandStyle;

trait Colors {

    public static function select(string $style) : ?string {
        $styles = [
            'black'      => '0;30',
            'red'        => '0;31',
            'green'      => '0;32',
            'yellow'     => '0;33',
            'blue'       => '0;34',
            'purple'     => '0;35',
            'cyan'       => '0;36',
            'white'      => '0;37',
            'default'    => '0',

            // Bright Colors
            'bright_black'  => '1;30',
            'bright_red'    => '1;31',
            'bright_green'  => '1;32',
            'bright_yellow' => '1;33',
            'bright_blue'   => '1;34',
            'bright_purple' => '1;35',
            'bright_cyan'   => '1;36',
            'bright_white'  => '1;37',

            // Background Colors
            'bg_black'   => '40',
            'bg_red'     => '41',
            'bg_green'   => '42',
            'bg_yellow'  => '43',
            'bg_blue'    => '44',
            'bg_purple'  => '45',
            'bg_cyan'    => '46',
            'bg_white'   => '47',

            // Bright Background Colors
            'bg_bright_black'  => '100',
            'bg_bright_red'    => '101',
            'bg_bright_green'  => '102',
            'bg_bright_yellow' => '103',
            'bg_bright_blue'   => '104',
            'bg_bright_purple' => '105',
            'bg_bright_cyan'   => '106',
            'bg_bright_white'  => '107',

            // Text Styles
            'bold'       => '1',
            'dim'        => '2',
            'italic'     => '3',
            'underline'  => '4',
            'blink'      => '5',
            'invert'     => '7',
            'hidden'     => '8',
            'strikethrough' => '9',
        ];

        return $styles[$style] ?? null;
    }
}