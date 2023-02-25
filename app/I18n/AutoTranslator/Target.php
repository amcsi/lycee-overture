<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;

/**
 * For translating text within {targets}
 */
class Target
{
    public static function autoTranslate(string $text): string
    {
        return preg_replace_callback('/\{([^}]+)}/', self::callback(...), $text);
    }

    public static function callback(array $matches): string
    {
        $subjectRegex = Subject::getUncapturedRegex();

        // "This character gains X."
        $pattern = "/($subjectRegex)/u";

        return '{' . trim(
                preg_replace_callback(
                    $pattern,
                    function (array $matches): string {
                        return Subject::createInstance($matches[0])->getSubjectText();
                    },
                    $matches[1]
                )
            ) . '}';
    }
}
