<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\CommentTranslator;

use amcsi\LyceeOverture\I18n\CommentTranslator\CommentTranslator;
use amcsi\LyceeOverture\I18n\SetTranslator\SetTranslator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Tools\TestUtils;

class CommentTranslatorTest extends TestCase
{
    #[DataProvider('provideTranslate')]
    public function testTranslate($expected, $input)
    {
        self::assertSame(
            $expected,
            (new CommentTranslator(
                new SetTranslator((require __DIR__.'/../../../../config/lycee.php')['sets']),
                TestUtils::createQuoteTranslator()
            ))->translate($input)
        );
    }

    public static function provideTranslate()
    {
        return [
            [
                'Deck restriction: August（[AUG]）',
                '構築制限:オーガスト（[AUG]）',
            ],
            [
                'Deck restriction: Girls & Panzer（[GUP]）',
                '構築制限:ガールズ＆パンツァー（[GUP]）',
            ],
            [
                'Deck restriction: Kamihime Project（[KHP]）',
                '構築制限:神姫PROJECT（[KHP]）',
            ],
            [
                'Deck restriction: Yuzusoft',
                '構築制限:ゆずソフト',
            ],
            [
                'Deck restriction: Oshiro Project: RE',
                '構築制限:御城プロジェクト：ＲＥ',
            ],
        ];
    }
}
