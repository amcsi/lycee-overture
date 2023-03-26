<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\Tools;

use Illuminate\Support\Arr;

class JapaneseSentenceSplitter
{
    public static function replaceCallback(string $text, callable $callback): string
    {
        $bracketStack = [];
        static $terminateChars = ["\n", "。"];
        static $bracketPairs = ['[' => ']', '{' => '}', '<' => '>'];

        $ret = '';
        $word = '';

        $chars = mb_str_split($text);

        foreach ($chars as $char) {
            $word .= $char;
            if (isset($bracketPairs[$char])) {
                $bracketStack[] = $bracketPairs[$char];
                // Did we just close the last bracket on the stack?
            } else if ($bracketStack && $char === Arr::last($bracketStack)) {
                array_pop($bracketStack);
            }

            if (!$bracketStack && in_array($char, $terminateChars, true)) {
                $ret .= $callback([$word]);
                $word = '';
            }
        }

        if ($word) {
            $ret .= $callback([$word]);
        }

        return $ret;
    }
}
