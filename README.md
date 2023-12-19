# Tabletown
[![License](http://poser.pugx.org/gbhorwood/tabletown/license)](https://packagist.org/packages/gbhorwood/tabletown)
[![Version](http://poser.pugx.org/gbhorwood/tabletown/version)](https://packagist.org/packages/gbhorwood/tabletown)
[![PHP Version Require](http://poser.pugx.org/gbhorwood/tabletown/require/php)](https://packagist.org/packages/gbhorwood/tabletown)

Tabletown is a php package for converting data, such as arrays, into nice ascii/ansi tables, similar to MySql's standard output.

Tabletown can accept input from arrays, PDO statements, and eloquent collections. It properly handles multibyte characters, including emojis, as well as unprintable ansi character sequences and tabs. It allows setting per-column alignment and offers a number of border style options.

Tabletown was developed as an internal-use tool for fruitbat studios/cloverhitch technologies/kludgetastic implementations.

## Install
Tabletown is installed via composer:

```shell
composer require gbhorwood/tabletown
```

## Features
Tabletown's basic features are:

* **Multiple input types:** Input can be arrays, `PDOStatement` objects or Eloquent collections.
* **Border styling:** Broders can be set as either solid lined, double solid lined or standard, MySql-style.
* **Per-column alignment:** Individual columns can be aligned left, right, or centre.
* **Multi-line rows** Line breaks in data are preseved and presented as multi-line rows.
* **Unicode-safe:** Multi-byte characters are handled.
* **Tab-safe:** Tab characters are handled with proper tab stops.
* **(mostly) ANSI-safe:** Most ansi escape codes, including backspace, are handled.

## Quickstart
Tabletown has one static method `get()`. The fastest way to create and print a table is to provide the `get()` method with two arguments: an array of headers, and an array of data for table rows.

```php
<?php
require __DIR__ . "/vendor/autoload.php";

use Gbhorwood\Tabletown\Table;

// array of column headers
$headers = ['Artist', 'Title', 'Year'];

// array of arrays, one for each row in the table
$rows = [
    ['Bratmobile', 'Pottymouth', 1993],
    ['Coltrane, John', 'Giant Steps', 1959],
];

// get and output the table
$myTable = Table::get($headers, $rows);

print $myTable;
```

The above example will output the table:

```
+----------------+-------------+------+
| Artist         | Title       | Year |
+----------------+-------------+------+
| Bratmobile     | Pottymouth  | 1993 |
| Coltrane, John | Giant Steps | 1959 |
+----------------+-------------+------+
```

## Building tables from arrays
Tables can be built from arrays in one of two ways: either with two separate arrays for the headers and rows, or with one associative array.

### Using separate arrays for headers and rows

```php
// array of column headers
$headers = ['Artist', 'Title', 'Year'];

// array of arrays, one for each row in the table
$rows = [
    ['Bratmobile', 'Pottymouth', 1993],
    ['Coltrane, John', 'Giant Steps', 1959],
];

$myTable = Table::get($headers, $rows);
```

### Using one associative array
```php

$data = [
    [
        'Artist' => 'Bratmobile',
        'Title' => 'Pottymouth',
        'Year' => 1993
    ],
    [
        'Artist' => 'Coltrane, John',
        'Title' => 'Giant Steps',
        'Year' => 1959
    ],
];

$myTable = Table::get($data);
```

**Note:** Tabletown will throw an exception if the column counts do not match for all rows.

## Building tables from PDO statements
Tabletown can build tables from a `PDOStatement` object returned from the PDO `query()` method:

```php
$dsn = "mysql:host=<hostname>;dbname=<dbname>;charset=UTF8";
$pdo = new PDO($dsn, "<username>", "<password>");

$stmt = $pdo->query("SELECT * FROM albums");

$myTable = Table::get($stmt);
```

## Building tables from Eloquent collections
Eloquent collection objects can be used to build tables:

```php
$albums = Album::all();

$myTable = Table::get($albums);
```

## Styling borders
Tabletown has three different types of borders that can be set by passing one of the border constants as an argument. The border style constants are:

* `TABLE_BORDER_STANDARD` The default MySql-style border. If no border style is supplied, the standard border is used.
* `TABLE_BORDER_SOLID` A border made of solid lines.
* `TABLE_BORDER_DOUBLE` A border made of double solid lines.

The usage and output of the border styles is:

### `TABLE_BORDER_STANDARD`

```php
print Table::get($headers, $rows, TABLE_BORDER_STANDARD); // or
print Table::get($dataArray, TABLE_BORDER_STANDARD); // or
print Table::get($pdoStatement, TABLE_BORDER_STANDARD); // or
print Table::get($eloquentCollection, TABLE_BORDER_STANDARD);
```

```
+----------------+-------------+------+
| Artist         | Title       | Year |
+----------------+-------------+------+
| Bratmobile     | Pottymouth  | 1993 |
| Coltrane, John | Giant Steps | 1959 |
+----------------+-------------+------+
```

### `TABLE_BORDER_SOLID`

```php
print Table::get($headers, $rows, TABLE_BORDER_SOLID); // or
print Table::get($dataArray, TABLE_BORDER_SOLID); // or
print Table::get($pdoStatement, TABLE_BORDER_SOLID); // or
print Table::get($eloquentCollection, TABLE_BORDER_SOLID);
```

```
┌────────────────┬─────────────┬──────┐
│ Artist         │ Title       │ Year │
├────────────────┼─────────────┼──────┤
│ Bratmobile     │ Pottymouth  │ 1993 │
│ Coltrane, John │ Giant Steps │ 1959 │
└────────────────┴─────────────┴──────┘
```

### `TABLE_BORDER_DOUBLE`

```php
print Table::get($headers, $rows, TABLE_BORDER_DOUBLE); // or
print Table::get($dataArray, TABLE_BORDER_DOUBLE); // or
print Table::get($pdoStatement, TABLE_BORDER_DOUBLE); // or
print Table::get($eloquentCollection, TABLE_BORDER_DOUBLE);
```

```
╔════════════════╦═════════════╦══════╗
║ Artist         ║ Title       ║ Year ║
╠════════════════╬═════════════╬══════╣
║ Bratmobile     ║ Pottymouth  ║ 1993 ║
║ Coltrane, John ║ Giant Steps ║ 1959 ║
╚════════════════╩═════════════╩══════╝
```

## Aligning columns
Columns in tables are left-aligned by default. Aligments can be changed by passing an array of alignment constants after the border style constant argument.

The valid alignment constants are:

* `LEFT`
* `RIGHT`
* `CENTRE`
* `CENTER`

Note that null values are considered `LEFT`.

```php
$headers = ['Artist', 'Title', 'Year'];
$rows = [
    ['Bratmobile', 'Pottymouth', 1993],
    ['Coltrane, John', 'Giant Steps', 1959],
];

print Table::get($headers, $rows, TABLE_BORDER_STANDARD, [RIGHT, LEFT, CENTRE]);
```

The above example will output:

```
+----------------+-------------+------+
|         Artist | Title       | Year |
+----------------+-------------+------+
|     Bratmobile | Pottymouth  | 1993 |
| Coltrane, John | Giant Steps | 1959 |
+----------------+-------------+------+
```

## Multiple line handling
Tabletown perserves new lines in the input data, creating multi-line rows in the table.

For instance, this example creates rows with pretty-printed json

```php
$headers = ['id', 'some_json'];
$rows = [
    [1, json_encode(['artist' => 'Bratmobile', 'title' => 'Pottymouth'], JSON_PRETTY_PRINT)],
    [2, json_encode(['artist' => 'Coltrane, John', 'title' => 'Giant Steps'], JSON_PRETTY_PRINT)],
];

$myTable = Table::get($headers, $rows);
print $myTable;
```

and outputs:

```
+----+---------------------------------+
| id | some_json                       |
+----+---------------------------------+
| 1  | {                               |
|    |     "artist": "Bratmobile",     |
|    |     "title": "Pottymouth"       |
|    | }                               |
| 2  | {                               |
|    |     "artist": "Coltrane, John", |
|    |     "title": "Giant Steps"      |
|    | }                               |
+----+---------------------------------+
```

**Note:** The linebreak characters used are those for the platform running Tabletown as defined by the `PHP_EOL` constant.

## Tab handling
Tabs are handled in Tabletown as tab stops on eight spaces on a per-line basis. This allows vertically aligning text on tab stops across multiple lines. For example:

```php
$pottyMouth =<<<TXT
Pottymouth
label:\tKRS
yr:\t1993
rating:\t4.5
TXT;

$giantSteps =<<<TXT
Giant Steps
label:\tAtlantic
yr:\t1959
rating:\t5
TXT;

$headers = ['Artist', 'Title'];
$rows = [
    ['Bratmobile', $pottyMouth],
    ['Coltrane, John', $giantSteps],
];

$myTable = Table::get($headers, $rows);
print $myTable;
```

Will output:

```
+----------------+------------------+
| Artist         | Title            |
+----------------+------------------+
| Bratmobile     | Pottymouth       |
|                | label:  KRS      |
|                | yr:     1993     |
|                | rating: 4.5      |
| Coltrane, John | Giant Steps      |
|                | label:  Atlantic |
|                | yr:     1959     |
|                | rating: 5        |
+----------------+------------------+
```
