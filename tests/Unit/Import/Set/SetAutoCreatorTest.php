<?php
declare(strict_types=1);

namespace Tests\Unit\Import\Set;

use amcsi\LyceeOverture\Models\Set;
use PHPUnit\Framework\TestCase;
use Tests\Tools\SetAutoCreatorStubber;

class SetAutoCreatorTest extends TestCase
{
    public function testWithExistingSet(): void
    {
        $instance = SetAutoCreatorStubber::createInstanceWithSets();

        self::assertSame(1, $instance->getOrCreateSetIdByJaFullName('オーガスト 1.0'));
    }

    public function testWithEmptySet(): void
    {
        $instance = SetAutoCreatorStubber::createInstanceWithSets();

        self::assertNull($instance->getOrCreateSetIdByJaFullName('-'));
    }

    public function testWithNewSetOfNewBrand(): void
    {
        $setModel = \Mockery::mock(Set::class);

        $instance = SetAutoCreatorStubber::createInstanceWithSets($setModel);

        $setModel->expects()->forceCreate([
            'name_ja' => 'something',
            'version' => '1.0'
        ])->andReturn((new Set())->forceFill(['id' => 3, 'name_ja' => 'something', 'version' => '1.0']));


        self::assertSame(3, $instance->getOrCreateSetIdByJaFullName('something 1.0'));
        // Test that the new set was appended to the existing sets so that it would be reused.
        self::assertSame(3, $instance->getOrCreateSetIdByJaFullName('something 1.0'));
    }

    public function testWithNewSetOfNewBrandNoVersion(): void
    {
        $setModel = \Mockery::mock(Set::class);

        $instance = SetAutoCreatorStubber::createInstanceWithSets($setModel);

        $setModel->expects()->forceCreate([
            'name_ja' => 'メロンブックス',
            'version' => ''
        ])->andReturn((new Set())->forceFill(['id' => 3, 'name_ja' => 'メロンブックス', 'version' => '']));

        self::assertSame(3, $instance->getOrCreateSetIdByJaFullName('メロンブックス'));
        self::assertSame(3, $instance->getOrCreateSetIdByJaFullName('メロンブックス'));
    }

    public function testWithNewSetCopyingFromDifferentVersion(): void
    {
        $setModel = \Mockery::mock(Set::class);

        $instance = SetAutoCreatorStubber::createInstanceWithSets($setModel);

        $setModel->expects()->forceCreate([
            'name_ja' => 'オーガスト',
            'version' => '2.0'
        ])->andReturn((new Set())->forceFill(['id' => 3]));


        self::assertSame(3, $instance->getOrCreateSetIdByJaFullName('オーガスト 2.0'));
    }
}
