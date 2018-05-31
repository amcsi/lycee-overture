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

    /**
     * Returns all the elements including the star.
     */
    public static function getAll(): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return (new \ReflectionClass(static::class))->getConstants();
    }

    /**
     * Returns all the elements excluding the star.
     */
    public static function getAllColored(): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $elements = (new \ReflectionClass(static::class))->getConstants();
        unset($elements['STAR']);
        return $elements;
    }

    /**
     * @return array [1 => 'snow', ...]
     */
    public static function getElementKeys(): array
    {
        $elementKeys = array_flip(self::getAllColored());
        foreach ($elementKeys as $key => $elementKeyUpperCase) {
            $elementKeys[$key] = strtolower($elementKeyUpperCase);
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
        return array_map(function ($elementKeyUpperCase) {
            return 'cost_' . strtolower($elementKeyUpperCase);
        }, array_keys(static::getAll()));
    }
}
