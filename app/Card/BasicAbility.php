<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

use Illuminate\Support\Str;

/**
 * Constants should maintain backwards-compatibility with classic Lycee.
 *
 * https://github.com/amcsi/lycdb/blob/master/module/Lycee/src/Lycee/Char.php#L18-L35
 * https://github.com/amcsi/lycdb/blob/master/module/Lycee/src/Lycee/Lang.php#L7-L34
 *
 */
class BasicAbility
{
    public const STEP = 0;
    public const SIDE_STEP = 1;
    public const ORDER_STEP = 2;
    public const SUPPORTER = 8;
    public const BONUS = 12;
    public const PENALTY = 13;
    public const AGGRESSIVE = 16;
    public const ORDER_CHANGE = 18;
    public const ENGAGE = 19;
    public const RECOVERY = 20;
    public const LEADER = 21;
    public const ASSIST = 22;
    public const GUTS = 23;

    private static $japaneseMap = [
        'アグレッシブ' => self::AGGRESSIVE,
        'ステップ' => self::STEP,
        'サイドステップ' => self::SIDE_STEP,
        'オーダーステップ' => self::ORDER_STEP,
        'サポーター' => self::SUPPORTER,
        'ボーナス' => self::BONUS,
        'ペナルティ' => self::PENALTY,
        'オーダーチェンジ' => self::ORDER_CHANGE,
        'エンゲージ' => self::ENGAGE,
        'リカバリー' => self::RECOVERY,
        'リーダー' => self::LEADER,
        'アシスト' => self::ASSIST,
        'ガッツ' => self::GUTS,
    ];

    public static function getJapaneseToMarkup(): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $flippedConstants = array_flip((new \ReflectionClass(static::class))->getConstants());
        return array_map(
            function ($basicAbilityId) use ($flippedConstants) {
                return str_replace('_', ' ', Str::title(strtolower($flippedConstants[$basicAbilityId])));
            },
            self::$japaneseMap
        );
    }
}
