<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

use Gbhorwood\Tabletown\Table;

/**
 * ExpandTabsTest
 *
 * Test expanding tabs to spaces
 */

#[CoversClass(\Gbhorwood\Tabletown\Table::class)]
#[UsesClass(\Gbhorwood\Tabletown\Table::class)]
class ExpandTabsTest extends TestCase
{
    /**
     * Test expandTabs($text)
     *
     * @dataProvider expandTabsProvider
     */
    public function testArraysTableStandardBorder($text, $expected)
    {
        $result = Table::expandTabs($text);
        $this->assertEquals($result, $expected);
    }

    /**
     * Provider for expanding tabs test
     *
     */
    public static function expandTabsProvider(): array
    {
        return [
            ["some\ttest", "some    test"],
            ["\tsome\ttest", "        some    test"],
            ["\tsome\ttest\t", "        some    test    "],
            ["\tsometest\t", "        sometest        "],
            ["som\t🚀etest\t", "som     🚀etest "],
            ["som\t🚀etest".PHP_EOL."second\tline", "som     🚀etest".PHP_EOL."second  line"],

        ];
    }
}
