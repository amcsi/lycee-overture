<?php
declare(strict_types=1);

namespace Http\Controllers;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\CardTranslation;
use Tests\DatabaseTestCase;

class SuggestionControllerTest extends DatabaseTestCase
{
    private CardTranslation $japaneseTranslation;

    public function testWithoutAuth(): void
    {
        self::assertStatus(401, $this->postJson('/api/suggestions', []));
    }

    public function testMissingProperties(): void
    {
        $this->actingAs($this->user);
        $errors = self::assertStatus(422, $this->postJson('/api/suggestions', []))->json('errors');
        self::assertIsArray($errors);
        $requiredFields = [
            'card_id',
            'locale',
            'basic_abilities',
            'pre_comments',
            'ability_cost',
            'ability_description',
            'comments',
        ];
        foreach ($requiredFields as $requiredField) {
            self::assertArrayHasKey($requiredField, $errors);
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        $translation = factory(CardTranslation::class)->create();
        $card = $translation->card;
        $card->type = Card\Type::CHARACTER;
        $card->save();

        $translation->ability_cost = '[宣言] [0]';
        $translation->ability_description = '日本語';
        $translation->save();
        $this->japaneseTranslation = $translation;
    }
}
