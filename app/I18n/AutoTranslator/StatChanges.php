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

        // language=regexp
        $statPlusMinusAction = 'に((?:(?:AP|DP|SP|DMG)[+-]\\d(?:, |または)?)+)';
        // language=regexp
        $statsToNumberAction = 'の((?:と?(?:AP|DP|SP|DMG))+)を(\d)に';

        $pattern = "/($subjectRegex)({$statPlusMinusAction}|{$statsToNumberAction})する\./u";

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
        $statChanges = next($matches); // The stat changes involved for stat changes action.
        $posessiveSubject = strpos($actionText, 'の') === 0; // {Target's}
        $thirdPersonPluralPlaceholder = Action::THIRD_PERSON_PLURAL_PLACEHOLDER;
        if (strpos($actionText, 'に') === 0) {

            $actionText = sprintf(
                'get%s %s.',
                $thirdPersonPluralPlaceholder,
                $statChanges
            );
            // ... or ...
            $actionText = str_replace('または', ' or ', $actionText);

            $action = new Action($actionText, $posessiveSubject, false);
        } elseif (strpos($actionText, 'の') === 0) {
            // ... 's Stat becomes 0.

            $stats = next($matches);
            $toWhatValue = next($matches);

            $posessivePlural = strpos($stats, 'と') !== false;

            $action = new Action(
                sprintf(
                    "%s become%s %d.",
                    str_replace('と', ' and ', $stats),
                    $thirdPersonPluralPlaceholder,
                    $toWhatValue
                ), $posessiveSubject, $posessivePlural
            );
        } else {
            throw new \InvalidArgumentException("Unexpected action: $actionText");
        }
        return SentenceCombiner::combine($subject, $action);
    }
}
