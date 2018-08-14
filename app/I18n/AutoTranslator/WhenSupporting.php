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
            "/(?:($subjectRegex)で)?($subjectRegex)にサポートをしたとき/u",
            function (array $matches): string {
                $subject = next($matches);
                $whoGetsSupported = next($matches);
                $whoGetsSupportedText = Subject::autoTranslateStrict($whoGetsSupported);
                $return = "when$whoGetsSupportedText gets supported";
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
