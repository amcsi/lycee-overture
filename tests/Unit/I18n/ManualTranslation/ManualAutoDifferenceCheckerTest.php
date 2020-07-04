<?php
declare(strict_types=1);

namespace I18n\ManualTranslation;

use amcsi\LyceeOverture\CardTranslation;
use amcsi\LyceeOverture\I18n\ManualTranslation\ManualAutoDifferenceChecker;
use amcsi\LyceeOverture\Suggestion;
use PHPUnit\Framework\TestCase;

class ManualAutoDifferenceCheckerTest extends TestCase
{
    public function testSuggestableTranslationsAreNotDifferent()
    {
        $baseProperties = array_fill_keys(Suggestion::SUGGESTABLE_PROPERTIES, null);
        $baseProperties = array_map(fn() => random_bytes(10), $baseProperties);

        $translation1 = $baseProperties;
        $translation1 = (new CardTranslation())->forceFill($translation1);
        $translation1['unrelatedProperty'] = 'Bob';

        $translation2 = $baseProperties;
        $basicAbilities = $translation2['basic_abilities'];
        // Order shouldn't matter; let's reorder properties.
        unset($translation2['basic_abilities']);
        $translation2['basic_abilities'] = $basicAbilities;

        $translation2['otherUnrelatedProperty'] = 'Joe';
        $translation2 = (new CardTranslation())->forceFill($translation2);

        self::assertFalse((new ManualAutoDifferenceChecker())->areSuggestablesDifferent(
            $translation1,
            $translation2
        ));
    }

    public function testSuggestableTranslationsAreDifferent()
    {
        $baseProperties = array_fill_keys(Suggestion::SUGGESTABLE_PROPERTIES, null);
        $baseProperties = array_map(fn() => random_bytes(10), $baseProperties);

        $translation1 = $baseProperties;
        $translation1['ability_description'] = 'Difference!';
        $translation1 = (new CardTranslation())->forceFill($translation1);

        $translation2 = $baseProperties;
        $translation2 = (new CardTranslation())->forceFill($translation2);

        self::assertTrue((new ManualAutoDifferenceChecker())->areSuggestablesDifferent(
            $translation1,
            $translation2
        ));
    }
}
