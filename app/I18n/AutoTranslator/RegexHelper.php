<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

class RegexHelper
{
    /**
     * @param string $subregex Removes capturing from the given regex subpattern
     */
    public static function uncapture(string $subregex): string
    {
        // Look ahead for any parentheses to see if there it's for a
        // lookahead/lookbehind (opening parentheses followed by a question mark),
        // and for all these NON-lookahead/lookbehind parentheses, make them non-capturing by adding ?:.
        $return = preg_replace('/\((?!\?)/', '(?:', $subregex);
        if ($return && $return[0] !== '(') {
            // Ensure this regex is enclosed with parentheses for isolation.
            $return = "(?:$return)";
        }
        return $return;
    }
}
