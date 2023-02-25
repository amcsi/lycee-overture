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
        return str_replace(
            'ゲーム中',
            'during the game',
            preg_replace_callback($pattern, self::callback(...), $text)
        );
    }

    public static function getUncapturedRegex(): string
    {
        return RegexHelper::uncapture(self::getRegex());
    }

    private static function getRegex(): string
    {
        $subjectRegex = Subject::getUncapturedRegex();
        // language=regexp
        return "(味方キャラがダウンした|自分のデッキがダメージを受けた)?(次の)?(この|自分?|相手|($subjectRegex))?の?(ターン|バトル|(?:攻撃)?宣言|攻撃|防御|ウェイクアップ)(開始時|中|終了時(?:まで)?|に対応|で)?((?:に|して)使用する|(?:に|して)使用できない)?";
    }

    public static function callback(array $matches): string
    {
        $allyDownOrDeckDamagedSource = next($matches);
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
            case '宣言':
                $turnOrBattle = 'activation of an effect';
                break;
            case '攻撃宣言':
                $turnOrBattle = 'attack declaration';
                break;
            case 'ウェイクアップ':
                $turnOrBattle = 'wake-up phase';
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
            case 'に対応':
                $when = 'as a response to';
                break;
            case 'で':
                $when = 'in';
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
            case '自分':
                $what = "your$next $turnOrBattle";
                break;
            case '相手':
                $what = "your opponent's$next $turnOrBattle";
                break;
            case '':
                if ($isBattle) {
                    $what = trim("$next battle");
                } else {
                    $what = "the$next $turnOrBattle";
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

        if ($allyDownOrDeckDamagedSource === '味方キャラがダウンした') {
            $what .= ' when an ally character is defeated';
        } elseif ($allyDownOrDeckDamagedSource === '自分のデッキがダメージを受けた') {
            $what .= ' when damage was dealt to your deck';
        }

        switch ($useNotUse) {
            case 'に使用する':
            case 'して使用する':
                return "use $when $what";
            case 'に使用できない':
            case 'して使用できない':
                return "do not use $when $what";
            case 'で未行動に戻らない':
                return "do not untap $what $when";
            case '':
                return "$when $what";
                break;
            default:
                throw new \LogicException("Unexpected useNotUse: $useNotUse");
        }
    }
}
