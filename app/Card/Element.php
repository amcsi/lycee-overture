<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Card;

class Element
{
    // Special colorless element.
    public const STAR = 0;

    public const SNOW = 1;
    public const MOON = 2;
    public const FLOWER = 3;
    public const SPACE = 4;
    public const SUN = 5;

    public static $elementMap = [
        '無' => self::STAR,
        '雪' => self::SNOW,
        '月' => self::MOON,
        '花' => self::FLOWER,
        '宙' => self::SPACE,
        '日' => self::SUN,
    ];

    private static $elementToMarkupMap = [
        '無' => 'star',
        '雪' => 'snow',
        '月' => 'moon',
        '花' => 'flower',
        '宙' => 'space',
        '日' => 'sun',
    ];

    /**
     * Returns all the elements including the star.
     */
    public static function getAllLowerCase(): array
    {
        $constants = (new \ReflectionClass(static::class))->getConstants();
        /** @noinspection PhpUnhandledExceptionInspection */
        return array_combine(array_map('strtolower', array_keys($constants)), $constants);
    }

    /**
     * Returns all the elements excluding the star.
     */
    public static function getAllColoredLowerCase(): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $elements = self::getAllLowerCase();
        unset($elements['star']);
        return $elements;
    }

    /**
     * @return array [1 => 'snow', ...]
     */
    public static function getElementKeys(): array
    {
        $elementKeys = array_flip(self::getAllColoredLowerCase());
        foreach ($elementKeys as $key => $elementKey) {
            $elementKeys[$key] = $elementKey;
        }
        return $elementKeys;
    }

    public static function getColoredElementMap(): array
    {
        $return = self::$elementMap;
        unset($return['無']);
        return $return;
    }

    /**
     * Returns all the DB keys for the costs (cost_*)
     * @return array [0 => 'cost_star', ...]
     */
    public static function getCostKeys(): array
    {
        return array_map(
            function ($elementKey) {
                return 'cost_' . $elementKey;
            },
            array_keys(static::getAllLowerCase())
        );
    }

    public static function getElementToMarkupMap(): array
    {
        return self::$elementToMarkupMap;
    }
}
