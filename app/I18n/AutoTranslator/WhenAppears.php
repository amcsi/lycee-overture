<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;

/**
 * For translating when a character appears in battle.
 */
class WhenAppears
{
    public static function autoTranslate(string $text): string
    {
        $subjectRegex = Subject::getUncapturedRegex();

        // "This character gains X."
        $pattern = "/($subjectRegex)が登場したとき/u";
        return preg_replace_callback($pattern, ['self', 'callback'], $text);
    }

    private static function callback(array $matches): string
    {
        $subjectSource = next($matches);
        $subject = Subject::createInstance($subjectSource);

        return sprintf(
            'when%s is summoned',
            $subject->getSubjectTextWithoutPlaceholders()
        );
    }
}
