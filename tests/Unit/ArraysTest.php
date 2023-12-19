<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

use Gbhorwood\Tabletown\Table;

/**
 * ArraysTest
 *
 * Test headers and data arrays as arguments
 */

#[CoversClass(\Gbhorwood\Tabletown\Table::class)]
#[UsesClass(\Gbhorwood\Tabletown\Table::class)]
class ArraysTest extends TestCase
{
    /**
     * Test get($headers, $data) with standard border
     *
     * @dataProvider arraysProviderStandardBorder
     */
    public function testArraysTableStandardBorder($headers, $data, $expected)
    {
        $result = Table::get($headers, $data);
        $this->assertEquals($result, $expected);
    }

    /**
     * Test get($headers, $data) with solid border
     *
     * @dataProvider arraysProviderSolidBorder
     */
    public function testArraysTableSolidBorder($headers, $data, $expected)
    {
        $result = Table::get($headers, $data, TABLE_BORDER_SOLID);
        $this->assertEquals($result, $expected);
    }

    /**
     * Test get($headers, $data) with double border
     *
     * @dataProvider arraysProviderDoubleBorder
     */
    public function testArraysTableDoubleBorder($headers, $data, $expected)
    {
        $result = Table::get($headers, $data, TABLE_BORDER_DOUBLE);
        $this->assertEquals($result, $expected);
    }

    public function testArraysColumnMismatch()
    {
        $this->expectException(\Exception::class);
        Table::get(['head1', 'head2'], [['one', 'two', 'three']]);
    }

