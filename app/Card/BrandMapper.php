<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Set;
use Illuminate\Support\Arr;

class BrandMapper
{
    private static $brands = [
        'AIG' => [ // Aigis
            '千年戦争アイギス',
        ],
        'AQP' => [ // AquaPlus
            'アクアプラス',
        ],
        'AUG' => [ // August
            'オーガスト',
        ],
        'BXB' => [ // Brave Sword X Blaze Soul
            'ブレイブソード×ブレイズソウル',
        ],
        'FGO' => [
            'Fate/Grand Order',
        ],
        'GUP' => [ // Girls & Panzer
            'ガールズ＆パンツァー最終章',
            'ガールズ＆パンツァー戦車道大作戦！',
        ],
        'KHP' => [ // Kamihime Project
            '神姫PROJECT',
        ],
        'NEX' => [ // Nexton
            'ネクストン',
        ],
        'OSP' => [ // Oshiro Project: RE
            '御城プロジェクト：ＲＥ',
        ],
        'TOA' => [ // Toaru Majutsu no Index
            'とある魔術の禁書目録Ⅲ',
        ],
        'VA' => [ // Visual Arts
            'ビジュアルアーツ',
        ],
        'YUZ' => [ // Yuzusoft
            'ゆずソフト',
        ],
    ];

    private static $setNameJaToBrandMap;

    private Set $setModel;

    public function __construct(Set $setModel)
    {
        $this->setModel = $setModel;
    }

    public static function getBrand(string $setNameJa): ?string
    {
        return self::getSetNameJaToBrandMap()[$setNameJa] ?? null;
    }

    private static function getSetNameJaToBrandMap(): array
    {
        if (!self::$setNameJaToBrandMap) {
            self::$setNameJaToBrandMap = [];
            foreach (self::$brands as $brand => $setNames) {
                foreach ($setNames as $setNameJa) {
                    self::$setNameJaToBrandMap[$setNameJa] = $brand;
                }
            }
        }
        return self::$setNameJaToBrandMap;
    }

    /**
     * @return array|int[]
     */
    public function fetchSetIdsByBrands(array $brands)
    {
        $setNamesArrays = [];
        foreach ($brands as $brand) {
            $setNamesArrays[] = self::$brands[$brand] ?? null;
        }
        $setNames = Arr::flatten($setNamesArrays);

        return $this->setModel->whereIn('name_ja', $setNames)->get()->pluck('id')->toArray();
    }

    /**
     * @return array|int[]
     */
    public function fetchSetIdsOfUnknownBrands()
    {
        $setNamesArrays = [];
        foreach (self::$brands as $setNames) {
            $setNamesArrays[] = $setNames;
        }
        $setNames = Arr::flatten($setNamesArrays);

        return $this->setModel->whereNotIn('name_ja', $setNames)->get()->pluck('id')->toArray();
    }
}
