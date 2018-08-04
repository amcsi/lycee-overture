<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;

class Equip
{
    public static function autoTranslate(string $text): string
    {
        $subjectRegex = Subject::getUncapturedRegex();

        return preg_replace_callback("/($subjectRegex)を($subjectRegex)に装備する/u", ['self', 'callback'], $text);
    }

    private static function callback(array $matches): string
    {
        return sprintf(
            'equip %s to %s',
            Subject::createInstance($matches[1])->getSubjectTextWithoutPlaceholders(),
            Subject::createInstance($matches[2])->getSubjectTextWithoutPlaceholders()
        );
    }
}
