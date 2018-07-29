<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

class AbilityType
{
    public const ACTIVATE = 1; // Blue
    public const TRIGGER = 2;// Red
    public const CONTINUOUS = 3;// Green
    public const COST = 4;// Orange

    public static function getJapaneseMap(): array
    {
        return [
            '宣言' => self::ACTIVATE,
            '誘発' => self::TRIGGER,
            '常時' => self::CONTINUOUS,
            'コスト' => self::COST,
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
                    default:
                        throw new \LogicException("Unexpected id: $id");
                }
            },
            self::getJapaneseMap()
        );
    }
}