    /**
     * Provider for header and data arrays with expected output
     * standard border
     */
    public static function arraysProviderStandardBorder(): array
    {
        return [
            //simple test
            [["artist","title","date"], [["Bratmobile","Pottymouth","1993"],["Coltrane, John","Giant Steps","1959"]], "+----------------+-------------+------+".PHP_EOL."| artist         | title       | date |".PHP_EOL."+----------------+-------------+------+".PHP_EOL."| Bratmobile     | Pottymouth  | 1993 |".PHP_EOL."| Coltrane, John | Giant Steps | 1959 |".PHP_EOL."+----------------+-------------+------+"],
            //numbers in data
            [["artist","title","date"], [["Bratmobile","Pottymouth",1993],["Coltrane, John","Giant Steps",1959]], "+----------------+-------------+------+".PHP_EOL."| artist         | title       | date |".PHP_EOL."+----------------+-------------+------+".PHP_EOL."| Bratmobile     | Pottymouth  | 1993 |".PHP_EOL."| Coltrane, John | Giant Steps | 1959 |".PHP_EOL."+----------------+-------------+------+"],
            //numbers in headers
            [["artist","title",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "+----------------+-------------+----+".PHP_EOL."| artist         | title       | 19 |".PHP_EOL."+----------------+-------------+----+".PHP_EOL."| Bratmobile     | Pottymouth  | 93 |".PHP_EOL."| Coltrane, John | Giant Steps | 59 |".PHP_EOL."+----------------+-------------+----+"],
            //FIX!! multi-line body first col
            [["artist","title",19], [["Bratmobile".PHP_EOL."(Olympia, WA)","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "+----------------+-------------+----+".PHP_EOL."| artist         | title       | 19 |".PHP_EOL."+----------------+-------------+----+".PHP_EOL."| Bratmobile     | Pottymouth  | 93 |".PHP_EOL."| (Olympia, WA)  |             |    |".PHP_EOL."| Coltrane, John | Giant Steps | 59 |".PHP_EOL."+----------------+-------------+----+"],
            //FIX!! multi-line body second col
            [["artist","title",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps".PHP_EOL."(Atlantic)",59]], "+----------------+-------------+----+".PHP_EOL."| artist         | title       | 19 |".PHP_EOL."+----------------+-------------+----+".PHP_EOL."| Bratmobile     | Pottymouth  | 93 |".PHP_EOL."| Coltrane, John | Giant Steps | 59 |".PHP_EOL."|                | (Atlantic)  |    |".PHP_EOL."+----------------+-------------+----+"],
            //FIX!! multi-line header first col
            [["artist".PHP_EOL."(composer)","title",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "+----------------+-------------+----+".PHP_EOL."| artist         | title       | 19 |".PHP_EOL."| (composer)     |             |    |".PHP_EOL."+----------------+-------------+----+".PHP_EOL."| Bratmobile     | Pottymouth  | 93 |".PHP_EOL."| Coltrane, John | Giant Steps | 59 |".PHP_EOL."+----------------+-------------+----+"],
            //FIX!! multi-line header second col
            [["artist","title(album)",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "+----------------+--------------+----+".PHP_EOL."| artist         | title(album) | 19 |".PHP_EOL."+----------------+--------------+----+".PHP_EOL."| Bratmobile     | Pottymouth   | 93 |".PHP_EOL."| Coltrane, John | Giant Steps  | 59 |".PHP_EOL."+----------------+--------------+----+"],
            //unicode 1. apoloties for the google-translate korean
            [["artist","title",19], [["Bratmobile 락앤롤","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "+-------------------+-------------+----+".PHP_EOL."| artist            | title       | 19 |".PHP_EOL."+-------------------+-------------+----+".PHP_EOL."| Bratmobile 락앤롤 | Pottymouth  | 93 |".PHP_EOL."| Coltrane, John    | Giant Steps | 59 |".PHP_EOL."+-------------------+-------------+----+"],
            //unicode 2. apoloties for the google-translate korean
            [["artist","title",19], [["Bratmobile","Pottymouth",93],["Coltrane, John 재즈","Giant Steps",59]], "+---------------------+-------------+----+".PHP_EOL."| artist              | title       | 19 |".PHP_EOL."+---------------------+-------------+----+".PHP_EOL."| Bratmobile          | Pottymouth  | 93 |".PHP_EOL."| Coltrane, John 재즈 | Giant Steps | 59 |".PHP_EOL."+---------------------+-------------+----+"],
            //tabs header
            [["artist	performer","title	album",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "+-------------------+---------------+----+".PHP_EOL."| artist  performer | title   album | 19 |".PHP_EOL."+-------------------+---------------+----+".PHP_EOL."| Bratmobile        | Pottymouth    | 93 |".PHP_EOL."| Coltrane, John    | Giant Steps   | 59 |".PHP_EOL."+-------------------+---------------+----+"],
            //tabs data
            [["artist","title",19], [["Br	atmob	ile","Pottym	outh",93],["Co	ltrane	John","Giant St	eps	",59]], "+----------------------+--------------------------+----+".PHP_EOL."| artist               | title                    | 19 |".PHP_EOL."+----------------------+--------------------------+----+".PHP_EOL."| Br      atmob   ile  | Pottym  outh             | 93 |".PHP_EOL."| Co      ltrane  John | Giant St        eps      | 59 |".PHP_EOL."+----------------------+--------------------------+----+"],
            //tabs and unicode 1. apoloties for the google-translate korean
            [["artist	performer","title	album",19], [["Bratmobile 락	앤롤","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "+----------------------+---------------+----+".PHP_EOL."| artist  performer    | title   album | 19 |".PHP_EOL."+----------------------+---------------+----+".PHP_EOL."| Bratmobile 락   앤롤 | Pottymouth    | 93 |".PHP_EOL."| Coltrane, John       | Giant Steps   | 59 |".PHP_EOL."+----------------------+---------------+----+"],
            //tabs and unicode 2. apoloties for the google-translate korean
            [["artist","title",19], [["Brat	mob	ile","Potty	mouth",93],["Col	trane	John 재	즈","Giant St	eps	",59]], "+----------------------------+--------------------------+----+".PHP_EOL."| artist                     | title                    | 19 |".PHP_EOL."+----------------------------+--------------------------+----+".PHP_EOL."| Brat    mob     ile        | Potty   mouth            | 93 |".PHP_EOL."| Col     trane   John 재 즈 | Giant St        eps      | 59 |".PHP_EOL."+----------------------------+--------------------------+----+"],
            //emoji headers
            [["🚀artist🚀","🚀title🚀","🚀date🚀"], [["Bratmobile","Pottymouth","1993"],["Coltrane, John","Giant Steps","1959"]], "+----------------+-------------+----------+".PHP_EOL."| 🚀artist🚀     | 🚀title🚀   | 🚀date🚀 |".PHP_EOL."+----------------+-------------+----------+".PHP_EOL."| Bratmobile     | Pottymouth  | 1993     |".PHP_EOL."| Coltrane, John | Giant Steps | 1959     |".PHP_EOL."+----------------+-------------+----------+"],
            //emoji data
            [["artist","title","date"], [["Bratmobile🚀","🚀Pottymouth","19🚀93"],["Col🚀trane, 🚀John","Giant 🚀Steps","1959🚀"]], "+--------------------+---------------+--------+".PHP_EOL."| artist             | title         | date   |".PHP_EOL."+--------------------+---------------+--------+".PHP_EOL."| Bratmobile🚀       | 🚀Pottymouth  | 19🚀93 |".PHP_EOL."| Col🚀trane, 🚀John | Giant 🚀Steps | 1959🚀 |".PHP_EOL."+--------------------+---------------+--------+"],
            //tabs and emoji headers
            [["🚀ar	tist🚀","	🚀title	🚀","🚀date🚀	"], [["Bratmobile","Pottymouth","1993"],["Coltrane, John","Giant Steps","1959"]], "+----------------+--------------------+------------------+".PHP_EOL."| 🚀ar    tist🚀 |         🚀title 🚀 | 🚀date🚀         |".PHP_EOL."+----------------+--------------------+------------------+".PHP_EOL."| Bratmobile     | Pottymouth         | 1993             |".PHP_EOL."| Coltrane, John | Giant Steps        | 1959             |".PHP_EOL."+----------------+--------------------+------------------+"],
            //tabs and emoji data
            [["artist","title","date"], [["Bratmobile	🚀","	🚀Pottymouth","19🚀93	"],["Col🚀tran	e, 🚀John","Giant 	🚀Steps	","1	959🚀"]], "+---------------------------+----------------------+---------------+".PHP_EOL."| artist                    | title                | date          |".PHP_EOL."+---------------------------+----------------------+---------------+".PHP_EOL."| Bratmobile      🚀        |         🚀Pottymouth | 19🚀93        |".PHP_EOL."| Col🚀tran       e, 🚀John | Giant   🚀Steps      | 1       959🚀 |".PHP_EOL."+---------------------------+----------------------+---------------+"],
            //ansi test
            [["artist","title","date"], [["Bratmobile","Pottymouth","1993"],["Coltrane, John","Giant Steps","1959"]], "+----------------+-------------+------+".PHP_EOL."| artist         | title       | date |".PHP_EOL."+----------------+-------------+------+".PHP_EOL."| Bratmobile     | Pottymouth  | 1993 |".PHP_EOL."| Coltrane, John | Giant Steps | 1959 |".PHP_EOL."+----------------+-------------+------+"],
        ];
    }

    /**
     * Provider for header and data arrays with expected output
     * solid border
     */
    public static function arraysProviderSolidBorder(): array
    {
        return [
            //simple test
            [["artist","title","date"], [["Bratmobile","Pottymouth","1993"],["Coltrane, John","Giant Steps","1959"]], "┌────────────────┬─────────────┬──────┐".PHP_EOL."│ artist         │ title       │ date │".PHP_EOL."├────────────────┼─────────────┼──────┤".PHP_EOL."│ Bratmobile     │ Pottymouth  │ 1993 │".PHP_EOL."│ Coltrane, John │ Giant Steps │ 1959 │".PHP_EOL."└────────────────┴─────────────┴──────┘"],
            //numbers in data
            [["artist","title","date"], [["Bratmobile","Pottymouth",1993],["Coltrane, John","Giant Steps",1959]], "┌────────────────┬─────────────┬──────┐".PHP_EOL."│ artist         │ title       │ date │".PHP_EOL."├────────────────┼─────────────┼──────┤".PHP_EOL."│ Bratmobile     │ Pottymouth  │ 1993 │".PHP_EOL."│ Coltrane, John │ Giant Steps │ 1959 │".PHP_EOL."└────────────────┴─────────────┴──────┘"],
            //numbers in headers
            [["artist","title",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "┌────────────────┬─────────────┬────┐".PHP_EOL."│ artist         │ title       │ 19 │".PHP_EOL."├────────────────┼─────────────┼────┤".PHP_EOL."│ Bratmobile     │ Pottymouth  │ 93 │".PHP_EOL."│ Coltrane, John │ Giant Steps │ 59 │".PHP_EOL."└────────────────┴─────────────┴────┘"],
            //FIX!! multi-line body first col
            [["artist","title",19], [["Bratmobile".PHP_EOL."(Olympia, WA)","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "┌────────────────┬─────────────┬────┐".PHP_EOL."│ artist         │ title       │ 19 │".PHP_EOL."├────────────────┼─────────────┼────┤".PHP_EOL."│ Bratmobile     │ Pottymouth  │ 93 │".PHP_EOL."│ (Olympia, WA)  │             │    │".PHP_EOL."│ Coltrane, John │ Giant Steps │ 59 │".PHP_EOL."└────────────────┴─────────────┴────┘"],
            //FIX!! multi-line body second col
            [["artist","title",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps".PHP_EOL."(Atlantic)",59]], "┌────────────────┬─────────────┬────┐".PHP_EOL."│ artist         │ title       │ 19 │".PHP_EOL."├────────────────┼─────────────┼────┤".PHP_EOL."│ Bratmobile     │ Pottymouth  │ 93 │".PHP_EOL."│ Coltrane, John │ Giant Steps │ 59 │".PHP_EOL."│                │ (Atlantic)  │    │".PHP_EOL."└────────────────┴─────────────┴────┘"],
            //FIX!! multi-line header first col
            [["artist".PHP_EOL."(composer)","title",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "┌────────────────┬─────────────┬────┐".PHP_EOL."│ artist         │ title       │ 19 │".PHP_EOL."│ (composer)     │             │    │".PHP_EOL."├────────────────┼─────────────┼────┤".PHP_EOL."│ Bratmobile     │ Pottymouth  │ 93 │".PHP_EOL."│ Coltrane, John │ Giant Steps │ 59 │".PHP_EOL."└────────────────┴─────────────┴────┘"],
            //FIX!! multi-line header second col
            [["artist","title(album)",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "┌────────────────┬──────────────┬────┐".PHP_EOL."│ artist         │ title(album) │ 19 │".PHP_EOL."├────────────────┼──────────────┼────┤".PHP_EOL."│ Bratmobile     │ Pottymouth   │ 93 │".PHP_EOL."│ Coltrane, John │ Giant Steps  │ 59 │".PHP_EOL."└────────────────┴──────────────┴────┘"],
            //unicode 1. apoloties for the google-translate korean
            [["artist","title",19], [["Bratmobile 락앤롤","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "┌───────────────────┬─────────────┬────┐".PHP_EOL."│ artist            │ title       │ 19 │".PHP_EOL."├───────────────────┼─────────────┼────┤".PHP_EOL."│ Bratmobile 락앤롤 │ Pottymouth  │ 93 │".PHP_EOL."│ Coltrane, John    │ Giant Steps │ 59 │".PHP_EOL."└───────────────────┴─────────────┴────┘"],
            //unicode 2. apoloties for the google-translate korean
            [["artist","title",19], [["Bratmobile","Pottymouth",93],["Coltrane, John 재즈","Giant Steps",59]], "┌─────────────────────┬─────────────┬────┐".PHP_EOL."│ artist              │ title       │ 19 │".PHP_EOL."├─────────────────────┼─────────────┼────┤".PHP_EOL."│ Bratmobile          │ Pottymouth  │ 93 │".PHP_EOL."│ Coltrane, John 재즈 │ Giant Steps │ 59 │".PHP_EOL."└─────────────────────┴─────────────┴────┘"],
            //tabs header
            [["artist	performer","title	album",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "┌───────────────────┬───────────────┬────┐".PHP_EOL."│ artist  performer │ title   album │ 19 │".PHP_EOL."├───────────────────┼───────────────┼────┤".PHP_EOL."│ Bratmobile        │ Pottymouth    │ 93 │".PHP_EOL."│ Coltrane, John    │ Giant Steps   │ 59 │".PHP_EOL."└───────────────────┴───────────────┴────┘"],
            //tabs data
            [["artist","title",19], [["Br	atmob	ile","Pottym	outh",93],["Co	ltrane	John","Giant St	eps	",59]], "┌──────────────────────┬──────────────────────────┬────┐".PHP_EOL."│ artist               │ title                    │ 19 │".PHP_EOL."├──────────────────────┼──────────────────────────┼────┤".PHP_EOL."│ Br      atmob   ile  │ Pottym  outh             │ 93 │".PHP_EOL."│ Co      ltrane  John │ Giant St        eps      │ 59 │".PHP_EOL."└──────────────────────┴──────────────────────────┴────┘"],
            //tabs and unicode 1. apoloties for the google-translate korean
            [["artist	performer","title	album",19], [["Bratmobile 락	앤롤","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "┌──────────────────────┬───────────────┬────┐".PHP_EOL."│ artist  performer    │ title   album │ 19 │".PHP_EOL."├──────────────────────┼───────────────┼────┤".PHP_EOL."│ Bratmobile 락   앤롤 │ Pottymouth    │ 93 │".PHP_EOL."│ Coltrane, John       │ Giant Steps   │ 59 │".PHP_EOL."└──────────────────────┴───────────────┴────┘"],
            //tabs and unicode 2. apoloties for the google-translate korean
            [["artist","title",19], [["Brat	mob	ile","Potty	mouth",93],["Col	trane	John 재	즈","Giant St	eps	",59]], "┌────────────────────────────┬──────────────────────────┬────┐".PHP_EOL."│ artist                     │ title                    │ 19 │".PHP_EOL."├────────────────────────────┼──────────────────────────┼────┤".PHP_EOL."│ Brat    mob     ile        │ Potty   mouth            │ 93 │".PHP_EOL."│ Col     trane   John 재 즈 │ Giant St        eps      │ 59 │".PHP_EOL."└────────────────────────────┴──────────────────────────┴────┘"],
            //emoji headers
            [["🚀artist🚀","🚀title🚀","🚀date🚀"], [["Bratmobile","Pottymouth","1993"],["Coltrane, John","Giant Steps","1959"]], "┌────────────────┬─────────────┬──────────┐".PHP_EOL."│ 🚀artist🚀     │ 🚀title🚀   │ 🚀date🚀 │".PHP_EOL."├────────────────┼─────────────┼──────────┤".PHP_EOL."│ Bratmobile     │ Pottymouth  │ 1993     │".PHP_EOL."│ Coltrane, John │ Giant Steps │ 1959     │".PHP_EOL."└────────────────┴─────────────┴──────────┘"],
            //emoji data
            [["artist","title","date"], [["Bratmobile🚀","🚀Pottymouth","19🚀93"],["Col🚀trane, 🚀John","Giant 🚀Steps","1959🚀"]], "┌────────────────────┬───────────────┬────────┐".PHP_EOL."│ artist             │ title         │ date   │".PHP_EOL."├────────────────────┼───────────────┼────────┤".PHP_EOL."│ Bratmobile🚀       │ 🚀Pottymouth  │ 19🚀93 │".PHP_EOL."│ Col🚀trane, 🚀John │ Giant 🚀Steps │ 1959🚀 │".PHP_EOL."└────────────────────┴───────────────┴────────┘"],
            //tabs and emoji headers
            [["🚀ar	tist🚀","	🚀title	🚀","🚀date🚀	"], [["Bratmobile","Pottymouth","1993"],["Coltrane, John","Giant Steps","1959"]], "┌────────────────┬────────────────────┬──────────────────┐".PHP_EOL."│ 🚀ar    tist🚀 │         🚀title 🚀 │ 🚀date🚀         │".PHP_EOL."├────────────────┼────────────────────┼──────────────────┤".PHP_EOL."│ Bratmobile     │ Pottymouth         │ 1993             │".PHP_EOL."│ Coltrane, John │ Giant Steps        │ 1959             │".PHP_EOL."└────────────────┴────────────────────┴──────────────────┘"],
            //tabs and emoji data
            [["artist","title","date"], [["Bratmobile	🚀","	🚀Pottymouth","19🚀93	"],["Col🚀tran	e, 🚀John","Giant 	🚀Steps	","1	959🚀"]], "┌───────────────────────────┬──────────────────────┬───────────────┐".PHP_EOL."│ artist                    │ title                │ date          │".PHP_EOL."├───────────────────────────┼──────────────────────┼───────────────┤".PHP_EOL."│ Bratmobile      🚀        │         🚀Pottymouth │ 19🚀93        │".PHP_EOL."│ Col🚀tran       e, 🚀John │ Giant   🚀Steps      │ 1       959🚀 │".PHP_EOL."└───────────────────────────┴──────────────────────┴───────────────┘"],
            //ansi test
            [["artist","title","date"], [["Bratmobile","Pottymouth","1993"],["Coltrane, John","Giant Steps","1959"]], "┌────────────────┬─────────────┬──────┐".PHP_EOL."│ artist         │ title       │ date │".PHP_EOL."├────────────────┼─────────────┼──────┤".PHP_EOL."│ Bratmobile     │ Pottymouth  │ 1993 │".PHP_EOL."│ Coltrane, John │ Giant Steps │ 1959 │".PHP_EOL."└────────────────┴─────────────┴──────┘"],
        ];
    }

    /**
     * Provider for header and data arrays with expected output
     * double border
     */
    public static function arraysProviderDoubleBorder(): array
    {
        return [
            //simple test
            [["artist","title","date"], [["Bratmobile","Pottymouth","1993"],["Coltrane, John","Giant Steps","1959"]], "╔════════════════╦═════════════╦══════╗".PHP_EOL."║ artist         ║ title       ║ date ║".PHP_EOL."╠════════════════╬═════════════╬══════╣".PHP_EOL."║ Bratmobile     ║ Pottymouth  ║ 1993 ║".PHP_EOL."║ Coltrane, John ║ Giant Steps ║ 1959 ║".PHP_EOL."╚════════════════╩═════════════╩══════╝"],
            //numbers in data
            [["artist","title","date"], [["Bratmobile","Pottymouth",1993],["Coltrane, John","Giant Steps",1959]], "╔════════════════╦═════════════╦══════╗".PHP_EOL."║ artist         ║ title       ║ date ║".PHP_EOL."╠════════════════╬═════════════╬══════╣".PHP_EOL."║ Bratmobile     ║ Pottymouth  ║ 1993 ║".PHP_EOL."║ Coltrane, John ║ Giant Steps ║ 1959 ║".PHP_EOL."╚════════════════╩═════════════╩══════╝"],
            //numbers in headers
            [["artist","title",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "╔════════════════╦═════════════╦════╗".PHP_EOL."║ artist         ║ title       ║ 19 ║".PHP_EOL."╠════════════════╬═════════════╬════╣".PHP_EOL."║ Bratmobile     ║ Pottymouth  ║ 93 ║".PHP_EOL."║ Coltrane, John ║ Giant Steps ║ 59 ║".PHP_EOL."╚════════════════╩═════════════╩════╝"],
            //FIX!! multi-line body first col
            [["artist","title",19], [["Bratmobile".PHP_EOL."(Olympia, WA)","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "╔════════════════╦═════════════╦════╗".PHP_EOL."║ artist         ║ title       ║ 19 ║".PHP_EOL."╠════════════════╬═════════════╬════╣".PHP_EOL."║ Bratmobile     ║ Pottymouth  ║ 93 ║".PHP_EOL."║ (Olympia, WA)  ║             ║    ║".PHP_EOL."║ Coltrane, John ║ Giant Steps ║ 59 ║".PHP_EOL."╚════════════════╩═════════════╩════╝"],
            //FIX!! multi-line body second col
            [["artist","title",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps".PHP_EOL."(Atlantic)",59]], "╔════════════════╦═════════════╦════╗".PHP_EOL."║ artist         ║ title       ║ 19 ║".PHP_EOL."╠════════════════╬═════════════╬════╣".PHP_EOL."║ Bratmobile     ║ Pottymouth  ║ 93 ║".PHP_EOL."║ Coltrane, John ║ Giant Steps ║ 59 ║".PHP_EOL."║                ║ (Atlantic)  ║    ║".PHP_EOL."╚════════════════╩═════════════╩════╝"],
            //FIX!! multi-line header first col
            [["artist".PHP_EOL."(composer)","title",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "╔════════════════╦═════════════╦════╗".PHP_EOL."║ artist         ║ title       ║ 19 ║".PHP_EOL."║ (composer)     ║             ║    ║".PHP_EOL."╠════════════════╬═════════════╬════╣".PHP_EOL."║ Bratmobile     ║ Pottymouth  ║ 93 ║".PHP_EOL."║ Coltrane, John ║ Giant Steps ║ 59 ║".PHP_EOL."╚════════════════╩═════════════╩════╝"],
            //FIX!! multi-line header second col
            [["artist","title(album)",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "╔════════════════╦══════════════╦════╗".PHP_EOL."║ artist         ║ title(album) ║ 19 ║".PHP_EOL."╠════════════════╬══════════════╬════╣".PHP_EOL."║ Bratmobile     ║ Pottymouth   ║ 93 ║".PHP_EOL."║ Coltrane, John ║ Giant Steps  ║ 59 ║".PHP_EOL."╚════════════════╩══════════════╩════╝"],
            //unicode 1. apoloties for the google-translate korean
            [["artist","title",19], [["Bratmobile 락앤롤","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "╔═══════════════════╦═════════════╦════╗".PHP_EOL."║ artist            ║ title       ║ 19 ║".PHP_EOL."╠═══════════════════╬═════════════╬════╣".PHP_EOL."║ Bratmobile 락앤롤 ║ Pottymouth  ║ 93 ║".PHP_EOL."║ Coltrane, John    ║ Giant Steps ║ 59 ║".PHP_EOL."╚═══════════════════╩═════════════╩════╝"],
            //unicode 2. apoloties for the google-translate korean
            [["artist","title",19], [["Bratmobile","Pottymouth",93],["Coltrane, John 재즈","Giant Steps",59]], "╔═════════════════════╦═════════════╦════╗".PHP_EOL."║ artist              ║ title       ║ 19 ║".PHP_EOL."╠═════════════════════╬═════════════╬════╣".PHP_EOL."║ Bratmobile          ║ Pottymouth  ║ 93 ║".PHP_EOL."║ Coltrane, John 재즈 ║ Giant Steps ║ 59 ║".PHP_EOL."╚═════════════════════╩═════════════╩════╝"],
            //tabs header
            [["artist	performer","title	album",19], [["Bratmobile","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "╔═══════════════════╦═══════════════╦════╗".PHP_EOL."║ artist  performer ║ title   album ║ 19 ║".PHP_EOL."╠═══════════════════╬═══════════════╬════╣".PHP_EOL."║ Bratmobile        ║ Pottymouth    ║ 93 ║".PHP_EOL."║ Coltrane, John    ║ Giant Steps   ║ 59 ║".PHP_EOL."╚═══════════════════╩═══════════════╩════╝"],
            //tabs data
            [["artist","title",19], [["Br	atmob	ile","Pottym	outh",93],["Co	ltrane	John","Giant St	eps	",59]], "╔══════════════════════╦══════════════════════════╦════╗".PHP_EOL."║ artist               ║ title                    ║ 19 ║".PHP_EOL."╠══════════════════════╬══════════════════════════╬════╣".PHP_EOL."║ Br      atmob   ile  ║ Pottym  outh             ║ 93 ║".PHP_EOL."║ Co      ltrane  John ║ Giant St        eps      ║ 59 ║".PHP_EOL."╚══════════════════════╩══════════════════════════╩════╝"],
            //tabs and unicode 1. apoloties for the google-translate korean
            [["artist	performer","title	album",19], [["Bratmobile 락	앤롤","Pottymouth",93],["Coltrane, John","Giant Steps",59]], "╔══════════════════════╦═══════════════╦════╗".PHP_EOL."║ artist  performer    ║ title   album ║ 19 ║".PHP_EOL."╠══════════════════════╬═══════════════╬════╣".PHP_EOL."║ Bratmobile 락   앤롤 ║ Pottymouth    ║ 93 ║".PHP_EOL."║ Coltrane, John       ║ Giant Steps   ║ 59 ║".PHP_EOL."╚══════════════════════╩═══════════════╩════╝"],
            //tabs and unicode 2. apoloties for the google-translate korean
            [["artist","title",19], [["Brat	mob	ile","Potty	mouth",93],["Col	trane	John 재	즈","Giant St	eps	",59]], "╔════════════════════════════╦══════════════════════════╦════╗".PHP_EOL."║ artist                     ║ title                    ║ 19 ║".PHP_EOL."╠════════════════════════════╬══════════════════════════╬════╣".PHP_EOL."║ Brat    mob     ile        ║ Potty   mouth            ║ 93 ║".PHP_EOL."║ Col     trane   John 재 즈 ║ Giant St        eps      ║ 59 ║".PHP_EOL."╚════════════════════════════╩══════════════════════════╩════╝"],
            //emoji headers
            [["🚀artist🚀","🚀title🚀","🚀date🚀"], [["Bratmobile","Pottymouth","1993"],["Coltrane, John","Giant Steps","1959"]], "╔════════════════╦═════════════╦══════════╗".PHP_EOL."║ 🚀artist🚀     ║ 🚀title🚀   ║ 🚀date🚀 ║".PHP_EOL."╠════════════════╬═════════════╬══════════╣".PHP_EOL."║ Bratmobile     ║ Pottymouth  ║ 1993     ║".PHP_EOL."║ Coltrane, John ║ Giant Steps ║ 1959     ║".PHP_EOL."╚════════════════╩═════════════╩══════════╝"],
            //emoji data
            [["artist","title","date"], [["Bratmobile🚀","🚀Pottymouth","19🚀93"],["Col🚀trane, 🚀John","Giant 🚀Steps","1959🚀"]], "╔════════════════════╦═══════════════╦════════╗".PHP_EOL."║ artist             ║ title         ║ date   ║".PHP_EOL."╠════════════════════╬═══════════════╬════════╣".PHP_EOL."║ Bratmobile🚀       ║ 🚀Pottymouth  ║ 19🚀93 ║".PHP_EOL."║ Col🚀trane, 🚀John ║ Giant 🚀Steps ║ 1959🚀 ║".PHP_EOL."╚════════════════════╩═══════════════╩════════╝"],
            //tabs and emoji headers
            [["🚀ar	tist🚀","	🚀title	🚀","🚀date🚀	"], [["Bratmobile","Pottymouth","1993"],["Coltrane, John","Giant Steps","1959"]], "╔════════════════╦════════════════════╦══════════════════╗".PHP_EOL."║ 🚀ar    tist🚀 ║         🚀title 🚀 ║ 🚀date🚀         ║".PHP_EOL."╠════════════════╬════════════════════╬══════════════════╣".PHP_EOL."║ Bratmobile     ║ Pottymouth         ║ 1993             ║".PHP_EOL."║ Coltrane, John ║ Giant Steps        ║ 1959             ║".PHP_EOL."╚════════════════╩════════════════════╩══════════════════╝"],
            //tabs and emoji data
            [["artist","title","date"], [["Bratmobile	🚀","	🚀Pottymouth","19🚀93	"],["Col🚀tran	e, 🚀John","Giant 	🚀Steps	","1	959🚀"]], "╔═══════════════════════════╦══════════════════════╦═══════════════╗".PHP_EOL."║ artist                    ║ title                ║ date          ║".PHP_EOL."╠═══════════════════════════╬══════════════════════╬═══════════════╣".PHP_EOL."║ Bratmobile      🚀        ║         🚀Pottymouth ║ 19🚀93        ║".PHP_EOL."║ Col🚀tran       e, 🚀John ║ Giant   🚀Steps      ║ 1       959🚀 ║".PHP_EOL."╚═══════════════════════════╩══════════════════════╩═══════════════╝"],
            //ansi test
            [["artist","title","date"], [["Bratmobile","Pottymouth","1993"],["Coltrane, John","Giant Steps","1959"]], "╔════════════════╦═════════════╦══════╗".PHP_EOL."║ artist         ║ title       ║ date ║".PHP_EOL."╠════════════════╬═════════════╬══════╣".PHP_EOL."║ Bratmobile     ║ Pottymouth  ║ 1993 ║".PHP_EOL."║ Coltrane, John ║ Giant Steps ║ 1959 ║".PHP_EOL."╚════════════════╩═════════════╩══════╝"],
        ];
    }
}
