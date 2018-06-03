<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

/**
 * For translating when a character appears in battle.
 */
class WhenAppears
{
    public static function autoTranslate(string $text): string
    {
        return str_replace('このキャラが登場したとき', 'when this character enters the field', $text);
    }
}
