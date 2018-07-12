<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart;

/**
 * Combines parts of a sentence, making sure posessives and plurals are taken into account.
 */
class SentenceCombiner
{
    public static function combine(Subject $subject, Action $action): string
    {
        $subjectText = $subject->getSubjectText();
        if ($action->demandsPosessiveSubject()) {
            // E.g. characters => characters'
            $subjectText = str_replace('s' . Subject::POSSESSIVE_PLACEHOLDER, "'", $subjectText);
            // E.g. character => character's
            $subjectText = str_replace(Subject::POSSESSIVE_PLACEHOLDER, "'s", $subjectText);
        } else {
            // E.g. character
            $subjectText = str_replace(Subject::POSSESSIVE_PLACEHOLDER, '', $subjectText);
        }
        $actionText = $action->getActionTextWithPlaceholders();
        // Whether the sentence subject is plural. If the Action actually contains a subject (in the grammar sense),
        // pluralness is determined by the Action. Otherwise the Subject determines it.
        $plural = $action->demandsPosessiveSubject() ? $action->getPosessivePlural() : $subject->plural();
        // E.g. character getS something, or characters get something.
        $actionText = str_replace(Action::THIRD_PERSON_PLURAL_PLACEHOLDER, $plural ? '' : 's', $actionText);
        return "$subjectText $actionText";
    }
}
