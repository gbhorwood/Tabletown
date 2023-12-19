<?php

declare(strict_types=1);

namespace Gbhorwood\Tabletown;

/**
 * MIT License
 *
 * Copyright (c) 2019 grant horwood
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * Border definitions
 */
if(!defined('TABLE_BORDER_STANDARD')) {
    define('TABLE_BORDER_STANDARD', 'standard');
}
if(!defined('TABLE_BORDER_SOLID')) {
    define('TABLE_BORDER_SOLID', 'solid');
}
if(!defined('TABLE_BORDER_DOUBLE')) {
    define('TABLE_BORDER_DOUBLE', 'double');
}

/**
 * Alignment definitions
 */
if(!defined('LEFT')) {
    define('LEFT', 0);
}
if(!defined('CENTRE')) {
    define('CENTRE', 1);
}
if(!defined('CENTER')) {
    define('CENTER', 1);
}
if(!defined('RIGHT')) {
    define('RIGHT', 2);
}

class Table
{
    /**
     * Array of arrays defining table styles
     * @var Array<Array<String,Array<String,String>>>
     * @access private
     */
    private static array $tableStyles = [
        TABLE_BORDER_STANDARD => [
            'default' => [
                'bar' => '|',
                'separator' => '-',
            ],
            'top' => [
                'left' => '+',
                'right' => '+',
                'join' => '+',
            ],
            'bottom' => [
                'left' => '+',
                'right' => '+',
                'join' => '+',
            ],
            'inner' => [
                'left' => '+',
                'right' => '+',
                'join' => '+',
            ],
        ],

        TABLE_BORDER_SOLID => [
            'default' => [
                'bar' => '│',
                'separator' => '─',
            ],
            'top' => [
                'left' => '┌',
                'right' => '┐',
                'join' => '┬',
            ],
            'bottom' => [
                'left' => '└',
                'right' => '┘',
                'join' => '┴',
            ],
            'inner' => [
                'left' => '├',
                'right' => '┤',
                'join' => '┼',
            ],
        ],

        TABLE_BORDER_DOUBLE => [
            'default' => [
                'bar' => '║',
                'separator' => '═',
            ],
            'top' => [
                'left' => '╔',
                'right' => '╗',
                'join' => '╦',
            ],
            'bottom' => [
                'left' => '╚',
                'right' => '╝',
                'join' => '╩',
            ],
            'inner' => [
                'left' => '╠',
                'right' => '╣',
                'join' => '╬',
            ],
        ],
    ];

    /**
     * Get the table as string. Overloaded method.
     *
     * Valid signatures are:
     * (array<string>, array<array<string|int>>, ?String, ?array<int>);
     * (array<array<string,string>>, ?String, ?array<int>);
     * (PDOStatement, ?String, ?array<int>);
     * (Illuminate\Database\Eloquent\Collection, ?String, ?array<int>);
     *
     * @return ?String
     */
    public static function get(...$args): ?string /** @phpstan-ignore-line */
    {
        /**
         * (array<string>, array<array<string|int>>, ?String, ?array<int>);
         */
        if(count($args) >= 2 && is_array($args[0]) && is_array($args[1])) {
            return self::build($args[0], $args[1], $args[2] ?? TABLE_BORDER_STANDARD, $args[3] ?? []);
        }

        /**
         * (array<array<string,string>>, ?String, ?array<int>);
         */
        if(count($args) >= 1 && is_array($args[0])) {
            $headers = array_keys($args[0][0]);
            $data = array_map(fn ($d) => array_values((array)$d), $args[0]);
            return self::build($headers, $data, $args[1] ?? TABLE_BORDER_STANDARD, $args[3] ?? []);
        }

        /**
         * (PDOStatement, ?String, ?array<int>);
         */
        if((!is_array($args[0]) && !is_scalar($args[0])) && get_class($args[0]) == "PDOStatement") {
            $assoc = $args[0]->fetchAll(\PDO::FETCH_ASSOC);
            $headers = array_keys($assoc[0]);
            $data = array_map(fn ($d) => array_values((array)$d), $assoc);
            return self::build($headers, $data, $args[1] ?? TABLE_BORDER_STANDARD, $args[3] ?? []);
        }

        /**
         * (Illuminate\Database\Eloquent\Collection, ?String, ?array<int>);
         */
        if((!is_array($args[0]) && !is_scalar($args[0])) && get_class($args[0]) == "Illuminate\Database\Eloquent\Collection") {
            $singleArray = $args[0]->toArray();
            $headers = array_keys($singleArray[0]);
            $data = array_map(fn ($d) => array_values((array)$d), $singleArray);
            return self::build($headers, $data, $args[1] ?? TABLE_BORDER_STANDARD, $args[3] ?? []);
        }

        return null;
    }

