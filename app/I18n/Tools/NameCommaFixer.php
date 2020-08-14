<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\Tools;

class NameCommaFixer
{
    public static function fix(string $string): string
    {
        return preg_replace_callback('/“(.*?)”/u',
            function ($match) {
                return str_replace(', ', ' ', $match[0]);
            },
            $string);
    }
}
