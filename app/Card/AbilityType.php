<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

class AbilityType
{
    public const ACTIVATE = 1; // Blue
    public const TRIGGER = 2;// Pink
    public const CONTINUOUS = 3;// Green
    public const COST = 4; // Orange
    public const EQUIP_RESTRICTION = 5; // No color
    public const HAND_ACTIVATE = 6; // Red

    public static function getJapaneseMap(): array
    {
        return [
            '宣言' => self::ACTIVATE,
            '誘発' => self::TRIGGER,
            '常時' => self::CONTINUOUS,
            'コスト' => self::COST,
            '装備制限' => self::EQUIP_RESTRICTION,
            '手札宣言' => self::HAND_ACTIVATE,
        ];
    }

    public static function getJapaneseToMarkup(): array
    {
        return array_map(
            function (int $id): string {
                switch ($id) {
                    case self::ACTIVATE:
                        return 'Activate';
                    case self::TRIGGER:
                        return 'Trigger';
                    case self::CONTINUOUS:
                        return 'Continuous';
                    case self::COST:
                        return 'Cost';
                    case self::EQUIP_RESTRICTION:
                        return 'Equip Restriction';
                    case self::HAND_ACTIVATE:
                        return 'Hand Activate';
                    default:
                        throw new \LogicException("Unexpected id: $id");
                }
            },
            self::getJapaneseMap()
        );
    }
}