    /**
     * Create and return table as string from array of headers and data.
     *
     * @param  Array<String|Int>  $headers Array of strings to use as column headers
     * @param  Array<Array<String|Int>>  $data Array of data to populate the table rows
     * @param  String $borderStyle Optional border style. One of TABLE_BORDER_STANDARD, TABLE_BORDER_SOLID, TABLE_BORDER_DOUBLE. Default TABLE_BORDER_STANDARD.
     * @param  Array<Int>  $alignments Optional array of alignments for each column position. Each value one of LEFT, RIGHT, CENTER or CENTRE. Default LEFT.
     * @return String
     * @throws \Exception
     */
    public static function build(array $headers, array $data, String $borderStyle = TABLE_BORDER_STANDARD, array $alignments = []): String
    {
        // validate all arrays have the same number of elements so columns align
        if(!self::validateColCount($headers, $data)) {
            throw new \Exception("Column counts do not match");
        }

        // expand tabs so string width works with tabs
        $headers = array_map(fn ($l) => self::expandTabs((string)$l), $headers);
        $data = array_map(fn ($l) => array_map(fn ($l) => self::expandTabs((string)$l), $l), $data);

        /**
         * Build array keyed by column position containing value of longest line in that column
         * Used for calculating the padding of each cel to ensure column alignment
         */
        $colWidths = [];
        for($i = 0;$i < count($headers);$i++) {
            $longestLineInColumn = fn ($col) => max(array_map(fn ($c) => self::mb_strwidth_ansi((string)$c), explode(PHP_EOL, (string)$col[$i])));
            $colWidths[$i] = max(array_map($longestLineInColumn, array_merge([$headers], $data)));
        }

        /**
         * Build array keyed by row position containing value of count of lines in that row
         * Used for building the right number of lines in each cell to ensure consistent row height
         */
        $rowHeights = [];
        for($i = 0;$i < count($data);$i++) {
            $rowHeights[] = max(array_map(fn ($d) => count(explode(PHP_EOL, (string)$d)), $data[$i]));
        }

        /**
         * Height of header in lines
         * Used for ensuring all cels in header row are the same height.
         */
        $headerHeight = max(array_map(fn ($h) => count(explode(PHP_EOL, (string)$h)), $headers));

        /**
         * Function to build and return a divider line, ie. the string of chars used in the border as a divider
         *
         * @param  String $position One of 'top', 'bottom', or 'inner'
         * @return String
         */
        $divider = function (String $position) use ($colWidths, $borderStyle): String {
            $edgeLeft = self::$tableStyles[$borderStyle][$position]['left'];
            $edgeRight = self::$tableStyles[$borderStyle][$position]['right'];
            $join = self::$tableStyles[$borderStyle][$position]['join'];
            $separator = self::$tableStyles[$borderStyle]['default']['separator'];

            return $edgeLeft .
                   join($join, array_map(fn ($c) => join(array_fill(0, $c + 2, $separator)), $colWidths)) .
                   $edgeRight;
        };

        /**
         * Function to pad the content of one line in one cel to correct width for the desired alignment.
         *
         * @param  String $text      The text to pad to $colWidth
         * @param  Int    $colWidth  The width to pad $text to
         * @param  Int    $alignment The alignment of the column. One of LEFT, RIGHT, CENTER or CENTRE. Default LEFT
         * @return String
         */
        $padCelLine = function (String $text, Int $colWidth, Int $alignment = LEFT) use ($borderStyle): String {
            switch ($alignment) {
                case CENTER:
                case CENTRE:
                    $lpad = (int)floor(($colWidth - self::mb_strwidth_ansi($text)) / 2);
                    $rpad = (int)ceil(($colWidth - self::mb_strwidth_ansi($text)) / 2);
                    return ' '.join(array_fill(0, $lpad, ' ')).$text.join(array_fill(0, $rpad, ' ')).' '.self::$tableStyles[$borderStyle]['default']['bar'];
                case RIGHT:
                    return join(array_fill(0, $colWidth - self::mb_strwidth_ansi($text), ' ')).' '.$text.' '.self::$tableStyles[$borderStyle]['default']['bar'];
                // LEFT
                default:
                    return " $text".join(array_fill(0, $colWidth - self::mb_strwidth_ansi($text), ' ')).' '.self::$tableStyles[$borderStyle]['default']['bar'];
            }
        };

        /**
         * Build the table as an array of lines
         */

        // top divider line
        $tableArray = [$divider('top')];

        // header row
        for($line = 0; $line < $headerHeight; $line++) {
            $element = null;
            for($col = 0; $col < count($headers); $col++) {
                $headerLines = explode(PHP_EOL, $headers[$col]);
                $element .= $padCelLine($headerLines[$line] ?? '', $colWidths[$col], $alignments[$col] ?? LEFT);
            }
            $tableArray[] = self::$tableStyles[$borderStyle]['default']['bar'].$element;
        }

        // divider line below headers
        $tableArray[] = $divider('inner');

        // all data rows
        for($row = 0; $row < count($data); $row++) {
            for($line = 0; $line < $rowHeights[$row]; $line++) {
                $element = null;
                for($col = 0; $col < count($data[$row]); $col++) {
                    $rowLines = explode(PHP_EOL, $data[$row][$col]);
                    $element .= $padCelLine($rowLines[$line] ?? '', $colWidths[$col], $alignments[$col] ?? LEFT);
                }
                $tableArray[] = self::$tableStyles[$borderStyle]['default']['bar'].$element;
            }
        }

        // bottom divider line
        $tableArray[] = $divider('bottom');

        // array to string
        return join(PHP_EOL, $tableArray);
    }

