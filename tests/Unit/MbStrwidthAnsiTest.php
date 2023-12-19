<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

use Gbhorwood\Tabletown\Table;

/**
 * MbStrwidthAnsiTest
 *
 * Tests multi-byte, ansi-safe string width
 */

#[CoversClass(\Gbhorwood\Tabletown\Table::class)]
#[UsesClass(\Gbhorwood\Tabletown\Table::class)]
class MbStrwidthAnsiTest extends TestCase
{
    /**
     * Test mb_strwidth_ansi($text)
     *
     * @dataProvider strwidthProvider
     */
    public function testArraysTableStandardBorder($text, $width)
    {
        $result = Table::mb_strwidth_ansi($text);
        $this->assertEquals($width, $result);
    }

    /**
     * Provider for testing ansi-safe multi-byte string width
     *
     */
    public static function strwidthProvider(): array
    {
        return [
            // no ansi
            ["no ansi string", 14],
            // simple bold
            ["i am \033[1mbold\033[0m", 9],

        ];
    }
}
