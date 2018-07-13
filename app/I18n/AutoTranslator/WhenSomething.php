<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

/**
 * For "when something happens" triggers.
 */
class WhenSomething
{
    public static function autoTranslate(string $text): string
    {
        $text = WhenSupporting::autoTranslate($text);
        $text = WhenAppears::autoTranslate($text);
        $text = str_replace('味方キャラがエンゲージ登場したとき', 'when an ally character enters engagement', $text);
        $text = str_replace('このキャラが移動したとき', 'when this character moves', $text);
        $text = str_replace(
            '自分の効果によって相手キャラを破棄したとき',
            'when an opponent character is destroyed by use of your effects',
            $text
        );
        $text = str_replace(
            'このキャラをエンゲージ登場によって破棄したとき',
            'when this character is destroyed due to Engage summon',
            $text
        );
        $text = str_replace(
            'このキャラで攻撃宣言をしたとき',
            'when you declare an attack with this character',
            $text
        );
        return $text;
    }
}
