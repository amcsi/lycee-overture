<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\CommentTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator;

class PreCommentTranslator
{
    public function __construct(private AutoTranslator $autoTranslator)
    {
    }

    public function translate(string $text): string
    {
        return preg_replace_callback('/(?:(?:装備制限:|装備制限) *)(.+)/u', [$this, 'equipRestrictionCallback'], $text);
    }

    private function equipRestrictionCallback(array $matches): string
    {
        return 'Equip restriction: ' . $this->autoTranslator->autoTranslate($matches[1]);
    }
}
