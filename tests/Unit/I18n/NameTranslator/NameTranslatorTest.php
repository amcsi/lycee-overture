<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\NameTranslator;

use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\I18n\NameTranslator\KanaTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\KanjiTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\ManualNameTranslator;
use amcsi\LyceeOverture\I18n\NameTranslator\NameTranslator;
use amcsi\LyceeOverture\I18n\OneSkyClient;
use PHPUnit\Framework\TestCase;

class NameTranslatorTest extends TestCase
{
    /**
     * @var KanaTranslator|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    private $kanaTranslator;
    private $kanjiTranslator;

    public function setUp(): void
    {
        $this->kanaTranslator = \Mockery::mock(KanaTranslator::class);
        $this->kanjiTranslator = \Mockery::mock(KanjiTranslator::class);
    }

    public function testNotFoundTranslationShouldJustReturnSameText()
    {
        $instance = $this->createInstance();
        $input = 'asdf';
        $this->kanaTranslator->expects()->translate($input)->andReturn($input);
        $this->kanjiTranslator->expects()->translate($input)->andReturn($input);
        self::assertSame($input, $instance->tryTranslateCharacterType($input));
    }

    public function createInstance()
    {
        return new NameTranslator(new ManualNameTranslator(
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
        ), $this->kanaTranslator, $this->kanjiTranslator);
    }

    public function testTryToTranslateCharacterTypeExact()
    {
        $instance = $this->createInstance();
        self::assertSame('se', $instance->tryTranslateCharacterType('セ'));
    }

    public function testNameTranslationByPunctuationComponent()
    {
        $instance = $this->createInstance();
        self::assertSame(
            'i / baa i',
            $instance->tryTranslateName('イ／バー・イ')
        );
    }
}
