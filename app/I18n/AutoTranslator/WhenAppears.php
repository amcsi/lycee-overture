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
        $pattern = "/($subjectRegex)が登場(したとき|している場合)/u";
        return preg_replace_callback($pattern, ['self', 'callback'], $text);
    }

    private static function callback(array $matches): string
    {
        $subjectSource = next($matches);
        $partOrCurrent = next($matches);
        $subject = Subject::createInstance($subjectSource);

        $text = $partOrCurrent === 'したとき' ? 'when%s is summoned' : 'if%s is on the field';

        return sprintf(
            $text,
            $subject->getSubjectText()
        );
    }
}
