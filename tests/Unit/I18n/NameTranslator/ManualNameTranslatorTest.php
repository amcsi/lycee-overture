<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\NameTranslator;

use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\I18n\NameTranslator\ManualNameTranslator;
use amcsi\LyceeOverture\I18n\OneSkyClient;
use PHPUnit\Framework\TestCase;

class ManualNameTranslatorTest extends TestCase
{
    public function testNotFoundTranslationShouldJustReturnSameText()
    {
        $instance = self::createInstance();
        self::assertSame('asdf', $instance->tryToTranslate('asdf', [OneSkyClient::CHARACTER_TYPES]));
    }

    public static function createInstance()
    {
        return new ManualNameTranslator(
            [
                Locale::ENGLISH => [
                    'translation' => [
                        OneSkyClient::CHARACTER_TYPES => [
                            'セ' => 'se',
                        ],
                        OneSkyClient::NAMES => [
                            'イ' => 'i',
                        ],
                        OneSkyClient::ABILITY_NAMES => [
                            'バー' => 'baa',
                        ],
                    ],
                ],
            ]
        );
    }

    public function testTryToTranslateCharacterTypeExact()
    {
        $instance = self::createInstance();
        self::assertSame('se', $instance->tryToTranslate('セ', [OneSkyClient::CHARACTER_TYPES]));
    }

    public function testNameTranslationByPunctuationComponent()
    {
        $instance = self::createInstance();
        self::assertSame(
            'i／baa・i',
            $instance->tryToTranslate('イ／バー・イ', [OneSkyClient::NAMES, OneSkyClient::ABILITY_NAMES])
        );
    }
}
