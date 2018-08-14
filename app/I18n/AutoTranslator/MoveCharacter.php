<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;

class MoveCharacter
{
    public static function autoTranslate(string $text): string
    {
        $subjectRegex = Subject::getUncapturedRegex();

        // "This character moves to X." or "This character and that character change places."
        $pattern = "/($subjectRegex)[をと]($subjectRegex)(を入れ替える|に移動(する|できる))/u";
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
        $changePlacesOrMoveSource = next($matches);
        $canOrDoSource = next($matches);
        if ($changePlacesOrMoveSource === 'を入れ替える') {
            return sprintf('%s and %s change places', $sourceSubject->getSubjectText(), $destination->getSubjectText());
        }
        $mandatory = true;
        if ($canOrDoSource === 'できる') {
            $mandatory = false;
        }
        $verb = $mandatory ? 'move' : 'you can move';

        return preg_replace(
            '/ {2,}/',
            ' ',
            sprintf(
                " %s %s to %s",
                $verb,
                $sourceSubject->getSubjectText(),
                $destination->getSubjectText()
            )
        );
    }
}
