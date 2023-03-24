<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\Tools;

class JapaneseSentenceSplitter
{
    public const REGEX = "/(?<=[\n。])/u";
    public const REPLACE_REGEX = "/(.*?[\n。])|(.+?$)/u";

    public static function split(string $text): array
    {
        $ret = preg_split(self::REGEX, $text);

        if ($ret === false) {
            throw new \LogicException(preg_last_error_msg());
        }

        return $ret;
    }

    public static function replaceCallback(string $text, callable $callback): string
    {
        return preg_replace_callback(self::REPLACE_REGEX, $callback, $text);
    }
}
