<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;

/**
 * For translating things related to someone's turn or battle
 */
class TurnAndBattle
{
    public static function autoTranslate(string $text): string
    {
        // language=regexp
        $pattern = '/' . self::getRegex() . '/u';
        return preg_replace_callback($pattern, ['self', 'callback'], $text);
    }

    public static function getUncapturedRegex(): string
    {
        return RegexHelper::uncapture(self::getRegex());
    }

    private static function getRegex(): string
    {
        $subjectRegex = Subject::getUncapturedRegex();
        // language=regexp
        return "(味方キャラがダウンした)?(次の)?(この|自|相手|($subjectRegex)の)?(ターン|バトル|攻撃|防御)(開始時|中|終了時(?:まで)?)?(に使用する|に使用できない)?";
    }

    private static function callback(array $matches): string
    {
        $allyDown = next($matches);
        $next = next($matches);
        if ($next) {
            $next = " next";
        }
        $which = next($matches);
        $subjectSource = next($matches);
        $turnOrBattleMatched = next($matches);
        $isBattle = false;
        switch ($turnOrBattleMatched) {
            case 'ターン':
                $turnOrBattle = 'turn';
                break;
            case 'バトル':
                $turnOrBattle = 'battle';
                $isBattle = true;
                break;
            case '攻撃':
                $turnOrBattle = 'attack';
                break;
            case '防御':
                $turnOrBattle = 'defense';
                break;
            default:
                throw new \LogicException("Unexpected turnOrBattleMatched: $turnOrBattleMatched");
        }
        $startDuringEnd = next($matches);
        $useNotUse = next($matches);

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
            case '':
                if ($isBattle) {
                    $what = trim("$next battle");
                } else {
                    $what = "the$next turn";
                }
                break;
            default:
                if ($subjectSource) {
                    $subject = Subject::autoTranslateStrict($subjectSource);
                    $what = trim(Subject::posessivize($subject) . " $turnOrBattle");
                    if ($when === 'during' && in_array($turnOrBattle, ['attack', 'defense'], true)) {
                        $when = 'while';
                        $what = $turnOrBattle === 'attack' ?
                            "attacking with$subject" :
                            "defending with$subject";
                    }
                } else {
                    throw new \LogicException("Unexpected which: $which");
                }
        }

        if ($allyDown) {
            $what .= ' when an ally character is defeated';
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
