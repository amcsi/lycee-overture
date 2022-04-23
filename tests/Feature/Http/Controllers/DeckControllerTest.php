<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\CardDeck;
use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\Deck;
use Tests\DatabaseTestCase;

class DeckControllerTest extends DatabaseTestCase
{
    public function testIndex()
    {
        $this->actingAs($this->user);

        Deck::factory()->create();

        $data = self::assertSuccessfulResponseData($this->getJson('/api/decks'));
        self::assertNotEmpty($data);
        $item = $data[0];
        self::assertNotEmpty($item['id']);
        self::assertNotEmpty($item['name']);
    }

    public function testShow()
    {
        $this->actingAs($this->user);

        /** @var Deck $deck */
        $deck = Deck::factory()->create();
        $cards = CardTranslation::factory()->count(2)->create()->map->card;
        $cardDeck = new CardDeck();
        $cardDeck->card()->associate($cards[0]);
        $cardDeck->deck()->associate($deck);
        $cardDeck->quantity = 3;
        $cardDeck->save();
        $cardDeck = new CardDeck();
        $cardDeck->card()->associate($cards[1]);
        $cardDeck->deck()->associate($deck);
        $cardDeck->quantity = 3;
        $cardDeck->save();

        $data = self::assertSuccessfulResponseData($this->getJson('/api/decks/' . $deck->id));
        self::assertSame($deck->id, $data['id']);
        self::assertSame($deck->name_en, $data['name']);
        self::assertIsArray($data['cards']);
        self::assertCount(2, $data['cards']);
        $item = $data['cards'][0];
        self::assertSame(3, $item['quantity']);
        self::assertIsArray($item['card']);
        self::assertNotEmpty($item['card']['id']);
    }
}
