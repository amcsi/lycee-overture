<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

/**
 * For translating things related to someone's turn or battle
 */
class TurnAndBattle
{
    public static function autoTranslate(string $text): string
    {
        // language=regexp
        $pattern = '/(次の)?(この|自|相手)?ターン(開始時|中|終了時(?:まで))?(に使用する)?/u';
        return preg_replace_callback($pattern, ['self', 'callback'], $text);
    }

    private static function callback(array $matches): string
    {
        $next = next($matches);
        if ($next) {
            $next = " next";
        }
        $which = next($matches);
        $startDuringEnd = next($matches);
        $use = next($matches);

        switch ($which) {
            case 'この':
                $what = 'this turn';
                break;
            case '自':
                $what = "your$next turn";
                break;
            case '相手':
                $what = "your opponent's$next turn";
                break;
            case '':
                $what = "the$next turn";
                break;
            default:
                throw new \LogicException("Unexpected which: $which");
        }
        switch ($startDuringEnd) {
            case '開始時':
                $when = 'at the start of';
                break;
            case '中':
                $when = 'during';
                break;
            case '終了時':
                $when = 'at the end of';
                break;
            case '終了時まで':
                $when = 'until the end of';
                break;
            default:
                throw new \LogicException("Unexpected startDuringEnd: $startDuringEnd");
        }

        if ($use) {
            return "use $when $what";
        } else {
            return "$when $what";
        }
    }
}
