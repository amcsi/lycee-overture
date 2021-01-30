<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\NameTranslator\NameTranslator;

/**
 * Translates <quotes> "like these".
 */
class QuoteTranslator
{
    public function __construct(private NameTranslator $nameTranslator)
    {
    }

    public function autoTranslate(string $autoTranslated): string
    {
        $autoTranslated = preg_replace_callback(
            '/(?<=＜)(.+?)(?=＞)/u',
            [$this, 'characterTypeCallback'],
            $autoTranslated
        );
        return preg_replace_callback(
            '/(?<=「)(.+?)(?=」)/u',
            [$this, 'nameCallback'],
            $autoTranslated
        );
    }

    public function characterTypeCallback(array $matches): string
    {
        return $this->nameTranslator->tryTranslateCharacterType($matches[0]);
    }

    public function nameCallback(array $matches): string
    {
        return $this->nameTranslator->tryTranslateName($matches[0], true);
    }
}
