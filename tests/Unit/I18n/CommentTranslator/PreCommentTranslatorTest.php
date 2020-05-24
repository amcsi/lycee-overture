<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\CommentTranslator;

use amcsi\LyceeOverture\I18n\CommentTranslator\PreCommentTranslator;
use PHPUnit\Framework\TestCase;
use Tests\Tools\TestUtils;

class PreCommentTranslatorTest extends TestCase
{
    /**
     * @dataProvider provideTranslate
     */
    public function testTranslate($expected, $input)
    {
        self::assertSame(
            $expected,
            (new PreCommentTranslator(TestUtils::createAutoTranslator()))->translate($input)
        );
    }

    public function provideTranslate()
    {
        return [
            [
                'Equip restriction: A [space] character',
                '装備制限:[宙]キャラ',
            ],
        ];
    }
}
