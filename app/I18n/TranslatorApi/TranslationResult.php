<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\TranslatorApi;

/**
 * Result of a translation from Yahoo's Japanese kanji decoder.
 */
class TranslationResult
{
    public function __construct(private string $xml)
    {
    }

    public function getWords(): array
    {
        $return = [];
        $xmlElement = new \SimpleXMLElement($this->xml);
        $words = json_decode(json_encode($xmlElement->Result->WordList));

        foreach ($words->Word as $word) {
            if (isset($word->Roman)) {
                $return[] = $word->Roman;
            }
        }

        return $return;
    }
}
