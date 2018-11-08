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
    </div>
</template>

<script>
  /** @class RulesPage */
  export default {
    name: 'RulesPage',
  };
</script>

<style scoped>

</style>
