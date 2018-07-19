<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;

class MoveCharacter
{
    public static function autoTranslate(string $text): string
    {
        $subjectRegex = Subject::getUncapturedRegex();

        // "This character gains X."
        $pattern = "/($subjectRegex)を($subjectRegex)に移動(する|できる)\./u";
        $text = preg_replace_callback(
            $pattern,
            ['self', 'callback'],
            $text
        );

        return $text;
    }

    private static function callback(array $matches): string
    {
        $sourceSubject = Subject::createInstance(next($matches));
        $destination = Subject::createInstance(next($matches));
        $canOrDoSource = next($matches);
        $mandatory = true;
        if ($canOrDoSource === 'できる') {
            $mandatory = false;
        }
        $verb = $mandatory ? 'move' : 'you can move';

        return preg_replace(
            '/ {2,}/',
            ' ',
            sprintf(
                " %s %s to %s.",
                $verb,
                str_replace(Subject::POSSESSIVE_PLACEHOLDER, '', $sourceSubject->getSubjectText()),
                str_replace(Subject::POSSESSIVE_PLACEHOLDER, '', $destination->getSubjectText())
            )
        );
    }
}
