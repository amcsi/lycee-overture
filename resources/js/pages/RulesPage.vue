<template>
    <div>
        <DeckAndCardRules />
        <FieldRules />
        <CostRules />
    </div>
</template>

<script>

  import CostRules from '../components/rules/CostRules';
  import DeckAndCardRules from '../components/rules/DeckAndCardRules';
  import FieldRules from '../components/rules/FieldRules';

  /** @class RulesPage */
  export default {
    name: 'RulesPage',
    components: { CostRules, FieldRules, DeckAndCardRules },
  };
</script>

<style scoped>
</style>
