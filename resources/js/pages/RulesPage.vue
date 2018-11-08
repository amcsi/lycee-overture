<template>
    <div>
        <h1>{{ $t('nav.rules') }}</h1>

        <DeckAndCardRules />
        <FieldRules />
    </div>
</template>

<script>

  import DeckAndCardRules from '../components/rules/DeckAndCardRules';
  import FieldRules from '../components/rules/FieldRules';

  /** @class RulesPage */
  export default {
    name: 'RulesPage',
    components: { FieldRules, DeckAndCardRules },
  };
</script>

<style scoped>
</style>
