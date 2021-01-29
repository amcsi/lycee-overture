<?php
declare(strict_types=1);

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\CardDeck;
use amcsi\LyceeOverture\Deck;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


/**
 * Moves cards from deck table to a separate pivot table also counting the quantity of cards in the deck.
 *
 * Notes:
 * Nexton starter deck: https://lycee-tcg.com/d/?d=S4kj9q
 * Starter deck search:
 * https://lycee-tcg.com/deck/?deck=&word=%E3%82%B9%E3%82%BF%E3%83%BC%E3%82%BF%E3%83%BC&_pickup=1&_official=1&_festa=1&_user=1&align_1_low=&align_1_high=&align_2_low=&align_2_high=&align_3_low=&align_3_high=&align_4_low=&align_4_high=&align_5_low=&align_5_high=&limit=
 */
class AddCardDeckTable extends Migration
{
    private static $decks = [
        'Fate/Grand Order 2.0' => [
            ['LO-0467', 4],
            ['LO-0468', 2],
            ['LO-0473', 4],
            ['LO-0475', 4],
            ['LO-0479', 2],
            ['LO-0481', 2],
            ['LO-0484', 2],
            ['LO-0485', 2],
            ['LO-0487', 4],
            ['LO-0488', 4],
            ['LO-0489', 4],
            ['LO-0545', 2],
            ['LO-0550', 2],
            ['LO-0556', 4],
            ['LO-0557', 2],
            ['LO-0567', 4],
            ['LO-0568', 4],
            ['LO-0569', 4],
            ['LO-0570', 4],
        ],
        'Girls und Panzer Senshadou Daisakusen! 1.0' => [
            ['LO-0289', 4],
            ['LO-0346', 4],
            ['LO-0347', 4],
            ['LO-0348', 4],
            ['LO-0349', 4],
            ['LO-0350', 4],
            ['LO-0351', 4],
            ['LO-0362', 4],
            ['LO-0364', 4],
            ['LO-0366', 4],
            ['LO-0417', 4],
            ['LO-0418', 4],
            ['LO-0419', 4],
            ['LO-0420', 4],
            ['LO-0421', 4],
        ],
        'Kamihime Project 1.0' => [
            ['LO-0781', 4],
            ['LO-0782', 2],
            ['LO-0783', 4],
            ['LO-0784', 4],
            ['LO-0785', 4],
            ['LO-0786', 4],
            ['LO-0787', 4],
            ['LO-0788', 4],
            ['LO-0789', 2],
            ['LO-0790', 4],
            ['LO-0792', 2],
            ['LO-0793', 2],
            ['LO-0830', 3],
            ['LO-0842', 1],
            ['LO-0843', 4],
            ['LO-0844', 4],
            ['LO-0845', 4],
            ['LO-0846', 4],
        ],
        'Yuzusoft 1.0' => [
            ['LO-0662', 4],
            ['LO-0669', 4],
            ['LO-0671', 4],
            ['LO-0672', 2],
            ['LO-0673', 4],
            ['LO-0679', 4],
            ['LO-0680', 4],
            ['LO-0684', 2],
            ['LO-0686', 4],
            ['LO-0688', 4],
            ['LO-0700', 4],
            ['LO-0703', 4],
            ['LO-0712', 4],
            ['LO-0713', 4],
            ['LO-0714', 4],
            ['LO-0715', 4],
        ],
        'Visual Arts 1.0' => [
            ['LO-1293', 4],
            ['LO-1294', 4],
            ['LO-1295', 4],
            ['LO-1296', 4],
            ['LO-1171', 4],
            ['LO-1172', 4],
            ['LO-1173', 2],
            ['LO-1175', 2],
            ['LO-1178', 4],
            ['LO-1183', 4],
            ['LO-1187', 4],
            ['LO-1188', 2],
            ['LO-1189', 4],
            ['LO-1190', 2],
            ['LO-1276', 4],
            ['LO-1277', 4],
            ['LO-1278', 4],
        ],
        'Fate/Grand Order 1.0' => [
            ['LO-0041', 4],
            ['LO-0045', 2],
            ['LO-0048', 4],
            ['LO-0050', 4],
            ['LO-0052', 4],
            ['LO-0053', 2],
            ['LO-0057', 2],
            ['LO-0058', 2],
            ['LO-0059', 2],
            ['LO-0061', 4],
            ['LO-0063', 2],
            ['LO-0064', 2],
            ['LO-0066', 2],
            ['LO-0092', 2],
            ['LO-0093', 2],
            ['LO-0099', 2],
            ['LO-0105', 2],
            ['LO-0108', 4],
            ['LO-0109', 4],
            ['LO-0110', 4],
            ['LO-0111', 4],
        ],
        'Brave Sword X Blaze Soul 1.0' => [
            ['LO-0150', 4],
            ['LO-0154', 4],
            ['LO-0162', 2],
            ['LO-0164', 4],
            ['LO-0209', 4],
            ['LO-0216', 4],
            ['LO-0217', 4],
            ['LO-0223', 4],
            ['LO-0225', 4],
            ['LO-0227', 2],
            ['LO-0229', 2],
            ['LO-0239', 3],
            ['LO-0238', 3],
            ['LO-0261', 4],
            ['LO-0262', 4],
            ['LO-0263', 4],
            ['LO-0264', 4],
        ],
        'August 1.0' => [
            ['LO-0897', 4],
            ['LO-0898', 4],
            ['LO-0900', 2],
            ['LO-0901', 2],
            ['LO-0902', 4],
            ['LO-0903', 2],
            ['LO-0904', 4],
            ['LO-0905', 2],
            ['LO-0906', 2],
            ['LO-0907', 4],
            ['LO-0908', 2],
            ['LO-0910', 2],
            ['LO-0911', 2],
            ['LO-0912', 2],
            ['LO-0913', 2],
            ['LO-0966', 4],
            ['LO-0980', 4],
            ['LO-0981', 4],
            ['LO-0982', 4],
            ['LO-0983', 4],
        ],
    ];

