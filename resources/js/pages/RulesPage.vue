<template>
    <div>
        <h1>{{ $t('nav.rules') }}</h1>

        <h3>{{ $t('rules.deck') }}</h3>

        <p>{{ $t('rules.deckRules.overview') }}</p>

        <ul>
            <li>{{ $t('rules.deckRules.rule1') }}</li>
            <li>{{ $t('rules.deckRules.rule2') }}</li>
            <li>{{ $t('rules.deckRules.rule3') }}</li>
            <li>{{ $t('rules.deckRules.rule4') }}</li>
        </ul>


        <h3>{{ $t('rules.characterCard.title') }}</h3>

        <p>
            {{ $t('rules.characterCard.overview') }}<br />
            {{ $t('rules.characterCard.overviewNote') }}
        </p>

        <div class="character-anatomy-container">
            <img src="https://lycee-tcg.com/rule/images/index_2_img1.png" :alt="$t('rules.characterCard.imageAlt')" />
            <AnatomyTable>
                <AnatomyRow number="1" :name="$t('rules.characterCard.cardName')">
                    {{ $t('rules.characterCard.cardNameDescription') }}
                </AnatomyRow>
                <AnatomyRow number="2" :name="$t('rules.characterCard.element')">
                    {{ $t('rules.characterCard.elementDescription') }}
                </AnatomyRow>
                <AnatomyRow number="3" :name="$t('rules.characterCard.ex')">
                    {{ $t('rules.characterCard.exDescription') }}
                </AnatomyRow>
                <AnatomyRow number="4" :name="$t('rules.characterCard.cost')">
                    {{ $t('rules.characterCard.costDescription') }}
                </AnatomyRow>
                <AnatomyRow number="5" :name="$t('rules.characterCard.positionRestriction')">
                    {{ $t('rules.characterCard.positionRestrictionDescription') }}
                </AnatomyRow>
                <AnatomyRow number="6" name="AP">
                    {{ $t('rules.characterCard.apDescription') }}
                </AnatomyRow>
                <AnatomyRow number="7" name="DMG">
                    {{ $t('rules.characterCard.dmgDescription') }}
                </AnatomyRow>
                <AnatomyRow number="8" name="DP">
                    {{ $t('rules.characterCard.dpDescription') }}
                </AnatomyRow>
                <AnatomyRow number="9" name="SP">
                    {{ $t('rules.characterCard.spDescription') }}
                </AnatomyRow>
                <AnatomyRow number="10" :name="$t('rules.characterCard.basicAbility')">
                    {{ $t('rules.characterCard.basicAbilityDescription') }}
                </AnatomyRow>
                <AnatomyRow number="11" :name="$t('rules.characterCard.specialAbility')">
                    {{ $t('rules.characterCard.specialAbilityDescription') }}
                </AnatomyRow>
                <AnatomyRow number="12" :name="$t('rules.characterCard.cardNumber')">
                    {{ $t('rules.characterCard.cardNumberDescription') }}<br />
                    {{ $t('rules.characterCard.cardNumberLettersExplanation') }}
                </AnatomyRow>
                <AnatomyRow number="13" :name="$t('rules.characterCard.type')">
                    {{ $t('rules.characterCard.typeDescription1') }}<br />
                    {{ $t('rules.characterCard.typeDescription2') }}
                </AnatomyRow>
                <AnatomyRow number="14" :name="$t('rules.characterCard.brand')">
                    {{ $t('rules.characterCard.brandDescription1') }}<br />
                    {{ $t('rules.characterCard.brandDescription2') }}
                </AnatomyRow>
            </AnatomyTable>
        </div>
    </div>
</template>

<script>
  import AnatomyRow from '../components/rules/AnatomyRow';
  import AnatomyTable from '../components/rules/AnatomyTable';

  /** @class RulesPage */
  export default {
    name: 'RulesPage',
    components: { AnatomyRow, AnatomyTable },
  };
</script>

<style scoped>
    .character-anatomy-container {
        display: flex;
        align-items: flex-start;
    }
</style>
