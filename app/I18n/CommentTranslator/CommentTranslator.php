<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\CommentTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\FullWidthCharacters;
use amcsi\LyceeOverture\I18n\AutoTranslator\QuoteTranslator;
use amcsi\LyceeOverture\I18n\SetTranslator\SetTranslator;

/**
 * For translating comments on the cards.
 */
class CommentTranslator
{
    public function __construct(private SetTranslator $setTranslator, private QuoteTranslator $quoteTranslator)
    {
    }

    public function translate(string $text): string
    {
        $text = str_replace(
            'このカードは「ラッキーカードキャンペーン」の当たりカードです。キャンペーンの詳細は以下をご確認ください。',
            'This card is the winning card of the "Lucky Card Campaign". See below for campaign details.',
            $text
        );
        $text = str_replace(
            'このカードは能力を持たないキャラとしてゲームで使用できます。',
            'This card can be used in the game as a character without an ability.',
            $text
        );
        $text = $this->quoteTranslator->autoTranslate($text);
        $text = FullWidthCharacters::transformQuotes($text);

        return preg_replace_callback('/構築制限:(.+)/u', [$this, 'deckRestrictionCallback'], $text);
    }

    private function deckRestrictionCallback(array $matches): string
    {
        return 'Deck restriction: ' . trim($this->setTranslator->translate($matches[1]));
    }
}
