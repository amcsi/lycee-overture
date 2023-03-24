<?php
declare(strict_types=1);

namespace Tests\I18n\Tools;

use amcsi\LyceeOverture\I18n\Tools\JapaneseSentenceSplitter;
use PHPUnit\Framework\TestCase;

class JapaneseSentenceSplitterTest extends TestCase
{

    /**
     * @dataProvider provideSplit
     */
    public function testSplit(array $expected, string $input)
    {
        self::assertSame($expected, JapaneseSentenceSplitter::split($input));
    }

    /**
     * @dataProvider provideReplace
     */
    public function testReplaceCallback(string $expected, string $input)
    {
        self::assertSame($expected, JapaneseSentenceSplitter::replaceCallback($input, static fn($match) => $match[0] . '¤'));
    }

    public function provideSplit() {
        return [
            'simple' => [['hey'], 'hey'],
            'line break' => [["hey\n", 'yo'], "hey\nyo"],
            'periods' => [['る。', 'yo'], "る。yo"],
            'period and line break' => [['る。', "\n", ''],  "る。\n"],
            'periods and line breaks' => [["る。", "\n", "き。", "\n", ''], "る。\nき。\n"],
        ];
    }

    public function provideReplace() {
        return [
            'simple' => ['hey¤', 'hey'],
            'line break' => ["hey\n¤yo¤", "hey\nyo"],
            'periods' => ["る。¤yo¤", "る。yo"],
            'period and line break' => ["る。¤\n¤",  "る。\n"],
            'periods and line breaks' => ["る。¤\n¤き。¤\n¤", "る。\nき。\n"],
        ];
    }
}