    public function up()
    {
        try {
            \DB::transaction(function () {
                // Create the new pivot table.
                Schema::create(
                    'card_deck',
                    function (Blueprint $table) {
                        $table->string('card_id')->index();
                        $table->unsignedInteger('deck_id')->index();
                        $table->unique(['card_id', 'deck_id']);
                        $table->unsignedTinyInteger('quantity');

                        $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
                        $table->foreign('deck_id')->references('id')->on('decks')->onDelete('cascade');
                    }
                );

                /** @var Deck[] $decks */
                $decks = Deck::query()->whereIn('name_en', array_keys(self::$decks))->get();
                if (count($decks) !== count(self::$decks)) {
                    throw new \RuntimeException(sprintf(
                        '%d out of %d decks were found.',
                        count($decks),
                        count(self::$decks)
                    ));
                }

                $cardsById = Card::all()->keyBy('id');

                // Add in all the cards and quantities for the decks.
                foreach ($decks as $deck) {
                    $cards = self::$decks[$deck->name_en];
                    foreach ($cards as [$cardId, $quantity]) {
                        if (!isset($cardsById[$cardId])) {
                            // Card was not found. Perhaps this is a fresh migration?
                            continue;
                        }

                        $cardDeck = new CardDeck();
                        $cardDeck->card_id = $cardId;
                        $cardDeck->deck_id = $deck->id;
                        $cardDeck->quantity = $quantity;
                        $cardDeck->save();
                    }
                }

                // Remove the legacy "cards" column.
                Schema::table(
                    'decks',
                    function (Blueprint $table) {
                        $table->dropColumn('cards');
                    }
                );
            });
        } catch (\Exception $e) {
            Schema::dropIfExists('card_deck');

            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::transaction(function () {
            Schema::table(
                'decks',
                function (Blueprint $table) {
                    $table->string('cards')->after('name_en')->default('');
                }
            );

            $cardDecksByDeckId = CardDeck::all()->groupBy('deck_id');
            foreach ($cardDecksByDeckId as $deckId => $cardDecks) {
                $cardIds = $cardDecks->pluck('card_id');
                $cardListCommaSeparated = $cardIds->join(',');
                Deck::where('id', $deckId)->update(['cards' => $cardListCommaSeparated]);
            }

            Schema::drop('card_deck');
        });
    }
}
