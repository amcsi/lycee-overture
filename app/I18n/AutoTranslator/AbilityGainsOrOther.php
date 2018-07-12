<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

/**
 * For characters gaining abilities, or being discarded, or other.
 */
class AbilityGainsOrOther
{
    public static function autoTranslate(string $text): string
    {
        // "This character gains X."
        $text = preg_replace_callback(
            '/(}|このキャラ|対戦キャラ)(は((?:\[.+?\])+)を得る|を(破棄|未行動に|行動済みに)する)\./u',
            ['self', 'callback'],
            $text
        );

        // "... gain $basicAbility."
        $text = preg_replace_callback(
            '/}は(\[.+?\]\])を得る\./u',
            function ($matches) use ($text) {
                return sprintf('} gains %s.', $matches[1]);
            },
            $text
        );

        return $text;
    }

    public static function callback(array $matches): string
    {
        $subject = next($matches);
        $action = next($matches);
        $what = next($matches);
        switch ($action) {
            case 'を破棄する':
                $doesAction = "gets discarded";
                break;
            case 'を未行動にする':
                $doesAction = "gets untapped";
                break;
            case 'を行動済みにする':
                $doesAction = "gets tapped";
                break;
            default:
                if (isset($what)) {
                    $doesAction = "gains $what";
                } else {
                    throw new \InvalidArgumentException("Unexpected action: $action");
                }
        }
        switch ($subject) {
            case 'このキャラ':
                return " this character $doesAction.";
            case '対戦キャラ':
                return " opponent's battling character $doesAction.";
            case '}':
                return "} $doesAction.";
            default:
                throw new \InvalidArgumentException("Unexpected subject: $subject");

        }
    }
}
