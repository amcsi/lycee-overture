<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\DeeplTranslator;

use amcsi\LyceeOverture\Import\CsvValueInterpreter\MarkupConverter;

class DeeplMarkupTool
{
    public static function splitToMarkup(string $text): DeeplMarkupWrapped
    {
        $parts = [];
        $i = 0;
        $text = preg_replace_callback(self::getMarkupRegex(), static function ($match) use (&$parts, &$i) {
            $parts[] = $match[0];
            $ret = "<m id=$i />";
            ++$i;
            return $ret;
        }, $text);

        return new DeeplMarkupWrapped($text, $parts);
    }

    public static function reassembleString(string $translatedWithMarkup, array $translatedParts): string
    {
        return preg_replace_callback(
            '#<m id=(\d+) />#u',
            fn($match) => $translatedParts[$match[1]],
            $translatedWithMarkup
        );
    }

    private static function getMarkupRegex()
    {
        $costElementRegex = '\[[TＴ]?([雪月花宙日無])+]';

        $japaneseBasicAbilitiesRegex = MarkupConverter::getJapaneseBasicAbilitiesRegex();

        $abilityTypesRegex = MarkupConverter::getAbilityTypesRegex();

        return "/(\[($japaneseBasicAbilitiesRegex)(:($costElementRegex))?\]|$costElementRegex|$abilityTypesRegex)+/u";
    }
}
