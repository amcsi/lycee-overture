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
    private CardTranslation $japaneseTranslation;

    private static $suggestionInput = [
        'card_id' => 'LO-0001',
        'locale' => Locale::ENGLISH,
        'basic_abilities' => '',
        'pre_comments' => '',
        'ability_cost' => 'something',
        'ability_description' => 'something in English',
        'comments' => '',
    ];

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

        $data = self::$suggestionInput;
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

        $data = self::$suggestionInput;

        $responseData = self::assertSuccessfulResponseData($this->postJson('/api/suggestions', $data));
        self::assertIsArray($responseData);
        self::assertSame($data, array_intersect_key($data, $responseData));
        Suggestion::findOrFail($responseData['id']);
    }

    public function testNewSubmissionEditsExistingOne(): void
    {
        $this->actingAs($this->user);

        $originalTranslator = factory(User::class)->create();
        $existingSuggestion = factory(Suggestion::class)->create([
            'card_id' => 'LO-0001',
            'creator_id' => $originalTranslator->id,
        ]);

        $data = self::$suggestionInput;

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
        factory(Suggestion::class)->create();
        $responseData = self::assertSuccessfulResponseData($this->get('/api/suggestions'));
        self::assertIsArray($responseData);
        self::assertCount(1, $responseData);
    }

    public function testCannotApproveUnlessHaveRights(): void
    {
        $this->actingAs($this->user);

        $data = self::$suggestionInput;
        $data['approved'] = 1;

        self::assertStatus(403, $this->postJson('/api/suggestions', $data));
    }

    public function testApprove(): void
    {
        $this->user->can_approve_locale = Locale::ENGLISH;
        $this->user->save();
        $this->actingAs($this->user);

        $data = self::$suggestionInput;
        $suggestion = Suggestion::create($data);
        factory(CardTranslation::class)->create([
            'card_id' => self::$suggestionInput['card_id'],
            'locale' => Locale::ENGLISH_AUTO,
        ]);

        $data['approved'] = 1;

        self::assertSuccessfulResponseData($this->postJson('/api/suggestions', $data));
        $this->assertDeleted($suggestion); // Approving should remove the suggestion.
        $card = $this->japaneseTranslation->card;
        $card->load('translations');

        $cardTranslation = $card->getTranslation(Locale::ENGLISH);
        self::assertNotNull($cardTranslation);
        self::assertTranslationMatchesSuggestion($suggestion, $cardTranslation);
    }

    public function testSuggestAndApproveInOneGo(): void
    {
        $this->user->can_approve_locale = Locale::ENGLISH;
        $this->user->save();
        $this->actingAs($this->user);

        factory(CardTranslation::class)->create([
            'card_id' => self::$suggestionInput['card_id'],
            'locale' => Locale::ENGLISH_AUTO,
        ]);

        $data = self::$suggestionInput;
        $data['approved'] = 1;

        $responseData = self::assertSuccessfulResponseData($this->postJson('/api/suggestions', $data));
        self::assertArrayNotHasKey('id', $responseData, 'Suggestion never should have been saved.');
        $card = $this->japaneseTranslation->card;
        $card->load('translations');

        $cardTranslation = $card->getTranslation(Locale::ENGLISH);
        self::assertTranslationMatchesSuggestion(Suggestion::make(self::$suggestionInput), $cardTranslation);
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

    private static function assertTranslationMatchesSuggestion(
        Suggestion $suggestion,
        CardTranslation $cardTranslation
    ): void {
        foreach (array_keys(self::$suggestionInput) as $key) {
            self::assertSame($suggestion->{$key}, $cardTranslation->{$key});
        }
    }
}
