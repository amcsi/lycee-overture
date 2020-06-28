<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\Suggestion;
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

    public function testPropertyHasJapaneseCharacters(): void
    {
        $this->actingAs($this->user);

        $data = [
            'card_id' => 'LO-0001',
            'locale' => Locale::ENGLISH,
            'basic_abilities' => '',
            'pre_comments' => '',
            'ability_cost' => 'something',
            'ability_description' => '日本語',
            'comments' => '',
        ];

        $errors = self::assertStatus(422, $this->postJson('/api/suggestions', $data))->json('errors');

        self::assertIsArray($errors);
        self::assertCount(1, $errors);
        self::assertArrayHasKey('ability_description', $errors);
        self::assertSame(
            ['The ability description must be fully translated; no japanese characters should remain.'],
            $errors['ability_description']
        );
    }

    public function testSuccessfulSuggestion(): void
    {
        $this->actingAs($this->user);

        $data = [
            'card_id' => 'LO-0001',
            'locale' => Locale::ENGLISH,
            'basic_abilities' => '',
            'pre_comments' => '',
            'ability_cost' => 'something',
            'ability_description' => 'something in English',
            'comments' => '',
        ];

        $responseData = self::assertSuccessfulResponseData($this->postJson('/api/suggestions', $data));
        self::assertIsArray($responseData);
        self::assertSame($data, array_intersect_key($data, $responseData));
        Suggestion::findOrFail($responseData['id']);
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
