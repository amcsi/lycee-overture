<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

/**
 * For translating things related to someone's turn or battle
 */
class TurnAndBattle
{
    private const REGEX = '(次の)?(この|自|相手|このキャラの)?(ターン|バトル)(開始時|中|終了時(?:まで)?)?(に使用する|に使用できない)?';

    public static function autoTranslate(string $text): string
    {
        // language=regexp
        $pattern = '/' . self::REGEX . '/u';
        return preg_replace_callback($pattern, ['self', 'callback'], $text);
    }

    public static function getUncapturedRegex(): string
    {
        return RegexHelper::uncapture(self::REGEX);
    }

    private static function callback(array $matches): string
    {
        $next = next($matches);
        if ($next) {
            $next = " next";
        }
        $which = next($matches);
        $turnOrBattleMatched = next($matches);
        if ($turnOrBattleMatched === 'ターン') {
            $turnOrBattle = 'turn';
            $isBattle = false;
        } elseif ($turnOrBattleMatched === 'バトル') {
            $turnOrBattle = 'battle';
            $isBattle = true;
        } else {
            throw new \LogicException("Unexpected turnOrBattleMatched: $turnOrBattleMatched");
        }
        $startDuringEnd = next($matches);
        $useNotUse = next($matches);

        switch ($which) {
            case 'この':
                $what = "this $turnOrBattle";
                break;
            case '自':
                $what = "your$next $turnOrBattle";
                break;
            case '相手':
                $what = "your opponent's$next $turnOrBattle";
                break;
            case 'このキャラの':
                $what = "this character's $turnOrBattle";
                break;
            case '':
                if ($isBattle) {
                    $what = "$next battle";
                } else {
                    $what = "the$next turn";
                }
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
                // Can't translate this in that case.
                return $matches[0];
        }

        switch ($useNotUse) {
            case 'に使用する':
                return "use $when $what";
            case 'に使用できない':
                return "do not use $when $what";
            case '':
                return "$when $what";
                break;
            default:
                throw new \LogicException("Unexpected useNotUse: $useNotUse");
        }
    }
}
