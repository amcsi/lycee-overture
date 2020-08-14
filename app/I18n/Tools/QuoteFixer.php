<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\Tools;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\Suggestion;

class QuoteFixer
{
    /**
     * Fixes quotes on all models.
     */
    public static function fixQuotesOnAll(bool $dryRun): array
    {
        $lazyCollection = CardTranslation::whereIn('locale', [Locale::ENGLISH])
            ->cursor()
            ->concat(Suggestion::whereLocale(Locale::ENGLISH)->cursor());
        /** @var CardTranslation|Suggestion $item */
        $ret = [];
        foreach ($lazyCollection as $item) {
            foreach (CardTranslation::TEXT_COLUMNS as $column) {
                $item[$column] = self::fixQuotes($item[$column]);
            }
            if ($item->isDirty()) {
                $dirty = $item->getDirty();
                $ret[] = [
                    'model' => get_class($item),
                    'id' => $item->card_id,
                    'old' => array_intersect_key($item->getOriginal(), $item->getDirty()),
                    'new' => $dirty,
                ];
                if (!$dryRun) {
                    $item->save();
                }
            }
        }
        return $ret;
    }

    public static function fixQuotes(string $string): string
    {
        return preg_replace('/"(.*?)"/', '“$1”', $string);
    }
}
