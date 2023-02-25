<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Action;
use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;

/**
 * Automatically translates stat changes.
 */
class StatChanges
{
    public static function autoTranslate(string $text): string
    {
        $subjectRegex = Subject::getUncapturedRegex();

        $turnAndBattleRegex = TurnAndBattle::getUncapturedRegex();

        // language=regexp
        $statPlusMinusAction = "に({$turnAndBattleRegex})?((?:(?:AP|DP|SP|DMG)[+-](?:\\d|\\[$subjectRegex])(?:, |または)?)+)";
        // language=regexp
        $statsToNumberAction = "を(\\d|\[$subjectRegex])に";

        $pattern = "/($subjectRegex)({$statPlusMinusAction}|{$statsToNumberAction})(する|できる)\./u";
        return Action::subjectReplace(
            $pattern,
            [self::class, 'callback'],
            $text
        );
    }

    public static function callback(array $matches): Action
    {
        $actionText = next($matches);
        $turnAndBattleSource = next($matches); // e.g. "Until the end of battle"
        $statChanges = next($matches); // The stat changes involved for stat changes action.
        $toWhatValue = next($matches);
        $canOrDoSource = next($matches);
        $mandatory = true;
        if ($canOrDoSource === 'できる') {
            $mandatory = false;
        }
        $thirdPersonPluralPlaceholder = Action::THIRD_PERSON_PLURAL_PLACEHOLDER;
        $turnAndBattleText = $turnAndBattleSource ? ' ' . TurnAndBattle::autoTranslate($turnAndBattleSource) : '';
        if (str_starts_with($actionText, 'に')) {
            $verb = $mandatory ? "get$thirdPersonPluralPlaceholder" : 'can get';
            $subjectRegex = Subject::getUncapturedRegex();
            $statChanges = preg_replace_callback(
                "/\\[($subjectRegex)]/u",
                [self::class, 'subjectBetweenBracketsReplaceCallback'],
                $statChanges
            );

            $actionText = sprintf(
                '%s %s%s.',
                $verb,
                $statChanges,
                $turnAndBattleText
            );
            // ... or ...
            $actionText = str_replace('または', ' or ', $actionText);

            $action = new Action($actionText);
        } else {
            // ... 's Stat becomes 0.

            if ($toWhatValue[0] === '[') {
                $toWhatValue = preg_replace_callback(
                    '/^\[(.*)]$/',
                    [self::class, 'subjectBetweenBracketsReplaceCallback'],
                    $toWhatValue
                );
            }

            $verb = $mandatory ? "become$thirdPersonPluralPlaceholder" : 'can become';

            $action = new Action(
                sprintf(
                    "%s %s%s.",
                    $verb,
                    $toWhatValue,
                    $turnAndBattleText
                )
            );
        }
        return $action;
    }

    private static function subjectBetweenBracketsReplaceCallback(array $matches): string
    {
        return '[' . trim(Subject::createInstance($matches[1])->getSubjectText()) . ']';
    }
}
