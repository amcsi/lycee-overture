<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Action;
use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\SentenceCombiner;
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
        $statPlusMinusAction = "に(${turnAndBattleRegex})?((?:(?:AP|DP|SP|DMG)[+-]\\d(?:, |または)?)+)";
        // language=regexp
        $statsToNumberAction = 'の((?:と?(?:AP|DP|SP|DMG))+)を(\d)に';

        $pattern = "/($subjectRegex)({$statPlusMinusAction}|{$statsToNumberAction})(する|できる)\./u";

        return preg_replace_callback(
            $pattern,
            ['self', 'callback'],
            $text
        );
    }

    private static function callback(array $matches): string
    {
        $subjectPart = next($matches);
        $subject = Subject::createInstance($subjectPart);

        $actionText = next($matches);
        $turnAndBattleSource = next($matches); // e.g. "Until the end of battle"
        $statChanges = next($matches); // The stat changes involved for stat changes action.
        $posessiveSubject = strpos($actionText, 'の') === 0; // {Target's}
        $stats = next($matches);
        $toWhatValue = next($matches);
        $canOrDoSource = next($matches);
        $mandatory = true;
        if ($canOrDoSource === 'できる') {
            $mandatory = false;
        }
        $thirdPersonPluralPlaceholder = Action::THIRD_PERSON_PLURAL_PLACEHOLDER;
        $turnAndBattleText = $turnAndBattleSource ? ' ' . TurnAndBattle::autoTranslate($turnAndBattleSource) : '';
        if (strpos($actionText, 'に') === 0) {
            $verb = $mandatory ? "get$thirdPersonPluralPlaceholder" : 'can get';
            $actionText = sprintf(
                '%s %s%s.',
                $verb,
                $statChanges,
                $turnAndBattleText
            );
            // ... or ...
            $actionText = str_replace('または', ' or ', $actionText);

            $action = new Action($actionText, $posessiveSubject, false);
        } elseif (strpos($actionText, 'の') === 0) {
            // ... 's Stat becomes 0.

            $posessivePlural = strpos($stats, 'と') !== false;
            $verb = $mandatory ? "become$thirdPersonPluralPlaceholder" : 'can become';

            $action = new Action(
                sprintf(
                    "%s %s %d%s.",
                    str_replace('と', ' and ', $stats),
                    $verb,
                    $toWhatValue,
                    $turnAndBattleText
                ), $posessiveSubject, $posessivePlural
            );
        } else {
            throw new \InvalidArgumentException("Unexpected action: $actionText");
        }
        return SentenceCombiner::combine($subject, $action);
    }
}
