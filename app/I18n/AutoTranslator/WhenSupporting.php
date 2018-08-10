<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;

/**
 * Auto-translation for text related to supporting.
 */
class WhenSupporting
{
    public static function autoTranslate(string $text): string
    {
        $subjectRegex = Subject::getUncapturedRegex();
        $text = preg_replace('/このキャラ(で|が)サポートをしたとき/u', 'when this character supports another character', $text);
        $text = preg_replace_callback(
            "/(?:($subjectRegex)で)?このキャラにサポートをしたとき/u",
            function (array $matches): string {
                $return = 'when this character gets supported';
                $subject = next($matches);
                if ($subject) {
                    $return .= ' by' . Subject::autoTranslateStrict($subject);
                }
                return $return;
            },
            $text
        );
        return $text;
    }
}
