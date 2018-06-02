<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

class AbilityType
{
    public const IGNITION = 1; // Blue
    public const TRIGGER = 2;// Red
    public const CONTINUOUS = 3;// Green
    public const COST = 4;// Orange

    public static function getJapaneseMap(): array
    {
        return [
            '宣言' => self::IGNITION,
            '誘発' => self::TRIGGER,
            '常時' => self::CONTINUOUS,
            'コスト' => self::COST,
        ];
    }
}
