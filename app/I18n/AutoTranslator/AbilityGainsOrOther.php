<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Action;
use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;

/**
 * For characters gaining abilities, or being discarded, or other.
 */
class AbilityGainsOrOther
{
    public static function autoTranslate(string $text): string
    {
        $subjectRegex = Subject::getUncapturedRegex();

        $turnAndBattleRegex = TurnAndBattle::getUncapturedRegex();
        // language=regexp
        $getsSomethingActionRegex = "は(${turnAndBattleRegex})?((?:\\[.+?\\])+)を(得る|失う)|を(?:(破棄|未行動に|行動済みに|手札に入|登場|除外)(れる|する|できる)|(デッキの[下上]に置く))";

        // "This character gains X."
        $pattern = "/(相手は)?($subjectRegex)($getsSomethingActionRegex)/u";
        $text = Action::subjectReplace(
            $pattern,
            [self::class, 'callback'],
            $text,
            2
        );

        return $text;
    }

    public static function callback(array $matches): Action
    {
        $opponentDoes = next($matches); // E.g. "Your opponent discards 1 character".
        $action = next($matches);
        $untilEndOfTurnSource = next($matches);
        $what = next($matches);
        $gainsOrLoses = next($matches);
        $state = next($matches);
        $canOrDoSource = next($matches);
        $group2 = next($matches);
        if ($group2) {
            // 2nd group of possible states (without suru/dekiru)
            $state = $group2;
        }
        $mandatory = true;
        if ($canOrDoSource === 'できる') {
            $mandatory = false;
        }
        $s = SentencePart\Action::THIRD_PERSON_PLURAL_PLACEHOLDER;
        $verbS = ''; // Whether the verb of imperitive action should have an s.
        $beforeText = '';

        if ($opponentDoes) {
            $beforeText = 'your opponent ';
            if (!$mandatory) {
                $beforeText .= 'can ';
            } else {
                $verbS = 's';
            }
        } else {
            if (!$mandatory) {
                $beforeText = 'you can ';
            }
        }
        switch ($state) {
            case '破棄':
                $doesAction = "{$beforeText}destroy$verbS [subject]";
                break;
            case '未行動に':
                $doesAction = "{$beforeText}untap$verbS [subject]";
                break;
            case '行動済みに':
                $doesAction = "{$beforeText}tap$verbS [subject]";
                break;
            case '手札に入':
                $doesAction = "{$beforeText}return$verbS [subject] to its owner's hand";
                break;
            case '登場':
                $doesAction = "{$beforeText}summon$verbS [subject]";
                break;
            case 'デッキの下に置く':
                $doesAction = "{$beforeText}send$verbS [subject] to the bottom of the deck";
                break;
            case 'デッキの上に置く':
                $doesAction = "{$beforeText}send$verbS [subject] to the top of the deck";
                break;
            case '除外':
                $doesAction = "{$beforeText}remove$verbS [subject] from play";
                break;
            default:
                $verb = $gainsOrLoses === '得る' ? 'gain' : 'lose';
                $youCan = $mandatory ? "$verb$s" : "can $verb";
                if (isset($what)) {
                    $doesAction = "$youCan $what";
                } else {
                    throw new \InvalidArgumentException("Unexpected action: $action");
                }
                if ($untilEndOfTurnSource) {
                    $doesAction .= ' ' . TurnAndBattle::autoTranslate($untilEndOfTurnSource);
                }
        }

        return new Action($doesAction);
    }
}
