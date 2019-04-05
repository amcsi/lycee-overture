<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use amcsi\LyceeOverture\Card\Type;
use amcsi\LyceeOverture\Import\Set\SetAutoCreator;
use amcsi\LyceeOverture\Set;
use Illuminate\Support\Collection;
use League\Csv\Reader;
use PHPUnit\Framework\TestCase;
use function iter\toArray;

class BasicImportCsvFiltererTest extends TestCase
{
    public function testToDatabaseRows()
    {
        $exampleCsvReader = Reader::createFromPath(__DIR__ . '/test.csv');

        $set = new Set();
        $set->id = 1;
        $set->name_ja = 'オーガスト';
        $set->version = '1.0';
        $sets[] = $set;
        $set = new Set();
        $set->id = 2;
        $set->name_ja = 'Fate/Grand Order';
        $set->version = '2.0';
        $sets[] = $set;
        $sets = new Collection($sets);

        $setAutoCreator = new SetAutoCreator($sets);

        $basicImportCsvFilterer = new BasicImportCsvFilterer($setAutoCreator);

        $result = toArray(($basicImportCsvFilterer)->toDatabaseRows($exampleCsvReader));

        self::assertCount(2, $result);
        $item = $result[0];
        self::assertSame('LO-0576', $item['id']);
        self::assertSame(2, $item['set_id']);
        self::assertSame(Type::CHARACTER, $item['type']);
        self::assertSame(2, $item['ex']);
        $item = $result[1];
        self::assertSame('LO-0985', $item['id']);
        self::assertSame(1, $item['set_id']);
        self::assertSame(Type::CHARACTER, $item['type']);
        self::assertSame(2, $item['ex']);
    }
}
