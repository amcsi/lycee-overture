<template>
    <div>
        <h3>{{ $t('rules.field.title') }}</h3>

        <p>{{ $t('rules.field.description') }}</p>

        <ul>
            <li>{{ $t('rules.field.point1') }}</li>
            <li>{{ $t('rules.field.point2') }}</li>
        </ul>

        <p>
            <img src="https://lycee-tcg.com/rule/images/index_3_img.jpg" :alt="$t('rules.field.imageAlt')" />
        </p>

        <h3>{{ $t('rules.graveyard.title') }}</h3>

        <p>
            {{ $t('rules.graveyard.description1') }}<br />
            {{ $t('rules.graveyard.description2') }}
        </p>

        <h3>{{ $t('rules.deckTitle') }}</h3>

        <p>
            {{ $t('rules.deck.description1') }}<br />
            {{ $t('rules.deck.description2') }}
        </p>

        <h3>{{ $t('rules.rowsAndColumns.title') }}</h3>

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
            <li>{{ $t('rules.rowsAndColumns.point4') }}</li>
        </ul>

        <h3>{{ $t('rules.hand.title') }}</h3>

        <p>
            {{ $t('rules.hand.description1') }}<br />
            {{ $t('rules.hand.description2') }}
        </p>
    </div>
</template>

<script>
  /** @class FieldRules */
  export default {
    name: 'FieldRules',
  };
</script>

<style scoped>

</style>
