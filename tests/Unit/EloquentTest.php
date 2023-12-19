<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

use Gbhorwood\Tabletown\Table;

/**
 * EloquentTest
 *
 * Test eloquent collection of models as argument
 */

#[CoversClass(\Gbhorwood\Tabletown\Table::class)]
#[UsesClass(\Gbhorwood\Tabletown\Table::class)]
class EloquentTest extends TestCase
{
    /**
     * Test get($\Illuminate\Database\Eloquent\Collection) with standard border
     *
     */
    public function testEloquentTableStandardBorder()
    {
        $expected = "+----+----------------+-------------+------+".PHP_EOL."| id | artist         | album       | date |".PHP_EOL."+----+----------------+-------------+------+".PHP_EOL."| 1  | Bratmobile     | Pottymouth  | 1993 |".PHP_EOL."| 2  | Coltrane, John | Giant Steps | 1959 |".PHP_EOL."+----+----------------+-------------+------+";

        $c = new \Illuminate\Database\Eloquent\Collection();
        $c->add(new Album(['id' => 1, 'artist' => 'Bratmobile', 'album' => 'Pottymouth', 'date' => 1993]));
        $c->add(new Album(['id' => 2, 'artist' => 'Coltrane, John', 'album' => 'Giant Steps', 'date' => 1959]));

        $result = Table::get($c);
        $this->assertEquals($result, $expected);

        $result = Table::get($c, TABLE_BORDER_STANDARD);
        $this->assertEquals($result, $expected);
    }

    /**
     * Test get($\Illuminate\Database\Eloquent\Collection) with solid border
     *
     */
    public function testEloquentTableSolidBorder()
    {
        $expected = "┌────┬────────────────┬─────────────┬──────┐".PHP_EOL."│ id │ artist         │ album       │ date │".PHP_EOL."├────┼────────────────┼─────────────┼──────┤".PHP_EOL."│ 1  │ Bratmobile     │ Pottymouth  │ 1993 │".PHP_EOL."│ 2  │ Coltrane, John │ Giant Steps │ 1959 │".PHP_EOL."└────┴────────────────┴─────────────┴──────┘";

        $c = new \Illuminate\Database\Eloquent\Collection();
        $c->add(new Album(['id' => 1, 'artist' => 'Bratmobile', 'album' => 'Pottymouth', 'date' => 1993]));
        $c->add(new Album(['id' => 2, 'artist' => 'Coltrane, John', 'album' => 'Giant Steps', 'date' => 1959]));

        $result = Table::get($c, TABLE_BORDER_SOLID);
        $this->assertEquals($result, $expected);
    }

    /**
     * Test get($\Illuminate\Database\Eloquent\Collection) with double border
     *
     */
    public function testEloquentTableDoubleBorder()
    {
        $expected = "╔════╦════════════════╦═════════════╦══════╗".PHP_EOL."║ id ║ artist         ║ album       ║ date ║".PHP_EOL."╠════╬════════════════╬═════════════╬══════╣".PHP_EOL."║ 1  ║ Bratmobile     ║ Pottymouth  ║ 1993 ║".PHP_EOL."║ 2  ║ Coltrane, John ║ Giant Steps ║ 1959 ║".PHP_EOL."╚════╩════════════════╩═════════════╩══════╝";

        $c = new \Illuminate\Database\Eloquent\Collection();
        $c->add(new Album(['id' => 1, 'artist' => 'Bratmobile', 'album' => 'Pottymouth', 'date' => 1993]));
        $c->add(new Album(['id' => 2, 'artist' => 'Coltrane, John', 'album' => 'Giant Steps', 'date' => 1959]));

        $result = Table::get($c, TABLE_BORDER_DOUBLE);
        $this->assertEquals($result, $expected);
    }
}

/**
 * Album model
 *
 */
class Album extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "albums";
    protected $fillable = ['id', 'artist', 'album', 'date'];
}
