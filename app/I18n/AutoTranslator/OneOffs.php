<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

/**
 * One-off translations.
 */
class OneOffs
{
    public static function autoTranslate(string $text): string
    {
        $text = str_replace(
            'このキャラを未行動にし, {空き味方フィールド}に移動する',
            'untap this character and move it to {unoccupied ally field}',
            $text
        );
        return $text;
    }
}
