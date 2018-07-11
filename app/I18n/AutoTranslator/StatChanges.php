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
        $subject = '((?:(味方|相手|この|対象の|対戦)((?:\[.+?\])*))?キャラ((\d)体|全て)?|})';
        $statPlusMinusAction = 'に((?:(?:AP|DP|SP|DMG)[+-]\\d(?:, )?)+)';
        $statsToNumberAction = 'の((?:と?(?:AP|DP|SP|DMG))+)を(\d)に';
        $pattern = "/{$subject}({$statPlusMinusAction}|{$statsToNumberAction})する./u";
        return preg_replace_callback(
            $pattern,
            ['self', 'callback'],
            $text
        );
    }

    private static function callback(array $matches): string
    {
        $plural = false;
        $target = next($matches) === '}'; // Whether the effect targets.
        $subject = next($matches); // Ally or Enemy in Japanese (or '')
        $something = next($matches); // e.g. [sun] <- characters
        $allOrHowMany = next($matches);
        $all = $allOrHowMany === '全て';
        $howMany = next($matches);
        $action = next($matches);
        $statChanges = next($matches); // The stat changes involved for stat changes action.
        $posessiveSubject = strpos($action, 'の') === 0; // {Target's}
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
                    case '対象の':
                        $text = 'that';
                        break;
                    case '対戦':
                        $text = 'battling opponent\'s';
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
            if ($posessiveSubject) {
                $text = substr($text, -1) === 's' ?
                    "$text'" : // Already ends with an "s". Just append an apostrophe.
                    "$text's" // Doesn't, so "'s".
                ;
            }
        } else {
            $text = $posessiveSubject ? "'s}" : '}';
        }
        if (strpos($action, 'に') === 0) {
            // ... gets Stat+-n ...

            $s = $plural ? '' : 's';
            return sprintf(
                '%s get%s %s.',
                $text,
                $s,
                $statChanges
            );
        } elseif (strpos($action, 'の') === 0) {
            // ... 's Stat becomes 0.

            $stats = next($matches);
            $toWhatValue = next($matches);
            // become(s)
            $s = strpos($stats, 'と') !== false ? '' : 's';

            return sprintf(
                "%s %s become%s %d.",
                $text,
                str_replace('と', ' and ', $stats),
                $s,
                $toWhatValue
            );
        } else {
            throw new \InvalidArgumentException("Unexpected action: $action");
        }
    }
}
