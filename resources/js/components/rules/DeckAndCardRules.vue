<template>
  <section>
    <h2>{{ $t('rules.deckTitle') }}</h2>

    <p>{{ $t('rules.deckRules.overview') }}</p>

    <ul>
      <li>{{ $t('rules.deckRules.rule1') }}</li>
      <li>{{ $t('rules.deckRules.rule2') }}</li>
      <li>{{ $t('rules.deckRules.rule3') }}</li>
      <li>{{ $t('rules.deckRules.rule4') }}</li>
    </ul>

    <h2>{{ $t('rules.characterCard.title') }}</h2>

    <p>
      {{ $t('rules.characterCard.overview') }}<br />
      {{ $t('rules.characterCard.overviewNote') }}
    </p>

    <ImageWithAnatomy
      src="https://lycee-tcg.com/rule/images/index_2_img1.png"
      :alt="$t('rules.characterCard.imageAlt')"
    >
      <AnatomyRow number="1" :name="$t('rules.characterCard.cardName')">
        {{ $t('rules.characterCard.cardNameDescription') }}
      </AnatomyRow>
      <AnatomyRow number="2" :name="$t('rules.characterCard.element')">
        <CardText :text="$t('rules.characterCard.elementDescription')" />
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
      <AnatomyRow number="11" :name="$t('rules.characterCard.specialAbility.title')">
        {{ $t('rules.characterCard.specialAbility.description') }}<br />
        <CardText text="[Activate]" />
        : {{ $t('rules.characterCard.specialAbility.activate') }}<br />
        <CardText text="[Trigger]" />
        : {{ $t('rules.characterCard.specialAbility.trigger') }}<br />
        <CardText text="[Continuous]" />
        : {{ $t('rules.characterCard.specialAbility.continuous') }}<br />
        <CardText text="[Cost]" />
        : {{ $t('rules.characterCard.specialAbility.cost') }}<br />
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
    </ImageWithAnatomy>

    <h2>{{ $t('rules.eventCard.title') }}</h2>

    <p>
      {{ $t('rules.eventCard.description1') }}<br />
      {{ $t('rules.eventCard.description2') }}
    </p>

    <ImageWithAnatomy
      src="https://lycee-tcg.com/rule/images/index_2_img2.png"
      :alt="$t('rules.eventCard.imageAlt')"
    >
      <AnatomyRow number="1" :name="$t('rules.characterCard.cardName')">
        {{ $t('rules.characterCard.cardNameDescription') }}
      </AnatomyRow>
      <AnatomyRow number="2" :name="$t('rules.characterCard.element')">
        <CardText :text="$t('rules.characterCard.elementDescription')" />
      </AnatomyRow>
      <AnatomyRow number="3" :name="$t('rules.characterCard.ex')">
        {{ $t('rules.characterCard.exDescription') }}
      </AnatomyRow>
      <AnatomyRow number="4" :name="$t('rules.characterCard.cost')">
        {{ $t('rules.characterCard.costDescription') }}
      </AnatomyRow>
      <AnatomyRow number="5" :name="$t('rules.eventCard.effect')">
        {{ $t('rules.eventCard.effectDescription') }}
      </AnatomyRow>
    </ImageWithAnatomy>

    <h2>{{ $t('rules.itemCard.title') }}</h2>

    <p>
      {{ $t('rules.itemCard.description1') }}<br />
      {{ $t('rules.itemCard.description2') }}
    </p>

    <ul>
      <li>{{ $t('rules.itemCard.description1') }}</li>
      <li>{{ $t('rules.itemCard.description2') }}</li>
    </ul>

    <ImageWithAnatomy
      src="https://lycee-tcg.com/rule/images/index_2_img3.png"
      :alt="$t('rules.itemCard.imageAlt')"
    >
      <AnatomyRow number="1" :name="$t('rules.characterCard.cardName')">
        {{ $t('rules.characterCard.cardNameDescription') }}
      </AnatomyRow>
      <AnatomyRow number="2" :name="$t('rules.characterCard.element')">
        <CardText :text="$t('rules.characterCard.elementDescription')" />
      </AnatomyRow>
      <AnatomyRow number="3" :name="$t('rules.characterCard.ex')">
        {{ $t('rules.characterCard.exDescription') }}
      </AnatomyRow>
      <AnatomyRow number="4" :name="$t('rules.characterCard.cost')">
        {{ $t('rules.characterCard.costDescription') }}
      </AnatomyRow>
      <AnatomyRow number="5" :name="$t('rules.eventCard.effect')">
        {{ $t('rules.itemCard.effectDescription') }}
      </AnatomyRow>
    </ImageWithAnatomy>
  </section>
</template>

<script>
import CardText from '../card/CardText';
import AnatomyRow from './AnatomyRow';
import ImageWithAnatomy from './ImageWithAnatomy';

/** @class DeckAndCardRules */
export default {
  name: 'DeckAndCardRules',
  components: { CardText, ImageWithAnatomy, AnatomyRow },
};
</script>

<style scoped></style>
