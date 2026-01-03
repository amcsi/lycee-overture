<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import;

use amcsi\LyceeOverture\Card\Type;
use League\Csv\Reader;
use PHPUnit\Framework\TestCase;
use function iter\toArray;
use Tests\Tools\SetAutoCreatorStubber;

class BasicImportCsvFiltererTest extends TestCase
{
    public function testToDatabaseRows()
    {
        $exampleCsvReader = Reader::from(__DIR__.'/test.csv');

        $setAutoCreator = SetAutoCreatorStubber::createInstanceWithSets();

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
