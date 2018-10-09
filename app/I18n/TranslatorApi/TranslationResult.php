<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\TranslatorApi;

/**
 * Result of a translation from Yahoo's Japanese kanji decoder.
 */
class TranslationResult
{
    private $xml;

    public function __construct(string $xml)
    {
        $this->xml = $xml;
    }

    public function getWords(): array
    {
        $return = [];
        $xmlElement = new \SimpleXMLElement($this->xml);
        $words = json_decode(json_encode($xmlElement->Result->WordList));

        foreach ($words->Word as $word) {
            $return[] = $word->Roman;
        }

        return $return;
    }
}
