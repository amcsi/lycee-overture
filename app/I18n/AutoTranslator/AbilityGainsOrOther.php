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
            '/(}|(この|対戦|相手)キャラ)(?:(\d)体)?(は((?:\[.+?\])+)を得る|を(破棄|未行動に|行動済みに)する)\./u',
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
        $subjectTargetOrSomethingElse = next($matches);
        $matchedSubject = next($matches);
        $howMany = next($matches);
        $action = next($matches);
        $what = next($matches);
        switch ($action) {
            case 'を破棄する':
                $doesAction = "gets destroyed";
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
        if ($subjectTargetOrSomethingElse === '}') {
            return "} $doesAction.";
        }
        switch ($matchedSubject) {
            case 'この':
                $subject = 'this character';
                break;
            case '対戦':
                $subject = "opponent's battling character";
                break;
            case '相手':
                $subject = "enemy character";
                break;
            default:
                throw new \InvalidArgumentException("Unexpected subject: $subjectTargetOrSomethingElse");
        }

        if ($howMany) {
            $s = $howMany === "1" ? '' : 's';
            $subject = sprintf("%d %s%s", $howMany, $subject, $s);
        }

        return " $subject $doesAction.";
    }
}
