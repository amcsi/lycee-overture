<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Database;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use PHPUnit\Framework\TestCase;

class UpsertTest extends TestCase
{
    public function testUpsert()
    {
        $values = [
            [
                'id' => '1',
                'created_at' => '1',
                'column1' => 'value1',
                'column2' => 'value2',
            ],
            [
                'id' => '2',
                'created_at' => '2',
                'column1' => 'value3',
                'column2' => 'value4',
            ],
        ];

        $connection = $this->createMock(ConnectionInterface::class);
        $grammar = $this->createMock(Grammar::class);
        $processor = $this->createMock(Processor::class);

        $insertPart = 'insertPart';
        $grammar->expects($this->once())->method('compileInsert')->willReturn($insertPart);

        $expected = "$insertPart ON DUPLICATE KEY UPDATE `created_at`=VALUES(`created_at`), `column1`=VALUES(`column1`), `column2`=VALUES(`column2`)";

        $connection->expects($this->once())->method('affectingStatement')
            ->with($expected, ['1', '1', 'value1', 'value2', '2', '2', 'value3', 'value4'])
            ->willReturn(99);

        $builder = new Builder($connection, $grammar, $processor);

        (require __DIR__ . '/../../../app/Database/upsert.php')->bindTo($builder, $builder)($values);
    }
}
