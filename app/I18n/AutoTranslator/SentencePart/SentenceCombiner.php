<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart;

/**
 * Combines parts of a sentence, making sure plurals are taken into account.
 */
class SentenceCombiner
{
    public static function combine(Subject $subject, Action $action): string
    {
        // E.g. character
        $subjectText = $subject->getSubjectText();
        $actionText = $action->getActionTextWithPlaceholders();
        // Whether the sentence subject is plural.
        $plural = $subject->plural();
        // E.g. character getS something, or characters get something.
        $actionText = str_replace(Action::THIRD_PERSON_PLURAL_PLACEHOLDER, $plural ? '' : 's', $actionText);
        return str_contains($actionText, '[subject]') ?
            ' ' . str_replace('[subject]', trim($subjectText), $actionText) : // Subject placeholder present.
            "$subjectText $actionText";
    }
}
