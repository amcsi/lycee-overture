<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use amcsi\LyceeOverture\Card\Type;
use League\Csv\Reader;
use PHPUnit\Framework\TestCase;
use function iter\toArray;

class BasicImportCsvFiltererTest extends TestCase
{
    public function testToDatabaseRows()
    {
        $exampleCsvReader = Reader::createFromPath(__DIR__ . '/test.csv');
        $result = toArray((new BasicImportCsvFilterer())->toDatabaseRows($exampleCsvReader));

        self::assertCount(2, $result);
        $item = $result[0];
        self::assertSame('LO-0576', $item['id']);
        self::assertSame(Type::CHARACTER, $item['type']);
        self::assertSame(2, $item['ex']);
        $item = $result[1];
        self::assertSame('LO-0985', $item['id']);
        self::assertSame(Type::CHARACTER, $item['type']);
        self::assertSame(2, $item['ex']);
    }
}
