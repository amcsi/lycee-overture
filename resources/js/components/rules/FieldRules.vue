<template>
  <section>
    <h2>{{ $t('rules.field.title') }}</h2>

    <p>{{ $t('rules.field.description') }}</p>

    <ul>
      <li>{{ $t('rules.field.point1') }}</li>
      <li>{{ $t('rules.field.point2') }}</li>
    </ul>

    <p>
      <cloudinary-fetch-img
        src="https://lycee-tcg.com/rule/images/index_3_img.jpg"
        :alt="$t('rules.field.imageAlt')"
      />
    </p>

    <h2>{{ $t('rules.graveyard.title') }}</h2>

    <p>
      {{ $t('rules.graveyard.description1') }}<br />
      {{ $t('rules.graveyard.description2') }}
    </p>

    <h2>{{ $t('rules.deckTitle') }}</h2>

    <p>
      {{ $t('rules.deck.description1') }}<br />
      {{ $t('rules.deck.description2') }}
    </p>

    <h2>{{ $t('rules.rowsAndColumns.title') }}</h2>

    <p>
      {{ $t('rules.rowsAndColumns.description') }}
    </p>

    <ul>
      <li>
        {{ $t('rules.rowsAndColumns.point1_line1') }}<br />
        {{ $t('rules.rowsAndColumns.point1_line2') }}<br />
        {{ $t('rules.rowsAndColumns.point1_line3') }}
      </li>
      <li>{{ $t('rules.rowsAndColumns.point2') }}</li>
      <li>{{ $t('rules.rowsAndColumns.point3') }}</li>
    </ul>

    <h2>{{ $t('rules.hand.title') }}</h2>

    <p>
      {{ $t('rules.hand.description1') }}<br />
      {{ $t('rules.hand.description2') }}
    </p>
  </section>
</template>

<script>
import CloudinaryFetchImg from '../common/CloudinaryFetchImg';

/** @class FieldRules */
export default {
  name: 'FieldRules',
  components: { CloudinaryFetchImg },
};
</script>

<style scoped></style>
