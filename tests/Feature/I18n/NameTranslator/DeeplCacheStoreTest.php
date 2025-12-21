<?php
declare(strict_types=1);

namespace Tests\Feature\I18n\NameTranslator;

use amcsi\LyceeOverture\Models\DeeplTranslation;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\I18n\NameTranslator\DeeplCacheStore;
use Tests\DatabaseTestCase;

class DeeplCacheStoreTest extends DatabaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $translation = new DeeplTranslation();
        $translation->source = 'a';
        $translation->locale = Locale::ENGLISH;
        $translation->translation = 'x';
        $translation->save();
        $translation = new DeeplTranslation();
        $translation->source = 'b';
        $translation->locale = Locale::ENGLISH;
        $translation->translation = 'y';
        $translation->save();
    }
    public function testRememberForeverHit(): void
    {
        self::assertSame('x', $this->getInstance()->rememberForever('a', Locale::ENGLISH, fn() => 'bad'));
    }

    public function testRememberForeverMiss(): void
    {
        self::assertSame('good', $this->getInstance()->rememberForever('c', Locale::ENGLISH, fn() => 'good'));
        $msg = 'The cache should now win.';
        self::assertSame('good', $this->getInstance()->rememberForever('c', Locale::ENGLISH, fn() => 'whatever'), $msg);
    }

    private function getInstance()
    {
        return new DeeplCacheStore();
    }
}
