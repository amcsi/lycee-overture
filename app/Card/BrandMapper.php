<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use amcsi\LyceeOverture\Set;
use Illuminate\Support\Arr;

class BrandMapper
{
    /**
     * @var array|null Brand abbreviations are the keys, and values are an array of English set names part of the brand.
     */
    private static $brands;
    private static $setNameJaToBrandMap;

    public function __construct(private Set $setModel)
    {
    }

    public static function getBrand(string $setNameJa): ?string
    {
        return self::getSetNameJaToBrandMap()[$setNameJa] ?? null;
    }

    private static function getSetNameJaToBrandMap(): array
    {
        if (!self::$setNameJaToBrandMap) {
            self::$setNameJaToBrandMap = [];
            foreach (self::getBrands() as $brand => $setNames) {
                foreach ($setNames as $setNameJa) {
                    self::$setNameJaToBrandMap[$setNameJa] = $brand;
                }
            }
        }
        return self::$setNameJaToBrandMap;
    }

    private static function getBrands()
    {
        if (!self::$brands) {
            $setNameEnJaMap = array_flip(config('lycee.sets'));
            self::$brands = array_map(
                fn($englishSetNames) => array_map(
                    fn($setNameEn) => $setNameEnJaMap[$setNameEn] ?? $setNameEn,
                    $englishSetNames
                ),
                config('lycee.brands')
            );
        }
        return self::$brands;
    }

    /**
     * @return array|int[]
     */
    public function fetchSetIdsByBrands(array $brands)
    {
        $setNamesArrays = [];
        foreach ($brands as $brand) {
            $setNamesArrays[] = self::getBrands()[$brand] ?? null;
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
        foreach (self::getBrands() as $setNames) {
            $setNamesArrays[] = $setNames;
        }
        $setNames = Arr::flatten($setNamesArrays);

        return $this->setModel->whereNotIn('name_ja', $setNames)->get()->pluck('id')->toArray();
    }
}
