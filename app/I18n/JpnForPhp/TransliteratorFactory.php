<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\JpnForPhp;

use JpnForPhp\Transliterator\Transliterator;

class TransliteratorFactory
{
    public static function getInstance(): Transliterator
    {
        $instance = new Transliterator();
        $instance->setSystem(new RomajiSystem());
        return $instance;
    }
}