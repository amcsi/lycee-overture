<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n;

class Locale
{
    public const JAPANESE = 'ja';
    public const ENGLISH = 'en';
    public const SPANISH = 'es';
    public const HUNGARIAN = 'hu';

    public const ENGLISH_AUTO = 'en-auto';

    public const ENGLISH_DEEPL = 'en-deepl';

    public const TRANSLATION_LOCALES = [self::ENGLISH, self::SPANISH, self::HUNGARIAN];
}
