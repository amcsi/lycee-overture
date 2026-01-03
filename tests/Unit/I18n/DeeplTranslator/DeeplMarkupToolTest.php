<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\DeeplTranslator;

use amcsi\LyceeOverture\I18n\DeeplTranslator\DeeplMarkupTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DeeplMarkupToolTest extends TestCase
{

    #[DataProvider('provideSplitToMarkup')]
    public function testSplitToMarkupAndReassemble(string $expectedText, array $expectedParts, string $input)
    {
        $split = DeeplMarkupTool::splitToMarkup($input);
        self::assertSame($expectedText, $split->text);
        self::assertSame($expectedParts, $split->parts);

        self::assertSame($input, DeeplMarkupTool::reassembleString($expectedText, $expectedParts));
    }

    public static function provideSplitToMarkup()
    {
        return [
            [
                'hey', [], 'hey',
            ],
            [
                'hey <m id=0 /> yo', ['[雪月花宙日無]'], 'hey [雪月花宙日無] yo',
            ],
            [
                'hey <m id=0 /> yo', ['[雪月花宙日無][雪月花宙日無]'], 'hey [雪月花宙日無][雪月花宙日無] yo',
            ],
            [
                'hey <m id=0 /> yo', ['[T雪月花宙日無]'], 'hey [T雪月花宙日無] yo',
            ],
            'tap on its own should not get converted' => [
                'hey [T] yo', [], 'hey [T] yo',
            ],
            [
                'hey <m id=0 /> yo', ['[オーダーステップ:[雪]]'], 'hey [オーダーステップ:[雪]] yo',
            ],
            [
                'hey <m id=0 /> yo', ['[オーダーステップ:[0]]'], 'hey [オーダーステップ:[0]] yo',
            ],
        ];
    }
}
