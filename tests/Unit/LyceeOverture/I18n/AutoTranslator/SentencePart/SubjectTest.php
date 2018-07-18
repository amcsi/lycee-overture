<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\AutoTranslator\SentencePart;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;
use PHPUnit\Framework\TestCase;

class SubjectTest extends TestCase
{
    /**
     * @dataProvider provideCreateInstance
     */
    public function testCreateInstance(string $expected, string $input): void
    {
        self::assertSame($expected, Subject::createInstance($input)->getSubjectText());
    }

    public function provideCreateInstance()
    {
        $posessivePlaceholder = Subject::POSSESSIVE_PLACEHOLDER;
        return [
            [
                " all your AF characters$posessivePlaceholder",
                '味方AFキャラ全て',
            ],
            [
                " that character$posessivePlaceholder",
                'そのキャラ',
            ],
            [
                " 2 untapped ally characters$posessivePlaceholder",
                '未行動の味方キャラ2体',
            ],
            'item (even if it doesnt make sense' => [
                " all your AF items$posessivePlaceholder",
                '味方AFアイテム全て',
            ],
            'event (even if it doesnt make sense' => [
                " all your AF events$posessivePlaceholder",
                '味方AFイベント全て',
            ],
            'field as noun' => [
                " ally field$posessivePlaceholder",
                '味方フィールド',
            ],
        ];
    }
}
