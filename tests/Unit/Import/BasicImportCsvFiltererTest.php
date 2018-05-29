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
        self::assertSame('LO-0576', $result[0]['id']);
        self::assertSame(Type::CHARACTER, $result[0]['type']);
        self::assertSame('LO-0985', $result[1]['id']);
        self::assertSame(Type::CHARACTER, $result[1]['type']);
    }
}
