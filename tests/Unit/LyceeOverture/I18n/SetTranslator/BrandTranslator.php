<?php
declare(strict_types=1);

namespace Tests\Unit\LyceeOverture\I18n\SetTranslator;

class BrandTranslator
{
    private static $map = [
        'オーガスト' => 'August',
        '神姫PROJECT' => 'Kamihime Project',
        'ゆずソフト' => 'Yuzusoft',
        'ガールズ＆パンツァー' => 'Girls & Panzer',
        'ブレイブソード×ブレイズソウル' => 'BraveSword X BlazeSoul',
    ];

    private $search;
    private $replace;

    public function __construct()
    {
        $this->search = array_keys(self::$map);
        $this->replace = array_values(self::$map);
    }

    public function translate(string $text): string
    {
        return str_replace($this->search, $this->replace, $text);
    }
}