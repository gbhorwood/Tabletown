<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

use Gbhorwood\Tabletown\Table;

/**
 * NullTest
 *
 * Tests bad args return null
 */

#[CoversClass(\Gbhorwood\Tabletown\Table::class)]
#[UsesClass(\Gbhorwood\Tabletown\Table::class)]
class NullTest extends TestCase
{
    /**
     * Test invalid args return null
     *
     */
    public function testNull()
    {
        $expected = null;

        $result = Table::get('foo');
        $this->assertEquals($result, $expected);

        $result = Table::get(9);
        $this->assertEquals($result, $expected);

        $result = Table::get((object)"");
        $this->assertEquals($result, $expected);

        $result = Table::get((object)[]);
        $this->assertEquals($result, $expected);
    }

}
