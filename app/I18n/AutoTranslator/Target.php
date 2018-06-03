<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

/**
 * For translating text within {targets}
 */
class Target
{
    public static function autoTranslate(string $text): string
    {
        return preg_replace_callback('/\{([^}]+)}/', ['self', 'callback'], $text);
    }

    private static function callback(array $matches): string
    {
        return '{' .
            preg_replace_callback(
                '/(味方|相手)?(.*?)キャラ(\d)体/',
                ['self', 'targetCharacterCallback'],
                $matches[1]
            ) .
            '}';
    }

    private static function targetCharacterCallback(array $matches): string
    {
        $allyOrEnemy = $matches[1]; // Ally or Enemy or X in Japanese (or '')
        $something = $matches[2]; // e.g. 1 "Sun" character.
        $howMany = $matches[3];
        switch ($allyOrEnemy) {
            case '味方':
                $text = 'ally';
                break;
            case '相手':
                $text = 'enemy';
                break;
            default:
                $text = $allyOrEnemy;
                break;
        }
        $text = "$text $something character";
        if ($howMany > 1) {
            $text = "$howMany ${text}";
        }
        return ucfirst(trim($text));
    }
}
