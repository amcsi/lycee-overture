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
        // language=regexp
        $getsSomethingActionRegex = 'は((?:\[.+?\])+)を得る|を(?:(破棄|未行動に|行動済みに|手札に入|登場|除外)(れる|する|できる)|(デッキの[下上]に置く))';

        // "This character gains X."
        $pattern = "/($subjectRegex)($getsSomethingActionRegex)/u";
        $text = Action::subjectReplaceCallback(
            $pattern,
            [self::class, 'callback'],
            $text
        );

        return $text;
    }

    public static function callback(array $matches): Action
    {
        $action = next($matches);
        $what = next($matches);
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
        $youCan = $mandatory ? '' : 'you can ';
        switch ($state) {
            case '破棄':
                $doesAction = "{$youCan}destroy [subject]";
                break;
            case '未行動に':
                $doesAction = "{$youCan}untap [subject]";
                break;
            case '行動済みに':
                $doesAction = "{$youCan}tap [subject]";
                break;
            case '手札に入':
                $doesAction = "{$youCan}return [subject] to its owner's hand";
                break;
            case '登場':
                $doesAction = "{$youCan}summon [subject]";
                break;
            case 'デッキの下に置く':
                $doesAction = "{$youCan}send [subject] to the bottom of the deck";
                break;
            case 'デッキの上に置く':
                $doesAction = "{$youCan}send [subject] to the top of the deck";
                break;
            case '除外':
                $doesAction = "{$youCan}remove [subject] from play";
                break;
            default:
                $youCan = $mandatory ? "gain$s" : "can gain";
                if (isset($what)) {
                    $doesAction = "$youCan $what";
                } else {
                    throw new \InvalidArgumentException("Unexpected action: $action");
                }
        }

        return new Action("$doesAction");
    }
}
