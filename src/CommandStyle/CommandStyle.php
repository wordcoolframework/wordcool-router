<?php

namespace CommandStyle;

class CommandStyle{
    use Colors;
    /**
     * Print new lines for padding top/bottom
     * @param int $count
     * @return void
     */
    public static function br(int $count) : void{
        for ($i = 0; $i < $count; $i++){
            echo PHP_EOL;
        }
    }

    /**
     * Print horizontal line
     * @param int $length
     * @param string $char
     * @return void
     */
    public static function hr(
        int $length = 50,
        string $char = '-'
    ) : void {
        echo str_repeat($char, $length) . PHP_EOL;
    }

    /**
     * Print text with color
     *
     * @param string $text
     * @param array $styles
     * @return string
     */
    public static function color(
        string $text,
        array $styles = ['default']
    ) : string {
        $codes = array_filter(array_map(static function ($style) {
            return self::select($style);
        }, $styles));

        $ansiCode = implode(';', $codes);
        return "\033[" . $ansiCode . "m" . $text . "\033[0m";
    }

    /**
     * @param string $text
     * @param string $color
     * @param int $width
     * @param string $padString
     * @param int $padType
     * @return string
     */
    public static function paddedColor(
        string  $text,
        array   $styles = ['default'],
        int     $width = 0,
        string  $padString = " ",
        int     $padType = STR_PAD_RIGHT
    ): string {

        $plainText = strip_tags(preg_replace('/\033\[[0-9;]*m/', '', $text));

        $padding = max(0, $width - strlen($plainText));

        $paddedText = str_pad($plainText, $width, $padString, $padType);

        return self::color($paddedText, $styles);
    }

    public static function RepeatChar(
        string $char,
        int $count
    ) : string{
        return str_repeat($char, $count);
    }

    private static function calculateColumnWidths(array $headers, array $rows): array{
        $columnWidths = [];
        foreach ($headers as $index => $header) {
            $columnWidths[$index] = strlen($header);
        }
        foreach ($rows as $row) {
            foreach ($row as $index => $cell) {
                $columnWidths[$index] = max($columnWidths[$index] ?? 0, strlen((string)$cell));
            }
        }
        return $columnWidths;
    }

    private static function createDivider(array $columnWidths): string{
        $divider = '+';
        foreach ($columnWidths as $width) {
            $divider .= str_repeat('-', $width + 2) . '+';
        }
        return $divider;
    }

    private static function formatRow(array $row, array $columnWidths): string{
        $formattedRow = '|';
        foreach ($row as $index => $cell) {
            $formattedRow .= ' ' . str_pad((string)$cell, $columnWidths[$index]) . ' |';
        }
        return $formattedRow;
    }

    public static function printTable(array $headers, array $rows): void{
        $columnWidths = self::calculateColumnWidths($headers, $rows);

        // Divider
        $divider = self::createDivider($columnWidths);

        // Print headers
        echo $divider . PHP_EOL;
        echo self::formatRow($headers, $columnWidths) . PHP_EOL;
        echo $divider . PHP_EOL;

        // Print rows
        foreach ($rows as $row) {
            echo self::formatRow($row, $columnWidths) . PHP_EOL;
        }

        echo $divider . PHP_EOL;
    }


}