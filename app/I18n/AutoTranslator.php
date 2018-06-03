<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

use amcsi\LyceeOverture\I18n\AutoTranslator\DiscardFromDeck;
use amcsi\LyceeOverture\I18n\AutoTranslator\DrawCards;
use amcsi\LyceeOverture\I18n\AutoTranslator\FullWidthCharacters;
use amcsi\LyceeOverture\I18n\AutoTranslator\Target;
use amcsi\LyceeOverture\I18n\AutoTranslator\WhenAppears;

/**
 * Auto-translates parts of Japanese text.
 */
class AutoTranslator
{
    private static $punctuationMap = [
        '。' => '.',
        '、' => ',',
        '・' => ',',
    ];

    public static function autoTranslate(string $japaneseText): string
    {
        $bracketCounts = self::countBrackets($japaneseText);

        $autoTranslated = $japaneseText;

        $autoTranslated = preg_replace('/。$/', '.', $autoTranslated);
        $autoTranslated = preg_replace_callback('/。|、|・/', function ($match) {
            return self::$punctuationMap[$match[0]] . ' ';
        }, $autoTranslated);
        $autoTranslated = FullWidthCharacters::translateFullWidthCharacters($autoTranslated);

        // "This character gets X."
        $autoTranslated = preg_replace_callback(
            '/この(\[[^\]+]\])?キャラに((?:(?:AP|DP|SP|DMG)[+-]\d(?:, )?)+)する./u',
            function ($matches) use ($autoTranslated) {
                return " this $matches[1] character gets $matches[2].";
            },
            $autoTranslated
        );

        // "This character gains X."
        $autoTranslated = preg_replace_callback(
            '/このキャラは(\[[^\]]+\])を得る\./u',
            function ($matches) use ($autoTranslated) {
                return " this character gains $matches[1].";
            },
            $autoTranslated
        );

        // "... get $statChanges."
        $autoTranslated = preg_replace_callback(
            '/((味方|相手)キャラ(\d)体|})に((?:(?:AP|DP|SP|DMG)[+-]\d(?:, )?)+)する./u',
            function ($matches) use ($autoTranslated) {
                $target = $matches[1] === '}'; // Whether the effect targets.
                $allyOrEnemy = $matches[2]; // Ally or Enemy in Japanese (or '')
                $howMany = $matches[3];
                $statChanges = $matches[4]; // The stat changes involved.
                if (!$target) {
                    $text = $allyOrEnemy === '味方' ? 'ally' : 'enemy';
                    $text = "$text character";
                    if ($howMany > 1) {
                        $text = "$howMany ${text}s";
                    }
                } else {
                    $text = '}';
                }
                return sprintf(
                    '%s gets %s.',
                    $text,
                    $statChanges
                );
            },
            $autoTranslated
        );

        // "... gain $basicAbility."
        $autoTranslated = preg_replace_callback(
            '/}は(\[.+?\]\])を得る\./u',
            function ($matches) use ($autoTranslated) {
                return sprintf('} gains %s.', $matches[1]);
            },
            $autoTranslated
        );

        $autoTranslated = WhenAppears::autoTranslate($autoTranslated);
        $autoTranslated = DrawCards::autoTranslate($autoTranslated);
        $autoTranslated = DiscardFromDeck::autoTranslate($autoTranslated);
        $autoTranslated = Target::autoTranslate($autoTranslated);

        // Condense multiple spaces into one; trim.
        $autoTranslated = trim(preg_replace('/ {2,}/', ' ', $autoTranslated));
        // Fix capitalization.
        $autoTranslated = preg_replace_callback('/(^[a-z]|(?=\.\s*)[a-z])/', function ($matches) {
            return strtoupper($matches[1]);
        }, $autoTranslated);

        if (self::countBrackets($autoTranslated) !== $bracketCounts) {
            throw new \LogicException("Bracket count mismatch.\nOriginal: $japaneseText\nTranslated: $autoTranslated");
        }

        return $autoTranslated;
    }

    /**
     * @param string $japaneseText
     * @return array
     */
    private static function countBrackets(string $japaneseText): array
    {
        $charsToLookAt = [ord('{') => 0, ord('}') => 0];
        $charCounts = array_intersect_key(count_chars($japaneseText, 1), $charsToLookAt);
        return $charCounts;
    }
}
