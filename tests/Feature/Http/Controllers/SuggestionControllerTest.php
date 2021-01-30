<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\Locale;
use amcsi\LyceeOverture\Suggestion;
use amcsi\LyceeOverture\User;
use Tests\DatabaseTestCase;

class SuggestionControllerTest extends DatabaseTestCase
{
    private Card $card;
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

        $data = $this->getSuggestionInput();
        $data['ability_description'] = '日本語';

        $errors = self::assertStatus(422, $this->postJson('/api/suggestions', $data))->json('errors');

        self::assertIsArray($errors);
        self::assertCount(1, $errors);
        self::assertArrayHasKey('ability_description', $errors);
        self::assertSame(
            ['validation.no_japanese_characters'],
            $errors['ability_description']
        );
    }

    public function testSuccessfulSuggestion(): void
    {
        $this->actingAs($this->user);

        $data = $this->getSuggestionInput();

        $responseData = self::assertSuccessfulResponseData($this->postJson('/api/suggestions', $data));
        self::assertIsArray($responseData);
        self::assertSame($data, array_intersect_key($data, $responseData));
        Suggestion::findOrFail($responseData['id']);
    }

    public function testNewSubmissionEditsExistingOne(): void
    {
        $this->actingAs($this->user);

        $originalTranslator = User::factory()->create();
        $existingSuggestion = Suggestion::factory()->create([
            'card_id' => $this->card->id,
            'creator_id' => $originalTranslator->id,
        ]);

        $data = $this->getSuggestionInput();

        $responseData = self::assertSuccessfulResponseData($this->postJson('/api/suggestions', $data));
        self::assertIsArray($responseData);
        self::assertSame($data, array_intersect_key($data, $responseData));
        $suggestion = Suggestion::findOrFail($responseData['id']);

        self::assertTrue($suggestion->is($existingSuggestion));
        self::assertSame($originalTranslator->id, $suggestion->creator_id, 'Creator should not change');
        self::assertSame($data, array_intersect_key($data, $responseData));
    }

    public function testList()
    {
        Suggestion::factory()->create();
        $responseData = self::assertSuccessfulResponseData($this->get('/api/suggestions'));
        self::assertIsArray($responseData);
        self::assertCount(1, $responseData);
    }

    public function testCannotApproveUnlessHaveRights(): void
    {
        $this->actingAs($this->user);

        $data = $this->getSuggestionInput();
        $data['approved'] = 1;

        self::assertStatus(403, $this->postJson('/api/suggestions', $data));
    }

    public function testApprove(): void
    {
        $this->user->can_approve_locale = Locale::ENGLISH;
        $this->user->save();
        $this->actingAs($this->user);

        $data = $this->getSuggestionInput();
        $suggestion = Suggestion::create($data);
        CardTranslation::factory()->create([
            'card_id' => $this->getSuggestionInput()['card_id'],
            'locale' => Locale::ENGLISH_AUTO,
        ]);

        $data['approved'] = 1;

        self::assertSuccessfulResponseData($this->postJson('/api/suggestions', $data));
        $this->assertDeleted($suggestion); // Approving should remove the suggestion.
        $card = $this->japaneseTranslation->card;
        $card->load('translations');

        $cardTranslation = $card->getTranslation(Locale::ENGLISH);
        self::assertNotNull($cardTranslation);
        $this->assertTranslationMatchesSuggestion($suggestion, $cardTranslation);
        self::assertSame(0, $cardTranslation->kanji_count, 'The kanji count should be updated to 0');
    }

    public function testSuggestAndApproveInOneGo(): void
    {
        $this->user->can_approve_locale = Locale::ENGLISH;
        $this->user->save();
        $this->actingAs($this->user);

        CardTranslation::factory()->create([
            'card_id' => $this->getSuggestionInput()['card_id'],
            'locale' => Locale::ENGLISH_AUTO,
        ]);

        $data = $this->getSuggestionInput();
        $data['approved'] = 1;

        $responseData = self::assertSuccessfulResponseData($this->postJson('/api/suggestions', $data));
        self::assertArrayNotHasKey('id', $responseData, 'Suggestion never should have been saved.');
        $card = $this->japaneseTranslation->card;
        $card->load('translations');

        $cardTranslation = $card->getTranslation(Locale::ENGLISH);
        $this->assertTranslationMatchesSuggestion(Suggestion::make($this->getSuggestionInput()), $cardTranslation);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $translation = CardTranslation::factory()->create();
        $card = $translation->card;
        $card->type = Card\Type::CHARACTER;
        $card->save();

        $translation->ability_cost = '[宣言] [0]';
        $translation->ability_description = '日本語';
        $translation->save();

        $this->card = $card;
        $this->japaneseTranslation = $translation;
    }

    private function getSuggestionInput(): array
    {
        return [
            'card_id' => $this->card->id,
            'locale' => Locale::ENGLISH,
            'basic_abilities' => '',
            'pre_comments' => '',
            'ability_cost' => 'something',
            'ability_description' => 'something in English',
            'comments' => '',
        ];
    }

    private function assertTranslationMatchesSuggestion(
        Suggestion $suggestion,
        CardTranslation $cardTranslation
    ): void {
        foreach (array_keys($this->getSuggestionInput()) as $key) {
            self::assertSame($suggestion->{$key}, $cardTranslation->{$key});
        }
    }
}