    /**
     * String width that handles invisible ANSI characters.
     *
     * @param  String $text The line of text to count
     * @return Int
     */
    public static function mb_strwidth_ansi(String $text): int
    {
        // function to calculate how many backspces (\x08) are in the string
        $backspaceAdjustments = fn ($s) => count(array_filter(mb_str_split($s), fn ($c) => $c == "\x08"));

        // remove escape sequences. this is a bit more general than is used for ansi chars.
        $text = preg_replace('/\x1b(\[|\(|\))[;?0-9]*[0-9A-Za-z]/', "", $text);

        // remove ^c, ^z, ^?
        $text = preg_replace('/[\x03|\x1a|\x7f]/', "", $text);

        return mb_strwidth($text) - $backspaceAdjustments($text);
    }

    /**
     * Converts tabs to spaces on a tab stop model for one line of text, returns string with tabs expanded.
     *
     * @param  String $text     The text with tabs to expand
     * @param  Int    $tabStop  The optional number of spaces in a tab stop, default 8.
     * @return String
     */
    public static function expandTabsOneLine(String $text, Int $tabStop = 8): String
    {
        $expanded = '';
        for($i = 0;$i < count(mb_str_split($text));$i++) {
            $expanded .= mb_str_split($text)[$i] == "\t" ? join(array_fill(0, $tabStop - (mb_strwidth($expanded) % $tabStop), ' ')) : mb_str_split($text)[$i];
        }
        return $expanded;
    }

    /**
     * Converts tabs to spaces on a tab stop model for multi-line text, returns string with tabs expanded.
     *
     * @param  String $text     The text with tabs to expand
     * @param  Int    $tabStop  The optional number of spaces in a tab stop, default 8.
     * @return String
     */
    public static function expandTabs(String $text, Int $tabStop = 8): String
    {
        $lines = explode(PHP_EOL, $text);
        return join(PHP_EOL, array_map(fn($l) => self::expandTabsOneLine($l, $tabStop), $lines));
    }

    /**
     * Validates if the count of elements in all rows of data and headers
     * is the same.
     *
     * @param  Array<String|Int> $headers
     * @param  Array<Array<String|Int>> $data
     * @return bool
     */
    private static function validateColCount(array $headers, array $data): bool
    {
        return count(array_unique(array_map(fn ($d) => count($d), array_merge($data, [$headers])))) == 1;
    }
}
