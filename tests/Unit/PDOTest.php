<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

use Gbhorwood\Tabletown\Table;

/**
 * PDOTest
 *
 * Test pdo statement as argument
 */

#[CoversClass(\Gbhorwood\Tabletown\Table::class)]
#[UsesClass(\Gbhorwood\Tabletown\Table::class)]
class PDOTest extends TestCase
{
    /**
     * Create and scaffold sqlite in memory table
     *
     * @return \PDO
     */
    private function setDb():\PDO
    {
        $pdo = new \PDO('sqlite::memory:');

        $table =<<<SQL
        CREATE TABLE `albums` (
            `id`        bigint unsigned NOT NULL,
            `artist`    varchar(32) NULL,
            `name`      varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
        )
        SQL;
        $pdo->exec($table);

        $insertSql =<<<SQL
        INSERT
        INTO    albums
        VALUES  (1, 'Bratmobile', 'Pottymouth'),
                (2, 'Coltrane, John', 'Giant Steps')
        SQL;
        $pdo->exec($insertSql);

        return $pdo;
    }

    /**
     * Test get($PDOStatement) with standard border
     *
     */
    public function testPDOTableStandardBorder()
    {
        $expected = "+----+----------------+-------------+".PHP_EOL."| id | artist         | name        |".PHP_EOL."+----+----------------+-------------+".PHP_EOL."| 1  | Bratmobile     | Pottymouth  |".PHP_EOL."| 2  | Coltrane, John | Giant Steps |".PHP_EOL."+----+----------------+-------------+";
        $pdo = $this->setDb();

        $result = $pdo->query("select * from albums");
        $table = Table::get($result);
        $this->assertEquals($table, $expected);

        $result = $pdo->query("select * from albums");
        $table = Table::get($result, TABLE_BORDER_STANDARD);
        $this->assertEquals($table, $expected);
    }

    /**
     * Test get($PDOStatement) with solid border
     *
     */
    public function testPDOTableSolidBorder()
    {
        $expected = "┌────┬────────────────┬─────────────┐".PHP_EOL."│ id │ artist         │ name        │".PHP_EOL."├────┼────────────────┼─────────────┤".PHP_EOL."│ 1  │ Bratmobile     │ Pottymouth  │".PHP_EOL."│ 2  │ Coltrane, John │ Giant Steps │".PHP_EOL."└────┴────────────────┴─────────────┘";
        $pdo = $this->setDb();

        $result = $pdo->query("select * from albums");
        $table = Table::get($result, TABLE_BORDER_SOLID);

        $this->assertEquals($table, $expected);
        $this->assertEquals(2,2);
    }

    /**
     * Test get($PDOStatement) with double border
     *
     */
    public function testPDOTableDoubleBorder()
    {
        $expected = "╔════╦════════════════╦═════════════╗".PHP_EOL."║ id ║ artist         ║ name        ║".PHP_EOL."╠════╬════════════════╬═════════════╣".PHP_EOL."║ 1  ║ Bratmobile     ║ Pottymouth  ║".PHP_EOL."║ 2  ║ Coltrane, John ║ Giant Steps ║".PHP_EOL."╚════╩════════════════╩═════════════╝";
        $pdo = $this->setDb();

        $result = $pdo->query("select * from albums");
        $table = Table::get($result, TABLE_BORDER_DOUBLE);

        $this->assertEquals($table, $expected);
        $this->assertEquals(2,2);
    }
}
