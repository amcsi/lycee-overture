<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

/**
 * Automatically translates stat changes.
 */
class StatChanges
{
    public static function autoTranslate(string $text): string
    {
        return preg_replace_callback(
            '/((?:(味方|相手|この)((?:\[.+?\])*))?キャラ((\d)体|全て)?|})に((?:(?:AP|DP|SP|DMG)[+-]\d(?:, )?)+)する./u',
            ['self', 'callback'],
            $text
        );
    }

    private static function callback(array $matches): string
    {
        $plural = false;
        $target = $matches[1] === '}'; // Whether the effect targets.
        $subject = $matches[2]; // Ally or Enemy in Japanese (or '')
        $something = $matches[3]; // e.g. [sun] <- characters
        $allOrHowMany = $matches[4];
        $all = $allOrHowMany === '全て';
        $howMany = $matches[5];
        $statChanges = $matches[6]; // The stat changes involved.
        if (!$target) {
            if ($all) {
                switch ($subject) {
                    case '味方':
                        $text = "all your $something characters";
                        break;
                    case '相手':
                        $text = "all enemy $something characters";
                        break;
                    case '':
                        // Unknown
                        $text = "all $something characters";
                        break;
                    default:
                        throw new \LogicException("Unexpected all subject: $subject");
                }
                $plural = true;
            } else {
                switch ($subject) {
                    case '味方':
                        $text = 'ally';
                        break;
                    case '相手':
                        $text = 'enemy';
                        break;
                    case 'この':
                        $text = 'this';
                        break;
                    case '':
                        // Unknown
                        $text = '';
                        break;
                    default:
                        throw new \LogicException("Unexpected subject: $subject");
                }
                $text = "$text $something character";
                if ($howMany > 1) {
                    $plural = true;
                    $text = "$howMany ${text}s";
                }
            }
            $text = " $text";
        } else {
            $text = '}';
        }
        $s = $plural ? '' : 's';
        return sprintf(
            '%s get%s %s.',
            $text,
            $s,
            $statChanges
        );
    }
}
