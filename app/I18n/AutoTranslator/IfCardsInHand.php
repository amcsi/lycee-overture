<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

class IfCardsInHand
{
    public static function autoTranslate(string $text): string
    {
        // language=regexp
        $pattern = '/自分の手札が(\d)枚(?:以(下|上))?の場合/';
        $text = preg_replace_callback($pattern, self::callback(...), $text);
        return $text;
    }

    public static function callback(array $matches): string
    {
        $amount = next($matches);
        $moreOrLessSource = next($matches);
        switch ($moreOrLessSource) {
            case '下':
                $moreOrLess = ' or less';
                break;
            case '上':
                $moreOrLess = ' or more';
                break;
            case '':
                $moreOrLess = '';
                break;
            default:
                throw new \LogicException("Unexpected \$moreOrLessSource: $moreOrLessSource");
        }
        $s = $amount !== '1' || $moreOrLess ? 's' : '';
        return "if you have $amount$moreOrLess card$s in your hand";
    }
}
