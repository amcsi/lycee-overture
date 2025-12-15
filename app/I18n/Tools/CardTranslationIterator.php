<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\Tools;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CardTranslationIterator
{
    /**
     * Iterates CardTranslations in chunks.
     *
     * The passed callback accepts the japanese CardTranslation as the first argument, and CardTranslation
     * of locale $locale as the second.
     */
    public static function iterateLazy(Builder $builder, string $locale, $callback)
    {
        $builder = (clone $builder)->orderBy('card_id');
        $japaneseBuilder = (clone $builder)->where('locale', Locale::JAPANESE);
        $japaneseBuilder->chunk(1000, function (Collection $japaneseCards) use (
            $callback,
            $locale,
            $builder,
        ) {
            /** @var CardTranslation $first */
            $first = $japaneseCards->first();
            /** @var CardTranslation $last */
            $last = $japaneseCards->last();
            $translatedCards = (clone $builder)
                ->where('locale', $locale)
                ->where('card_id', '>=', $first->card_id)
                ->where('card_id', '<=', $last->card_id)
                ->get()
                ->keyBy(
                    function (
                        CardTranslation $cardTranslation
                    ) {
                        return $cardTranslation->card_id;
                    }
                );
            foreach ($japaneseCards as $japaneseCard) {
                $cardId = $japaneseCard->card_id;
                $translatedCard = $translatedCards->get($cardId) ?
                    // Update based on the existing translated card data.
                    $translatedCards[$cardId] :
                    // Create a new translated card based on the Japanese one.
                    $japaneseCard->replicate()->setAttribute('locale', $locale);
                $callback($japaneseCard, $translatedCard);
            }
        });
    }
}
