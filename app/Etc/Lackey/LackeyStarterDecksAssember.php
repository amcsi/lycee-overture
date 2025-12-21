<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Etc\Lackey;

use amcsi\LyceeOverture\Models\CardDeck;
use amcsi\LyceeOverture\Models\Deck;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * Assembles the starter decks for LackeyCCG.
 */
class LackeyStarterDecksAssember
{
    public function handle(Filesystem $dstAdapter): void
    {

        /** @var Deck[] $decks */
        $decks = Deck::with(['cards', 'cards.translations'])->get();

        // We want to find just the fully translated decks.
        $fullyTranslatedDecks = [];
        foreach ($decks as $deck) {
            $fullyTranslated = true;
            foreach ($deck->cards as $card) {
                if ($card->getBestTranslation()->kanji_count > 0) {
                    $fullyTranslated = false;
                    break;
                }
            }
            if ($fullyTranslated) {
                $fullyTranslatedDecks[] = $deck;
            }
        }

        $template = file_get_contents(__DIR__ . '/../../../resources/lackeyccg/decks/base.xml');
        $formatDeckName = fn(string $name) => str_replace([' ', '/'], '_', $name);

        foreach ($fullyTranslatedDecks as $deck) {
            $deckLines = [];
            $leader = '';
            foreach ($deck->cards as $card) {
                /** @var CardDeck $cardDeck */
                $cardDeck = $card->pivot;
                for ($i = 0; $i < $cardDeck->quantity; $i++) {
                    $cardLine = sprintf('<card><name id="%s">%s</name><set>%s</set></card>',
                        $card->id,
                        $card->id,
                        LackeyNameFormatter::formatSet($card)
                    );
                    if (Str::contains($card->getBestTranslation()->basic_abilities, '[Leader]')) {
                        $leader = $cardLine;
                    } else {
                        $deckLines[] = $cardLine;
                    }
                }
            }
            $dstAdapter->put(
                sprintf('decks/%s_Starter.dek', $formatDeckName($deck->name_en)),
                str_replace(['%deck%', '%leader%'], [implode("\n", $deckLines), $leader], $template),
            );
        }
    }
}
