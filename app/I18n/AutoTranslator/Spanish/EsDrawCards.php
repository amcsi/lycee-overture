<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator\Spanish;

class EsDrawCards
{
    public static function callback(array $matches): string
    {
        $opponent = next($matches);
        $amount = next($matches);
        $canOrDoSource = next($matches);
        $mandatory = true;
        if ($canOrDoSource === 'できる') {
            $mandatory = false;
        }
        $who = $opponent ? 'tu adversario ' : '';
        if ($mandatory && !$opponent) {
            // Avoid the subject ("draw n cards")
            $who = '';
        }

        $verbConjugation = $opponent || $mandatory ? '' : 's';
        $s = $amount != '1' ? 's' : '';
        $verb = $mandatory ? "roba$verbConjugation" : "puede$verbConjugation robar";
        return "$who$verb $amount carta$s";
    }
}
