<?php
declare(strict_types=1);

namespace Tests\Feature\I18n\NameTranslator;

use amcsi\LyceeOverture\DeeplTranslation;
use amcsi\LyceeOverture\I18n\NameTranslator\DeeplCacheStore;
use Tests\DatabaseTestCase;

class DeeplCacheStoreTest extends DatabaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $translation = new DeeplTranslation();
        $translation->source = 'a';
        $translation->translation = 'x';
        $translation->save();
        $translation = new DeeplTranslation();
        $translation->source = 'b';
        $translation->translation = 'y';
        $translation->save();
    }
    public function testRememberForeverHit(): void
    {
        self::assertSame('x', $this->getInstance()->rememberForever('a', fn() => 'bad'));
    }

    public function testRememberForeverMiss(): void
    {
        self::assertSame('good', $this->getInstance()->rememberForever('c', fn() => 'good'));
        $msg = 'The cache should now win.';
        self::assertSame('good', $this->getInstance()->rememberForever('c', fn() => 'whatever'), $msg);
    }

    private function getInstance()
    {
        return new DeeplCacheStore();
    }
}
