<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\CommentTranslator;

use Tests\Unit\LyceeOverture\I18n\SetTranslator\BrandTranslator;

/**
 * For translating comments on the cards.
 */
class CommentTranslator
{
    private $brandTranslator;

    public function __construct(BrandTranslator $brandTranslator)
    {
        $this->brandTranslator = $brandTranslator;
    }

    public function translate(string $text): string
    {
        return preg_replace_callback('/構築制限:(.+)/u', [$this, 'deckRestrictionCallback'], $text);
    }

    private function deckRestrictionCallback(array $matches): string
    {
        return 'Deck restriction: ' . trim($this->brandTranslator->translate($matches[1]));
    }
}